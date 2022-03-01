<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SmsController extends Controller
{

    public function send(Request $request)
    {
        $phone = $request->post('phone');
        $user = $request->post('user_id');
        if ($phone && $user) {
            return response()->json(['message' => 'SMS for '.$phone. ' send'], 200);
        }

        return response()->json(['message' => 'Fields is required but not provided'], 400);

    }

}
