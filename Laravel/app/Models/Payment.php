<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{

    use Filterable;

    public const FILTER_FIELDS = [
        "id"         => "EQUAL",
        "booking_id" => "EQUAL",
        "amount"     => "BETWEEN",
        "method"     => "LIKE",
        "status"     => "LIKE",
        "paid_at"    => "BETWEEN"
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        "booking_id",
        "amount",
        "method",
        "status",
        "paid_at"
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
        "booking_id" => "integer",
        "amount"     => "decimal:2",
        "paid_at"    => "datetime:Y-m-d H:i:s"
    ];

    /**
     * @return BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

}
