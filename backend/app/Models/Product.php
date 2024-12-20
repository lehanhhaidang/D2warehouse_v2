<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'color_id',
        'unit',
        'quantity',
        'product_img',
        'status',
    ];

    public function receipts()
    {
        return $this->hasMany(ProductReceipt::class);
    }

    public function exports()
    {
        return $this->hasMany(ProductExport::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function shelfDetails()
    {
        return $this->hasMany(ShelfDetail::class);
    }
}
