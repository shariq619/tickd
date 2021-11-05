<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'days' => 'array',
    ];

    protected $appends = ['timings'];

    public function setDaysAttribute($value)
    {
        $days = [];

        foreach ($value as $array_item) {
                $days[] = $array_item;
        }

        $this->attributes['days'] = json_encode($days);
    }

    public function business()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getTimingsAttribute()
    {
        return $this->start_time.' - '.$this->end_time;
    }

}
