<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShelfDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelf_id',
        'product_id',
        'unit',
        'quantity',
    ];



    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public $timestamps = false;
}
