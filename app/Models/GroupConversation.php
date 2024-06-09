<?php
// app/Models/GroupConversation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'group_participants');
    }
}
