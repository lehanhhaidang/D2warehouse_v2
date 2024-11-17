<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'receive_date',
        'status',
        'note',
        'created_by',
        'warehouse_id',
        'propose_id'

    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(MaterialReceiptDetail::class);
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
