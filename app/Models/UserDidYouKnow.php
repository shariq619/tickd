<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDidYouKnow extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function did_you_know()
    {
        return $this->belongsTo(DidYouKnow::class);
    }
}
