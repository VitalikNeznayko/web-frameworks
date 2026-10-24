<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use Filterable;

    public const METHODS = [
        'cash' => 'Готівка',
        'card' => 'Картка',
        'transfer' => 'Переказ',
    ];

    public const STATUSES = [
        'pending' => 'Очікує',
        'paid' => 'Оплачено',
        'refunded' => 'Повернено',
    ];

    protected $fillable = [
        'booking_id',
        'amount',
        'method',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function methodLabel(): string
    {
        return self::METHODS[$this->method] ?? $this->method;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
