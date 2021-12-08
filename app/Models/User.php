<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'remember_token',
        'role_id',
        'school_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * check if admin exists
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $filter)
    {
        return $query->when($filter['name'], function ($query) use ($filter) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        })->when($filter['username'], function ($query) use ($filter) {
            $query->where('username', 'like', '%' . $filter['username'] . '%');
        })->when($filter['role_id'], function ($query) use ($filter) {
            $query->where('role_id', '=', $filter['role_id']);
        })->when($filter['email'], function ($query) use ($filter) {
            $query->where('email', 'like', '%' . $filter['email'] . '%');
        });
    }


    /**
     * User belongs to Role
     *
     * @return this
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User belongs to School
     *
     * @return this
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
