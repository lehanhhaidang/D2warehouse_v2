<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',

    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
