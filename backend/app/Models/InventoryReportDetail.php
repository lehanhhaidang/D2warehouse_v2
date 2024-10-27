<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'material_id',
        'inventory_report_id',
        'shelf_id',
        'actual_quantity',
        'note',
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->belongsTo(Material::class);
    }

    public function inventoryReport()
    {
        return $this->belongsTo(InventoryReport::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }
}
