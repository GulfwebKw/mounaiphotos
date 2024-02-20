<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $included
 * @property float $price
 * @property string $picture
 * @property int $number_of_persons
 * @property int $ordering
 * @property bool $is_active
 * @property Collection<int, PackageOption> $options
 * @property Collection<int, PackageOption> $activeOptions
 * @property Collection<int, PackageOption> $reservations
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
        'number_of_persons',
        'ordering',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'number_of_persons' => 'int',
        'ordering' => 'int',
        'price' => 'float',
    ];

    public function options(){
        return $this->hasMany(PackageOption::class)->orderBy('ordering');
    }

    public function activeOptions(){
        return $this->hasMany(PackageOption::class)->orderBy('ordering')->where('is_active' , 1);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class)->withTrashed();
    }
}
