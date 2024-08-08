<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'period', 'description'];

    public function attractions()
    {
        return $this->hasMany(Attraction::class, 'species_id');
    }
}
