<?php
namespace App\Classes\Models;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
	protected $table = 'sbc_configuration';
    protected $fillable = ['key','value','label','user_id'];

}
