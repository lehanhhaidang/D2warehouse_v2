<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_export_id',
        'product_id',
        'shelf_id',
        'unit',
        'quantity',
    ];

    public $timestamps = false;

    public function productExport()
    {
        return $this->belongsTo(ProductExport::class);
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
