<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * A Messages belong to User
     *
     * @return this
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
