<?php
// app/Models/Conversation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
