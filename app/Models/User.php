<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'mobile_number'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function mobileNumbers()
    {
        return $this->hasMany(MobileNumber::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_id');
    }

    public function groupConversations()
    {
        return $this->belongsToMany(GroupConversation::class, 'group_participants');
    }

    public function deletedMessages()
    {
        return $this->hasMany(DeletedMessage::class);
    }

    public function deletedStatuses()
    {
        return $this->hasMany(DeletedStatus::class);
    }

    public function userStatus()
    {
        return $this->hasOne(UserStatus::class);
    }

    // Generar número de 9 dígitos aleatorio para mobile_number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->mobile_number = self::generateRandomNumber();
        });
    }

    private static function generateRandomNumber()
    {
        return mt_rand(100000000, 999999999);
    }
}
