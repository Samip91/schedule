<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timeslot
 * @package App\Models
 *
 * @property \App\Models\Event $event
 * @property integer $event_id
 * @property integer $day
 * @property integer $opening_time
 * @property integer $closing_time
 * @property integer $slot_time
 * @property integer $buffer_time
 */
class Timeslot extends Model
{
    use SoftDeletes;

    use HasFactory;


    public $fillable = [
        'event_id',
        'day',
        'opening_time',
        'closing_time',
        'slot_time',
        'buffer_time'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
