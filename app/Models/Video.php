<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'title', 'description', 'url'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category');
    }
}
