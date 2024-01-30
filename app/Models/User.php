<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'status'
    ];

    /**
     * Les attributs qui devraient être cachés lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui devraient être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relation One-To-One avec le modèle Profile.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relation One-To-One avec le modèle Company.
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Relation Many-To-Many avec le modèle Intern pour les favoris.
     */
    public function favorites()
    {
        return $this->belongsToMany(Intern::class, 'favorites', 'user_id', 'intern_id')->withTimestamps();
    }

    /**
     * Relation Many-To-Many avec le modèle Role.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}