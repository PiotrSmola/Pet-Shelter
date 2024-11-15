<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $table = 'vaccinations';

    protected $primaryKey = 'id';

    protected $fillable = [
        'pet_id',
        'vaccination_date',
        'vaccine_type',
        'batch_number',
    ];

    public $timestamps = false;

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
