<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'category_id', 'description', 'base_price', 'status'];

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }
}
