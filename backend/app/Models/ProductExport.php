<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'export_date',
        'warehouse_id',
        'status',
        'note',
        'created_by',
        'propose_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(ProductExportDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function propose()
    {
        return $this->belongsTo(Propose::class);
    }
}
