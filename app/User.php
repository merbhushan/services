<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Http\Controllers\userAccessManager;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $arrStatus = ['Active'];

    public $objUserAccessManager;

    public function __construct(){
        $this->objUserAccessManager = new userAccessManager();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'status'
    ];

    public function role()
    {
        return $this->belongsTo('App\role');
    }

    /**
     * This function will set a list of front end urls for particular user.
     */
    public function frontEndUrls(){
        if($this->id){
            return $this->objUserAccessManager->frontEndUrls($this->role);
        }
        // return 0 if user id is not set
        return 0;
    }
}
