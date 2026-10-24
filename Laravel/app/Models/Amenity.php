<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use Filterable;

    protected $fillable = [
        'name',
        'description',
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenity');
    }
}
