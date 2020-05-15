<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Storage;

/**
 * Settings Model
 */
class Settings extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'value',
    );

    public static function gets()
    {
        $Model = new self;
        $records = $Model->get();
        return $records;
    }
    
    public static function get_parameters()
    {
        $Model = new self;
        $records = $Model->get();
        $settings = array();
        foreach ($records as $record) {
            $name = $record['name'];
            $settings[$name] = $record['value'];
        }
        return $settings;
    }

}
