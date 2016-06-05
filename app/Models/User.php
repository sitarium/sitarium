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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function websites()
    {
        return $this->belongsToMany('\App\Models\Website');
    }
    
    public function isAuthorizedOnWebsite($website)
    {
        $is_authorized = false;
        /*
         * TODO refactor using Collection
         */
        foreach ($this->websites as $authorized_site)
        {
            if ($authorized_website->name === $website->name)
            {
                $is_authorized = true;
                break;
            }
        }
        return $is_authorized;
    }
}
