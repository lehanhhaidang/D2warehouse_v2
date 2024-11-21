<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'warehouse_id',
        'created_by',
        'created_at',
        'description',
        'status',
    ];


    public function inventoryReportDetails()
    {
        return $this->hasMany(InventoryReportDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
