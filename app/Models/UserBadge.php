<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['badge_name'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class,'badge_id');
    }

    public function getBadgeNameAttribute()
    {
        return Badge::find($this->badge_id)->name;
    }



    /*public function user_badge()
    {
        return $this->belongsTo(Badge::class);
    }*/

}
