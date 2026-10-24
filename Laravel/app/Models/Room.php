<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    public const TYPES = [
        'dorm' => 'Загальна',
        'private' => 'Приватна',
    ];

    protected $fillable = [
        'number',
        'type',
        'floor',
        'price_per_night',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'floor' => 'integer',
            'price_per_night' => 'decimal:2',
        ];
    }

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
