<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_receipt_id',
        'product_id',
        'shelf_id',
        'unit',
        'quantity',
    ];

    public $timestamps = false;

    public function productReceipt()
    {
        return $this->belongsTo(ProductReceipt::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }
}
