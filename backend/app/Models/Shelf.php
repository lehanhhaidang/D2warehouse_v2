<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'warehouse_id',
        'number_of_levels',
        'storage_capacity',
        'category_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('category_id', $this->category_id);
    }

    public function materials()
    {
        return $this->hasMany(Material::class)->where('category_id', $this->category_id);
    }


    public function cateogy()
    {
        return $this->belongsTo(Category::class);
    }
}
