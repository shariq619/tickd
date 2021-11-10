<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'user'], function () {

        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('send-otp', [AuthController::class, 'sendOtp']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('resend-verification-token', [AuthController::class, 'resendVerificationCode']);
        Route::post('check-username', [AuthController::class, 'checkUsername']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('create-profile', [AuthController::class, 'createProfile']);
        Route::post('get-profile', [AuthController::class, 'getProfile']);
        Route::post('edit-profile', [AuthController::class, 'editProfile']);

        Route::post('get-user-badges', [AuthController::class, 'getUserBadges']);
        Route::post('get-user-groups', [AuthController::class, 'getUserGroups']);
        Route::post('get-user-cities', [AuthController::class, 'getUserCities']);

        Route::post('get-profile-interest', [AuthController::class, 'profileInterest']);



        Route::post('get-user-didyouknow', [AuthController::class, 'getUserDidYouKnow']);

        Route::post('get-followers', [AuthController::class, 'getFollowers']);

        Route::post('follow-user', [AuthController::class, 'followUser']);

        Route::post('get-followings', [AuthController::class, 'getFollowings']);

        Route::post('logout', [AuthController::class, 'logout']);


        // events
        Route::post('get-events', [EventController::class, 'getEvents']);

        // challenges
        Route::post('get-challenges', [ChallengeController::class, 'getChallenges']);

        // offers
        Route::post('get-offers', [OfferController::class, 'getOffers']);

        /*
        Route::post('verify-token', [AuthController::class, 'verifyToken']);

        Route::post('resend-otp', [AuthController::class, 'resendOtp']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('get-profile', [AuthController::class, 'getProfile']);
        Route::post('contact-us', [AuthController::class, 'contactUs']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);*/
    });

});

/*Route::middleware('auth:api')->group(function () {
    Route::resource('products', ProductController::class);
});*/
