<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bed extends Model
{

    use Filterable;

    public const FILTER_FIELDS = [
        "id"        => "EQUAL",
        "room_id"   => "EQUAL",
        "label"     => "LIKE",
        "bed_type"  => "LIKE",
        "is_active" => "BOOL"
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        "room_id",
        "label",
        "bed_type",
        "is_active"
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        "room_id"   => "integer",
        "is_active" => "boolean"
    ];

    /**
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

}
