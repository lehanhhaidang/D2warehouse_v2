<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'propose_id',
        'product_id',
        'quantity',
        'status',
    ];

    protected $timestamps = false;

    public function propose()
    {
        return $this->belongsTo(Propose::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
