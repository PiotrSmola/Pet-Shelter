<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';

    protected $primaryKey = 'id';

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
}
