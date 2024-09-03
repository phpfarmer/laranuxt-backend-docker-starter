<?php

namespace App\Models;

use App\Notifications\UserEmailUpdateVerification;
use App\Traits\PropertyVisibilityTrait;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserEmailUpdate extends Model implements MustVerifyEmailContract
{
    use Notifiable, SoftDeletes, MustVerifyEmail, PropertyVisibilityTrait;

    protected $table = 'user_email_updates';

    protected $fillable = ['user_id', 'old_email', 'email', 'token'];

    protected array $visibleProperties = ['email'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token',
        'email_verified_at',
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserEmailUpdateVerification());
    }

    public function getVerificationToken()
    {
        return $this->token;
    }
}

