<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $package_id
 * @property Package $package
 * @property string $title
 * @property string $picture
 * @property bool $is_active
 * @property int $ordering
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PackageOption extends Model
{
    protected $fillable = [
        'title',
        'package_id',
        'picture',
        'ordering',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordering' => 'int',
        'package_id' => 'int',
    ];

    public function package(){
        return $this->belongsTo(Package::class);
    }
}
