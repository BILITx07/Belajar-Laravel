<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Accessor untuk Username (fallback ke prefix email jika kosong)
     */
    public function getDisplayUsernameAttribute(): string
    {
        if (!empty($this->attributes['username'])) {
            return $this->attributes['username'];
        }
        if (!empty($this->email)) {
            return explode('@', $this->email)[0];
        }
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $this->name ?? 'user'));
    }

    /**
     * Accessor untuk Role Label
     */
    public function getRoleLabelAttribute(): string
    {
        return match (strtolower($this->role ?? 'user')) {
            'superadmin' => 'Super Admin',
            'admin'      => 'Administrator',
            'operator'   => 'Operator',
            default      => 'User',
        };
    }
}
