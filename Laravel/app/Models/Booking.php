<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use Filterable;

    public const STATUSES = [
        'pending' => 'Очікує',
        'confirmed' => 'Підтверджено',
        'checked_in' => 'Заселено',
        'checked_out' => 'Виселено',
        'cancelled' => 'Скасовано',
    ];

    protected $fillable = [
        'guest_id',
        'bed_id',
        'check_in',
        'check_out',
        'status',
        'total_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
