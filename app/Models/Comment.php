<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['attraction_id', 'rating', 'comment'];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }
}