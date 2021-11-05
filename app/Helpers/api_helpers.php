<?php

use App\Models\StoreToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

function api_success1($message) {
    $data = array('status' => true, 'response' => array('message' => $message));
    return response()->json($data);
}

function api_success($message, $data) {
    $data = array('status' => true, 'response' => array('message' => $message, 'detail' => $data));
    return response()->json($data);
}

function api_error($message, $httpcode = 422) {
    $data = array('status' => false, 'error' => array('message' => $message));
    return response()->json($data, $httpcode);
}

function api_send_mail($mailInfo){
    try {
        $user = $mailInfo['user'];
        $mailFor = $mailInfo['mail_for'];

        if($mailFor == 'email_verification'){
            $subject = config('app.name') . ' | Email Verification Code!';
            $template = 'emails.email-verification';
        }
        elseif ($mailFor == 'forgot_password'){
            $subject = config('app.name') . ' | Automatic Generated Email: Password Reset Code';
            $template = 'emails.forgot-password';
        }else{
            return 'failed to send';
        }

        $getTokenData = storeToken($user->email);

        Mail::send($template, ['code' => $getTokenData['response']['token'], 'user' => $user->first_name.' '.$user->last_name], function($message) use ($user, $subject) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($user->email, $user->first_name.' '.$user->last_name);
            $message->subject($subject);
        });
        return ['status' => 'success', 'response' => []];
    }

    catch(Exception $e) {
        return ['status' => 'error', 'response' => 'Message: ' .$e->getMessage()];
    }

}

function api_resend_code($mailInfo){
    try {

        $user = $mailInfo['user'];
        $mailFor = $mailInfo['mail_for'];

        if($mailFor == 'email_verification'){
            $subject = config('app.name') . ' | Email Verification Code!';
            $template = 'emails.email-verification';
        }
        elseif ($mailFor == 'forgot_password'){
            $subject = config('app.name') . ' | Automatic Generated Email: Password Reset Code';
            $template = 'emails.forgot-password';
        }else{
            return 'failed to send';
        }

        $getTokenData = storeToken($user->email);

        Mail::send($template, ['code' => $getTokenData['response']['token'], 'user' => $user->first_name.' '.$user->last_name], function($message) use ($user, $subject) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($user->email, $user->first_name.' '.$user->last_name);
            $message->subject($subject);
        });

        return ['status' => 'success', 'response' => []];

    } catch (Exception $e) {
        return ['status' => 'error', 'response' => 'Message: ' .$e->getMessage()];
    }
}



function storeToken($source){
    try {
        StoreToken::where('verification_source', $source)->delete();
        $currentTime = Carbon::now();
        $token = rand(1000, 9999);
        $storeCode = new StoreToken;
        $storeCode->verification_source = $source;
        $storeCode->token = $token;
        $storeCode->expires_at = $currentTime->addMinutes(60);
        $storeCode->save();
        return ['status' => 'success', 'response' => $storeCode];
    }
    catch(Exception $e) {
        return ['status' => 'error', 'response' => 'Message: ' .$e->getMessage()];
    }
}

