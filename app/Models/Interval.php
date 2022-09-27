<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Interval
 * @package App\Models
 *
 * @property \App\Models\Event $event
 * @property string $name
 * @property integer $event_id
 * @property string $start_time
 * @property string $end_time
 */
class Interval extends Model
{
    use SoftDeletes;

    use HasFactory;


    public $fillable = [
        'name',
        'event_id',
        'start_time',
        'end_time'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
