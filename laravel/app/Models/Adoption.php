<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'reserved', 'completed', 'cancelled'];

    public const LABELS = [
        'pending' => 'Awaiting approval',
        'reserved' => 'Reserved',
        'completed' => 'Adopted',
        'cancelled' => 'Cancelled',
    ];

    // Statuses of a request that is still open.
    public const ACTIVE_STATUSES = ['pending', 'reserved'];

    // Statuses that make a pet unavailable.
    public const BLOCKING_STATUSES = ['pending', 'reserved', 'completed'];

    public const RESERVATION_HOURS = 48;

    protected $fillable = [
        'pet_id',
        'customer_id',
        'adoption_date',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'pet_id' => 'integer',
        'customer_id' => 'integer',
        'adoption_date' => 'date',
        'expires_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => self::LABELS[$this->status] ?? ucfirst((string) $this->status));
    }

    // Cancels reservations whose 48 h hold has run out. Returns the number of cancelled rows.
    public static function expireReservations(): int
    {
        return static::where('status', 'reserved')
            ->where('expires_at', '<', now())
            ->update(['status' => 'cancelled']);
    }
}
