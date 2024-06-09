<?php

// app/Models/DeletedStatus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
