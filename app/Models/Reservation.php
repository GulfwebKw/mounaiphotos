<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $uuid
 * @property int $package_id
 * @property Package $package
 * @property string $phone
 * @property Carbon $from
 * @property string $message
 * @property Carbon $to
 * @property float $price
 * @property int $number_of_persons
 * @property string $invoice_id
 * @property string $reference_number
 * @property Collection<int, ReserveOption> $options
 * @property bool $is_paid
 * @property Carbon $paid_at
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Reservation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uuid',
        'package_id',
        'name',
        'phone',
        'message',
        'from',
        'to',
        'price',
        'number_of_persons',
        'invoice_id',
        'reference_number',
        'is_paid',
        'paid_at',
    ];

    protected $casts = [
        'package_id' => 'int',
        'number_of_persons' => 'int',
        'from' => 'datetime',
        'to' => 'datetime',
        'price' => 'float',
        'is_paid' => 'bool',
        'paid_at' => 'datetime',
    ];

    public function package(){
        return $this->belongsTo(Package::class)->withTrashed();
    }

    public function options(){
        return $this->hasMany(ReserveOption::class);
    }
}
