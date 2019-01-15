<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
	public function getLogin(Request $request) {
		return view('login');
	}

	public function getLogout(Request $request) {
		auth()->logout();
		return redirect()->route('login')->with('status', 'Logged out successfully!');
	}

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
			abort(404, 'Page not found!');
		}

		parse_str($result->getBody()->getContents(), $args);
		$request->session()->put('oauth_token_secret', $args['oauth_token_secret']);
		return redirect('https://websignon.warwick.ac.uk/oauth/authorise?oauth_token=' . $args['oauth_token'] . '&oauth_callback=' . rawurlencode('https://dev.radio.warwick.ac.uk/callback'));
	}

	public function getCallback(Request $request) {
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

		$parameters['oauth_signature'] = $this->signature($request->session()->get('oauth_token_secret'), 'POST', $url, $parameters);
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
			abort(404, 'Page not found!');
		}

		parse_str($result->getBody()->getContents(), $args);

		$member_id = str_replace('u', '', $args['user_id']);
		$target = User::where('username', $member_id)->first();
		if(is_null($target)) {
			return redirect()->route('login')->with('status', 'Not a RAW member, please get membership');
		}
		else {
			if(is_null($target->name)) {
				$attributes = $this->getUserAttributes($args['oauth_token'], $args['oauth_token_secret']);
				$target->name = $attributes['name'];
				$target->save();
			}

			if(auth()->loginUsingId($target->id, true)) {
				if($request->session()->has('login-redirect'))
					return redirect($request->session()->pull('login-redirect'));
				else
					return redirect()->route('index');
			}
			else {
				abort(500, 'Can\'t login user');
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
			abort(500, 'Can\'t access resource');
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
}

