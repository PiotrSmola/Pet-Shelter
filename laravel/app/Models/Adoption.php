<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    use HasFactory;

    protected $table = 'adoptions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'pet_id',
        'customer_id',
        'adoption_date',
        'status',
    ];

    public $timestamps = false;

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
