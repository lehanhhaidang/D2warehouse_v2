<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_receipt_id',
        'material_id',
        'unit',
        'shelf_id',
        'quantity',
    ];

    public $timestamps = false;

    public function materialReceipt()
    {
        return $this->belongsTo(MaterialReceipt::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }
}
