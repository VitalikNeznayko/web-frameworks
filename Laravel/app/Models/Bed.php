<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bed extends Model
{
    use Filterable;

    public const TYPES = [
        'single' => 'Одномісне',
        'bunk_top' => 'Двоярусне (верх)',
        'bunk_bottom' => 'Двоярусне (низ)',
    ];

    protected $fillable = [
        'room_id',
        'label',
        'bed_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->bed_type] ?? $this->bed_type;
    }
}
