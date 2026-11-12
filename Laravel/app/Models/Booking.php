<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        "guest_id",
        "bed_id",
        "check_in",
        "check_out",
        "status",
        "total_price"
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
        "guest_id"    => "integer",
        "bed_id"      => "integer",
        "check_in"    => "date:Y-m-d",
        "check_out"   => "date:Y-m-d",
        "total_price" => "decimal:2"
    ];

    /**
     * @return BelongsTo
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * @return BelongsTo
     */
    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

}
