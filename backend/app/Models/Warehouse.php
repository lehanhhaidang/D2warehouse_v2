<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'acreage',
        'number_of_shelves',
        'category_id',
    ];

    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }

    public function materialReceipts()
    {
        return $this->hasMany(MaterialReceipt::class);
    }

    public function productReceipts()
    {
        return $this->hasMany(ProductReceipt::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
