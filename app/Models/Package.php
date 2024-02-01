<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $included
 * @property float $price
 * @property string $picture
 * @property int $ordering
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 */
class Package extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'included',
        'price',
        'picture',
        'ordering',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordering' => 'int',
        'price' => 'float',
    ];
}
