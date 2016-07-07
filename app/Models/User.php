<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'admin' => 'boolean',
    ];

    public function websites()
    {
        return $this->belongsToMany('\App\Models\Website');
    }

    public function isAuthorizedOnWebsite($website)
    {
        return collect($this->websites)->contains('name', $website->name);
    }
}
