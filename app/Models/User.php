<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_events()
    {
        return $this->hasMany(UserEvent::class,'user_id');
    }

    public function business_events()
    {
        return $this->hasMany(Event::class,'business_id');
    }



    public function challenges()
    {
        return $this->hasMany(Challenge::class,'business_id');
    }

    public function user_challenges()
    {
        return $this->hasMany(UserChallenge::class,'user_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class,'business_id');
    }

    public function user_offers()
    {
        return $this->hasMany(UserOffer::class,'user_id');
    }

    public function badges()
    {
        return $this->hasMany(Badge::class);
    }


    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'leader_id', 'follower_id')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'leader_id')->withTimestamps();
    }

    public function user_badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function user_groups()
    {
        return $this->hasMany(UserGroup::class);
    }

    public function user_did_you_knows()
    {
        return $this->hasMany(UserDidYouKnow::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function friends()
    {
        return $this->belongsToMany(self::class, 'friends', 'user_id', 'friend_id');
        // if you want to rely on accepted field, then add this:
        //->wherePivot('accepted', '=', 1);
    }

    public function friend_requests()
    {
        return $this->belongsToMany(self::class, 'friends', 'user_id', 'friend_id')->wherePivot('accepted', '=', 0);
    }

    public function user_stickers()
    {
        return $this->hasMany(UserSticker::class, 'user_id');
    }

    public function business_user()
    {
        return $this->hasOne(UserBusiness::class);
    }


    public function user_business_types()
    {
        return $this->hasMany(UserBusinessType::class);
    }

    public function business_type()
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function business()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /*public function business_type_data()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }*/

}
