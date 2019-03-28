<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
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
                'sender' => $m->sender,
                'subject' => $m->subject,
                'body' => $m->body,
                'datetime' => $m->datetime,
            ];
        }

        return response()->json($array);
    }
}
