<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $subject
 * @property string $message
 * @property Carbon $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function read():void
    {
        $this->read_at = now();
        $this->save();
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public static function numberUnreadMessage()
    {
        return self::query()->unread()->count();
    }
}
