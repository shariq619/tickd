<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckUsernameRequest;
use App\Http\Requests\User\CreateProfileRequest;
use App\Http\Requests\User\EditProfileRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\ResendVerifyTokenRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\SendOtpRequest;
use App\Http\Requests\User\SignupRequest;
use App\Http\Requests\User\UpdateProfileInfoRequest;
use App\Http\Requests\User\VerifyTokenRequest;
use App\Models\StoreToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        try {
            $data = new User;
            $data->name = $request->name;
            $data->username = $request->username;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);

            $data->nationality = $request->nationality;
            $data->dob = $request->dob;
            $data->mobile = $request->mobile;
            $data->gender = $request->gender;
            $data->bio = $request->bio;

            $data->save();

            /*$mailInfo = [
                    'user' => $data,
                    'mail_for' => 'email_verification'
            ];
            api_send_mail($mailInfo);*/

            return api_success1('User created successfully!');

        } catch (\Exception $e) {
            return api_error('Message: ' . $e->getMessage(), 500);
        }

    }

    public function sendOtp(SendOtpRequest $request)
    {
        $data = User::whereEmail($request->email)->first();
        if (empty($data)) {
            return api_error("Email does not exists."); //
        }
        $mailInfo = [
            'user' => $data,
            'mail_for' => 'email_verification'
        ];
        api_send_mail($mailInfo);
        return api_success1('Otp sent successfully.');
    }

    public function verifyOtp(VerifyTokenRequest $request)
    {
        // check if email exists
        $data = User::whereEmail($request->email)->first();
        if (empty($data)) {
            return api_error("Email does not exists."); //
        }

        if ($result = StoreToken::where('verification_source', $request->email)->where('token', $request->otp)->first()) {

            // check if code is not expires
            if (Carbon::now() > $result->expires_at) {
                return api_error("Otp expired."); //
            }
            StoreToken::where('verification_source', $request->email)->delete();

            // update user status to active
            $data->update(['status' => '1']);

            return api_success1("Otp is valid");
        } else {
            return api_error("Invalid code!");
        }
    }

    public function resendVerificationCode(ResendVerifyTokenRequest $request)
    {
        $email = $request->email;
        $data = User::whereEmail($email)->first();

        if (!empty($data)) {

            $mailInfo = [
                'user' => $data,
                'mail_for' => 'email_verification'
            ];

            api_resend_code($mailInfo);
            return api_success1('Verification code sent successfully.');
        } else {
            return api_error('Invalid email address.');
        }
    }

    public function checkUsername(CheckUsernameRequest $request)
    {
        try {

            $username = $request->username;
            $data = User::whereUsername($username)->first();
            if (!empty($data)) {
                return api_success('User found.', $data);
            } else {
                return api_error('User does not exists.');
            }

        } catch (\Exception $ex) {
            return api_error('message: ' . $ex->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {

            // for login with both username and password
            if( $request->exists('username') && $request->filled('username')){
                $credentials = request(['username', 'password']);
            } else {
                $credentials = request(['email', 'password']);
            }

            if (!Auth::attempt($credentials))
                return api_error('Invalid credentials.');
            $user = $request->user();

            if (!$user->status)
                return api_error('Please verify your email address');

            $user->save();

            foreach ($user->tokens as $token) {
                $token->revoke();
            }

            $tokenObj = $user->createToken('user access token');
            $token = $tokenObj->token;
            $token->expires_at = Carbon::now()->addWeeks(4);
            $token->save();

            $token->accessToken;
            $token = $tokenObj->accessToken;
            $user->makeHidden('tokens');
            $data = Arr::add($user->toArray(), 'token_detail', ['access_token' => $token, 'token_type' => 'Bearer']);
            return api_success('Login Successfully', $data);

        } catch (\Exception $ex) {
            return api_error('message: ' . $ex->getMessage(), 500);
        }

    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $data = User::whereEmail($email)->first();
        if (!empty($data)) {
            $mailInfo = [
                'user' => $data,
                'mail_for' => 'forgot_password'
            ];
            api_send_mail($mailInfo); // Note: Credential required
            return api_success1('Verification code sent successfully.');
        } else {
            return api_error('Email does not exists.');
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = User::whereEmail($request->email)->first();
        if (empty($data)) {
            return api_error("Invalid email.");
        }
        $data->password = bcrypt($request->password);
        $data->save();
        return api_success1("Password changed successfully!");
    }

    public function createProfile(CreateProfileRequest $request)
    {

        if(!auth('api')->check()){
            return api_error('Message: Login required');
        }

        try {

            $data['name'] = $request->name;
            $data['bio'] = $request->bio;

            if ($request->has('avatar')) {
                $fileName = time() . '_' . $request->avatar->getClientOriginalName();
                $filePathAvatar = $request->file('avatar')->storeAs('user/avatar', $fileName, 'public');
                $data['avatar'] = env('APP_URL') . 'storage/' . $filePathAvatar;
            }

            $result = auth('api')->user();
            if ($result->update($data)) {
                return api_success1('Profile updated successfully');
            }
        } catch (\Exception $ex) {
            return api_error('message: ' . $ex->getMessage(), 500);
        }

    }

    public function getProfile()
    {
        if (auth('api')->check()) {
            $data = auth('api')->user();
            return api_success("Get profile info", ['data' => $data]);
        } else {
            return api_error('PLease login first');
        }
    }

    public function editProfile(EditProfileRequest $request)
    {
        if(!auth('api')->check()){
            return api_error('Message: Login required');
        }

        try {

            $data['name'] = $request->name;
            $data['username'] = $request->username;
            $data['bio'] = $request->bio;

            if ($request->has('avatar')) {
                $fileName = time() . '_' . $request->avatar->getClientOriginalName();
                $filePathAvatar = $request->file('avatar')->storeAs('user/avatar', $fileName, 'public');
                $data['avatar'] = env('APP_URL') . 'storage/' . $filePathAvatar;
            }

            $result = auth('api')->user();
            if ($result->update($data)) {
                return api_success1('Profile updated successfully');
            }
        } catch (\Exception $ex) {
            return api_error('message: ' . $ex->getMessage(), 500);
        }

    }

    public function logout()
    {
        if (auth('api')->check()) {
            $user = auth('api')->user();
            $user->token()->revoke();
            return api_success1("Logout successfully!");
        } else {
            return api_error('PLease login first');
        }

        return api_error('Unable to logout');
    }

    // get badges

    public function getBadges()
    {
        $user = auth('api')->user();
        $data = $user->load('badges');
        return api_success('Badges',$data);
    }


    // Followers & Followings

    public function getFollowers()
    {

        $user = auth('api')->user();

        $data = $user->load('followers');
        return api_success('Followers', $data);
    }

    public function getFollowings()
    {
        $user = auth('api')->user();

        $data = $user->load('followings');
        return api_success('Followings', $data);
    }

}
