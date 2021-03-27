<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function divideGroups()
    {
        return $this->hasMany(DivideGroup::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class,'group_users','user_id','group_id')->where('type',1);

    }
    public function createGroups()
    {
        return $this->hasMany(Group::class);

    }

    //好友小群 只有两人
    public function friendGroups()
    {
        return $this->belongsToMany(Group::class,'group_users','user_id','group_id')->where('type',0);
    }
   
}
