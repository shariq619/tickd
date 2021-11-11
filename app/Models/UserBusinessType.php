<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBusinessType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business_type()
    {
        return $this->belongsTo(User::class,'business_id');
    }


}
