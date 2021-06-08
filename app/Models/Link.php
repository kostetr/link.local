<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id', 'long_link', 'key', 'transitions', 'life_time_minutes'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
