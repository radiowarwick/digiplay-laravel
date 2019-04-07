<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Email;

class MessageController extends Controller
{
    public function getMessage(Request $request) {
        $limit = (!$request->get('limit')) ? 20 : $request->get('limit');
        $query = Email::orderby('datetime', 'DESC')->limit($limit);

        $array = [];
        foreach($query->get() as $m) {
            $array[] = [
                'id' => $m->id,
                'sender' => $m->sender,
                'subject' => $m->subject,
                'body' => $m->body,
                'datetime' => $m->datetime,
            ];
        }

        return response()->json($array);
    }

    public function postMessage(Request $request) {
        $validator = Validator::make($request->all(), [
            'sender' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'There was missing data, expecting to see "sender", "subject" and "body".',
            ]);
        }

        $new_email = new Email();
        $new_email->new_flag = 't';
        $new_email->sender = $request->input('sender');
        $new_email->subject = $request->input('subject');
        $new_email->body = $request->input('body');
        $new_email->datetime = time();

        if($new_email->save()) {
            return response()->json([
                'status' => 'ok',
            ]);
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not save the message correctly.',
            ]);
        }
    }
}
