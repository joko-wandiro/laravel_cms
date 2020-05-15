<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Storage;

class Pages extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';
    
    /**
     * Get values for input select
     * 
     * @return array
     */
    public static function get_select()
    {
        $Model = new self;
        $list = array("" => trans('main.select.category'));
        $result = $Model->get()->pluck("title", "id")->all();
        $list = $list + $result;
        return $list;
    }
}
