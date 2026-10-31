<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'weight',
        'photo_path',
        'description',
    ];

    public $timestamps = false;

    public function adoptions()
    {
        return $this->hasMany(Adoption::class, 'pet_id');
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'pet_id');
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->whereDoesntHave('adoptions', function (Builder $query) {
            $query->whereIn('status', Adoption::BLOCKING_STATUSES);
        });
    }

    public function isAvailable(): bool
    {
        return ! $this->adoptions()->whereIn('status', Adoption::BLOCKING_STATUSES)->exists();
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => asset('storage/' . $this->photo_path));
    }
}
