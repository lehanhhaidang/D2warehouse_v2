<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propose extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'status',
        'order_id',
        'warehouse_id',
        'description',
        'created_by',
        'assigned_to',

    ];



    public function details()
    {
        return $this->hasMany(ProposeDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function asignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
