<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Events organised by this user.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Orders placed by this user (as a customer).
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'date_of_birth',
        'password_hash',
        'profile_picture_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_hash' => 'hashed',
        ];
    }

    /**
     * Override the get password name method for authorization, since the default is 'password'
     *
     * @return string
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }
}
