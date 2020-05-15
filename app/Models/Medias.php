<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Storage;

class Medias extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medias';

    public static function gets()
    {
        $Model = new self;
        $records = $Model->orderBy('created_at', 'DESC')->get();
        $result = array();
        foreach ($records as $record) {
            $id = $record['id'];
            $result[$id] = $record;
        }
        return $result;
    }

}
