<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * @property int $id
 * @property int $day
 * @property int $slot
 * @property int $rest
 * @property string $from
 * @property string $to
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 */
class Slot extends Model
{
    protected $fillable = [
        'day',
        'slot',
        'rest',
        'from',
        'to',
    ];

    protected $casts = [
        'day' => 'int',
        'slot' => 'int',
        'rest' => 'int',
    ];
}
