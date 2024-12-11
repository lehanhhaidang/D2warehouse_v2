<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingPlanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturing_plan_id',
        'product_id',
        'product_quantity',
        'material_id',
        'material_quantity',
    ];

    public $timestamps = false;

    public function manufacturingPlan()
    {
        return $this->belongsTo(ManufacturingPlan::class);
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
