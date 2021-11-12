<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckUsernameRequest;
use App\Http\Requests\User\CreateBadgeRequest;
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
use App\Models\Badge;
use App\Models\BusinessType;
use App\Models\Follower;
use App\Models\StoreToken;
use App\Models\User;
use App\Models\UserBadge;
use App\Notifications\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            if ($request->exists('username') && $request->filled('username')) {
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

        if (!auth('api')->check()) {
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

            if (request()->filled('user_id')) {
                $user = User::find(request()->user_id);
                $data = $user->load('user_badges.badge');

                foreach ($data->user_badges as $badge) {
                    unset($badge->badge->business_id);
                    unset($badge->badge->created_at);
                    unset($badge->badge->updated_at);
                    $badges[] = $badge->badge;
                }

                $u = User::find(request()->user_id);
                $u['badges'] = $badges;
                $u['total_badges'] = count($badges);
                return api_success("Get profile info", ['data' => $u]);

            } else {
                $user = auth('api')->user();
                $data = $user->load('friends');
                // dd($user->friends);
                //$data = auth('api')->user();
                return api_success("Get profile info", ['data' => $data]);
            }

        } else {
            return api_error('PLease login first');
        }
    }

    public function editProfile(EditProfileRequest $request)
    {
        if (!auth('api')->check()) {
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

    // get user badges
    public function getUserBadges()
    {
        $badges = [];
        $user = auth('api')->user();
        $data = $user->load('user_badges.badge');


        foreach ($data->user_badges as $badge) {

            $bu = User::find($badge->badge->business_id);
            $badge->badge['city'] = $bu->city->name;

            unset($badge->badge->business_id);
            unset($badge->badge->created_at);
            unset($badge->badge->updated_at);

            if (request()->filled('badge_id') && request()->badge_id == $badge->badge->id) {
                $badges[] = $badge->badge;
            } elseif (!request()->filled('badge_id')) {
                $badges[] = $badge->badge;
            }

        }

        if (count($badges)) {
            return api_success('Badges', $badges);
        } else {
            return api_error('Badges not found.');
        }
    }

    // get user groups
    public function getUserGroups()
    {
        $groups = [];
        $user = auth('api')->user();
        $data = $user->load('user_groups');

        foreach ($data->user_groups as $group) {
            unset($group->user_id);
            unset($group->badge_id);
            unset($group->created_at);
            unset($group->updated_at);

            if (request()->filled('group_id') && request()->group_id == $group->id) {
                $groups[] = $group;
            } elseif (!request()->filled('group_id')) {
                $groups[] = $group;
            }
        }
        if (count($groups)) {
            return api_success('Groups', $groups);
        } else {
            return api_error('Groups not found.');
        }
    }

    // get user cities
    public function getUserCities()
    {
        $cities = [];
        $user = auth('api')->user();
        $data = $user->load('user_badges.badge.city');
        foreach ($data->user_badges as $badge) {
            unset($badge->badge->city->created_at);
            unset($badge->badge->city->updated_at);


            if (request()->filled('city_id') && request()->city_id == $badge->badge->city->id) {
                $cities[] = $badge->badge->city;
            } elseif (!request()->filled('city_id')) {
                $cities[] = $badge->badge->city;
            }

        }
        if (count($cities)) {
            return api_success('Cities', array_unique($cities));
        } else {
            return api_error('Cities not found.');
        }
    }

    public function profileInterest()
    {
        $user = auth('api')->user();
        $data = $user->load('user_business_types.business_type.business_type', 'user_stickers.sticker');
        return api_success('Profile Interest', $data);
    }

    public function getBusinessProfile(Request $request)
    {
        $bid = $request->badge_id;
        $data = UserBadge::with('badge', 'user.user_events.event', 'user.user_challenges.challenge', 'user.user_offers.offer')->where('badge_id', $bid)->get();
        // ,'badge.business_user.challenges','badge.business_user.offers'
        return api_success('Badges Detail By ID', $data);
    }

    public function friendsData()
    {
        $tot = [];
        $suggested = [];
        $user = auth('api')->user();
        $data = $user->load('friend_requests', 'friends');

        foreach ($data->friend_requests as $request) {
            $tot[] = $request;
        }

        // for suggested friends
        $sug_friends = DB::select(
            "SELECT
            a.friend_id,
            COUNT(*) as relevance,
            GROUP_CONCAT(a.user_id ORDER BY a.user_id) as mutual_friends
            FROM
            friends a
            JOIN
            friends b
            ON  (
             b.friend_id = a.user_id
             AND b.user_id = " . auth('api')->user()->id . "
            )
            LEFT JOIN
            friends c
            ON
            (
             c.friend_id = a.friend_id
             AND c.user_id = " . auth('api')->user()->id . "
            )
            WHERE
            c.user_id IS NULL
            AND
            a.friend_id != " . auth('api')->user()->id . "
            GROUP BY
            a.friend_id
            ORDER BY
            relevance DESC"
        );

        foreach ($sug_friends as $sug_friend) {
            $suggested[] = User::where('id', $sug_friend->friend_id)->get();
        }

        $data['suggested_friends'] = $suggested;
        $data['total_requests'] = count($tot);

        $data['total_requests'] = count($tot);
        return api_success('Find Friends', $data);
    }

    public function getUserDidYouKnow()
    {
        $user = auth('api')->user();
        $data = $user->load('user_did_you_knows.did_you_know');
        return api_success('User did you know', $data);
    }

    // Followers & Followings
    public function getFollowers()
    {
        $user = auth('api')->user();

        $data = $user->load('followers');
        return api_success('Followers', $data);
    }

    public function followUser()
    {
        $follow = false;
        if (request()->filled('follower_id')) {
            // check if its already following or not
            $f = Follower::where('follower_id', auth('api')->user()->id)->where('leader_id', request()->follower_id)->first();

            if ($f) {
                return api_error('Already following.');
            } else {
                $follow = Follower::create([
                    'follower_id' => auth('api')->user()->id,
                    'leader_id' => request()->follower_id
                ]);

                // firebase notification to the user who is followed by this user
                //$ago = \Carbon\Carbon::createFromTimeStamp(strtotime($follow->created_at))->diffForHumans();
                $u = User::find(request()->follower_id);
                $title = "Follow Notification";
                $body = "@" . auth('api')->user()->username . ' - follows you';
                $u->notify(new PushNotification(
                    $title,
                    $body
                ));


            }
        }

        if ($follow) {
            return api_success('Successfully following', $follow);
        } else {
            return api_error('Failed to follow.');
        }
    }

    public function getFollowings()
    {
        $user = auth('api')->user();

        $data = $user->load('followings');
        return api_success('Followings', $data);
    }

    //
    public function createUserBadge(CreateBadgeRequest $request)
    {
        if (auth('api')->check()) {
            Badge::create([
                'user_id' => auth('api')->user()->id,
                'name' => $request->name,
                'location' => $request->location,
                'duration' => $request->duration,
                'privacy' => $request->privacy,
            ]);
            return api_success('Badge', 'Badge created successfully');
        } else {
            return api_error('PLease login first');
        }
    }

}
