<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attraction  extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function species()
    {
        return $this->belongsToMany(Species::class);
    }

    public function averageRating()
    {
        return $this->comments()->avg('rating');
    }
}