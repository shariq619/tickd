<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSticker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sticker()
    {
        return $this->belongsTo(Sticker::class);
    }

}
