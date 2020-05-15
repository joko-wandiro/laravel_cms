<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Storage;

/**
* Admins Model
*/
class Admins extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';
}
//$password= "J4V4source";
//$options = [
//    'salt' => config('app.key'),
//];
//// Checking Model
//$model       = new \App\Models\Admins;
//$model->email= "joko_wandiro@yahoo.com";
//$model->password= password_hash($password, PASSWORD_BCRYPT, $options);
//$model->save();
//$model         = \App\Models\Admins::get()->first()->toArray();
//dd($model);