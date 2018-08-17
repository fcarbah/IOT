<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','deleted_at'
    ];
    
    
    public function accountPolicy(){
        return $this->hasManyThrough('App\Models\Roles','App\Models\AccountPolicy');
    }
    
    public function role(){
        return $this->belongsTo('App\Models\Roles');
    }
    
}
