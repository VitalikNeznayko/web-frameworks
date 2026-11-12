<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{

    use Filterable;

    public const FILTER_FIELDS = [
        "id"              => "EQUAL",
        "first_name"      => "LIKE",
        "last_name"       => "LIKE",
        "email"           => "LIKE",
        "phone"           => "LIKE",
        "passport_number" => "LIKE",
        "country"         => "LIKE"
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "passport_number",
        "country"
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    /**
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

}
