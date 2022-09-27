<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Leave
 * @package App\Models
 *
 * @property integer $event_id
 * @property string $date
 */
class Leave extends Model
{
    use SoftDeletes;

    use HasFactory;


    public $fillable = [
        'event_id',
        'date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
