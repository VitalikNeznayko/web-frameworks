<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{

    use Filterable;

    public const FILTER_FIELDS = [
        "id"              => "EQUAL",
        "number"          => "LIKE",
        "type"            => "LIKE",
        "floor"           => "BETWEEN",
        "price_per_night" => "BETWEEN",
        "description"     => "LIKE"
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        "number",
        "type",
        "floor",
        "price_per_night",
        "description"
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
        "floor"           => "integer",
        "price_per_night" => "decimal:2"
    ];

    /**
     * @return HasMany
     */
    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }

}
