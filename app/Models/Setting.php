<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App\Models
 *
 * @property \App\Models\Event $event
 * @property integer $event_id
 * @property string $key
 * @property string $value
 */
class Setting extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $fillable = [
        'event_id',
        'key',
        'value'
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
