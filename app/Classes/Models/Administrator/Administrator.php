<?php
namespace App\Classes\Models\Administrator;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use App\Notifications\AdministratorResetPasswordNotification;

class Administrator extends Authenticatable
{
    use Notifiable;
	protected $table = 'sbc_users'; 
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['first_name','last_name','email','password','remember_token','profile_picture','status','parent_id'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	/**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
	//Send password reset notification
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new AdministratorResetPasswordNotification($token));
	}
	
	public function checkPermission($user,$permission)
	{
		$checkPermission=false;
		if(count((array)$user)==0){
			return $checkPermission;	
		}
		$userToSecurityModuleObj=new \App\Classes\Models\User\UserToSecurityModule();
		$checkPermission=$userToSecurityModuleObj->checkPermission($user->user_id,$permission);
		return $checkPermission;	
	}
}
