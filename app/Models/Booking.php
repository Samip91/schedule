<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Booking
 * @package App\Models
 * @version September 27, 2022, 6:26 am UTC
 *
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $booking_date
 * @property integer $event_id
 * @property time $start_time
 * @property time $end_time
 */
class Booking extends Model
{
    use SoftDeletes;

    use HasFactory;




    public $fillable = [
        'email',
        'first_name',
        'last_name',
        'booking_date',
        'event_id',
        'start_time',
        'end_time'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'booking_date' => 'date',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
