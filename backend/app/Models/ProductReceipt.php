<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'receive_date',
        'status',
        'warehouse_id',
        'note',
        'created_by',
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
        return $this->hasMany(ProductReceiptDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
