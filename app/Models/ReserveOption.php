<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $reservation_id
 * @property int $option_id
 * @property Reservation $reservation
 * @property PackageOption $option
 */
class ReserveOption extends Model
{
    protected $fillable = [
        'reservation_id' ,
        'option_id' ,
    ];
    public $timestamps = false;

    protected $casts = [
        'reservation_id' => 'int' ,
        'option_id' => 'int' ,
    ];

    public function option(){
        return $this->belongsTo(PackageOption::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class)->withTrashed();
    }
}
