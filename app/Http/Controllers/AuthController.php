<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
	public function getLogin(Request $request) {
		// Clear the session from any previous login attempts
		$request->session()->forget('login_redirect');
		$request->session()->forget('oauth_token_secret');

		if(auth()->check())
			return redirect()->route('index');
		else
			return view('login');
	}

	public function getLogout(Request $request) {
		auth()->logout();
		return redirect()->route('login')->with('status', 'Logged out successfully!');
	}

	/* 
	* The first step in the OAuth 1 protocol
	* 1) Setup the parameters which are required to be used
	* 2) Generate a signature based on the parameters
	* 3) Send a request to the 'requestToken' endpoint
	* 4) If we get a response redirect the user to Warwick ITS authorise endpoint
	*/
	public function getOAuth(Request $request) {
		$request->session()->forget('oauth_token_secret');

		$url = 'https://websignon.warwick.ac.uk/oauth/requestToken';
		$time = time();
		$nonce = uniqid();

		$parameters = [
			'oauth_consumer_key' => env('WARWICK_SSO_CONSUMER_KEY'),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp' => $time,
			'oauth_nonce' => $nonce,
		];
		$scope = 'urn:websignon.warwick.ac.uk:sso:service';

		$signature_params = $parameters;
		$signature_params['scope'] = $scope;

		$parameters['oauth_signature'] = $this->signature('', 'POST', $url, $signature_params);
		$client = new \GuzzleHttp\Client();
		try {
			$result = $client->request('POST', $url, [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => $this->auth_header($parameters)
				],
				'body' => 'scope=' . $scope,
			]);
		}
		catch (\Exception $e) {
			return $this->login_redirect($request)->with('status', 'Unable to contact Warwick ITS');
		}

		parse_str($result->getBody()->getContents(), $args);
		$request->session()->put('oauth_token_secret', $args['oauth_token_secret']);
		return redirect('https://websignon.warwick.ac.uk/oauth/authorise?oauth_token=' . $args['oauth_token'] . '&oauth_callback=' . rawurlencode(route('login-callback')));
	}

	/*
	* Third step in the OAuth 1 protocol (after the user has validated themselves with ITS)
	* 1) Setup parameters for request
	* 2) Generate signature based on params (including the secret key from previous stage)
	* 3) Request the access token from ITS
	* 4) Check the response for the users ID
	* 5) Find the user in the database, if they don't exist give error
	* 6) Login the user, if they have no name in database fetch it
	*/
	public function getCallback(Request $request) {
		if(!$request->session()->has('oauth_token_secret') or is_null($request->get('oauth_token')))
			return $this->login_redirect($request)->with('status', 'No token secret received');

		$oauth_token_secret = $request->session()->pull('oauth_token_secret');
		$url = 'https://websignon.warwick.ac.uk/oauth/accessToken';
		$time = time();
		$nonce = uniqid();

		$parameters = [
			'oauth_consumer_key' => env('WARWICK_SSO_CONSUMER_KEY'),
			'oauth_token' => $request->get('oauth_token'),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp' => $time,
			'oauth_nonce' => $nonce,
		];

		$parameters['oauth_signature'] = $this->signature($oauth_token_secret, 'POST', $url, $parameters);
		$client = new \GuzzleHttp\Client();
		try {
			$result = $client->request('POST', $url, [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => $this->auth_header($parameters)
				],
			]);
		}
		catch (\Exception $e) {
			return $this->login_redirect($request)->with('status', 'Issue authenticating user');
		}

		parse_str($result->getBody()->getContents(), $args);

		$member_id = str_replace('u', '', $args['user_id']);
		$target = User::where('username', $member_id)->first();
		if(is_null($target)) {
			return $this->login_redirect($request)->with('status', 'Not a RAW member, please get membership');
		}
		else {
			if(is_null($target->name)) {
				$attributes = $this->getUserAttributes($args['oauth_token'], $args['oauth_token_secret']);
				$target->name = $attributes['name'];
				$target->save();
			}

			if(auth()->loginUsingId($target->id, true)) {
				return $this->login_redirect($request);
			}
			else {
				return $this->login_redirect($request)->with('status', 'Failed to login user');
			}
		}
	}

	private function getUserAttributes($access_token, $access_secret) {
		$url = 'https://websignon.warwick.ac.uk/oauth/authenticate/attributes';
		$time = time();
		$nonce = uniqid();

		$parameters = [
				'oauth_consumer_key' => env('WARWICK_SSO_CONSUMER_KEY'),
				'oauth_token' => $access_token,
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_timestamp' => $time,
				'oauth_nonce' => $nonce,
		];
		$parameters['oauth_signature'] = $this->signature($access_secret, 'POST', $url, $parameters);

		$client = new \GuzzleHttp\Client();
		try {
			$result = $client->request('POST', $url, [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => $this->auth_header($parameters)
				],
			]);
		}
		catch (\Exception $e) {
			return $this->login_redirect($request)->with('status', 'Unable to fetch user details');
		}

		$text_result = $result->getBody()->getContents();
		$attributes = [];
		foreach(preg_split('/((\r?\n)|(\r\n?))/', $text_result) as $row) {
			$split = explode('=', $row);
			if(count($split) == 2)
				$attributes[$split[0]] = $split[1];
		}
		return $attributes;
	}

	private function auth_header($parameters) {
		$header = 'OAuth ';
		$parts = [];
		foreach($parameters as $key => $value) {
			$parts[] = $key . '="' . $value . '"';
		}
		$header .= implode(', ', $parts);
		return $header;
	}

	private function signature($secret_key, $method, $url, $parameters) {
		$secret_key = env('WARWICK_SSO_CONSUMER_SECRET') . '&' . $secret_key;

		$base_string = '';
		$base_string .= $method . '&';
		$base_string .= rawurlencode($url) . '&';

		ksort($parameters);
		$parameter_string = '';
		$first = true;
		foreach($parameters as $key => $value) {
			if($first)
				$first = false;
			else
				$parameter_string .= '&';
			$parameter_string .= rawurlencode($key) . '=' . urlencode($value);
		}

		$base_string .= rawurlencode($parameter_string);
		return rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $secret_key, true)));
	}

	private function login_redirect(Request $request) {
		if($request->session()->has('login_redirect'))
			return redirect($request->session()->get('login_redirect'));
		else
			return redirect()->route('login');
	}
}

