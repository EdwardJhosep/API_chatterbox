<?php

// app/Models/GroupParticipant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupParticipant extends Model
{
    use HasFactory;

    protected $table = 'group_participants';
    protected $primaryKey = ['user_id', 'group_conversation_id'];
    public $incrementing = false;
    protected $fillable = ['user_id', 'group_conversation_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groupConversation()
    {
        return $this->belongsTo(GroupConversation::class);
    }
}
