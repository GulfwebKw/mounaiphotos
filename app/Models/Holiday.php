<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $title
 * @property int $year
 * @property int $month
 * @property int $day
 * @property string $type
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 */
class Holiday extends Model
{
    protected $fillable = [
        'title',
        'year',
        'month',
        'day',
        'type',
    ];

    protected $casts = [
        'year' => 'int',
        'month' => 'int',
        'day' => 'int',
    ];

}
