<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Storage;

/**
 * App\Models\PostTags
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder
 * @mixin \Eloquent
 *  ^-----------------------
 */
class PostTags extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
    	'post_id',
    	'tag_id',
    );
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
