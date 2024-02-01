<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $subTitle
 * @property string $picture
 * @property int $ordering
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Slider extends Model
{
    protected $fillable = [
        'title',
        'subTitle',
        'picture',
        'ordering',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordering' => 'int'
    ];
}
