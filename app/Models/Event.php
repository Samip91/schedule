<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package App\Models
 *
 * @property string $name
 */
class Event extends Model
{
    use SoftDeletes;

    use HasFactory;


    public $fillable = [
        'name'
    ];
}
