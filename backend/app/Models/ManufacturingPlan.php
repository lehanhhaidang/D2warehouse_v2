<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManufacturingPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'status',
        'start_date',
        'end_date',
        'begin_manufacturing_by',
        'finish_manufacturing_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by',);
    }

    public function beginManufacturingBy()
    {
        return $this->belongsTo(User::class, 'begin_manufacturing_by');
    }

    public function finishManufacturingBy()
    {
        return $this->belongsTo(User::class, 'finish_manufacturing_by');
    }

    public function manufacturingPlanDetails()
    {
        return $this->hasMany(ManufacturingPlanDetail::class);
    }

    public function proposes()
    {
        return $this->hasMany(Propose::class);
    }
}
