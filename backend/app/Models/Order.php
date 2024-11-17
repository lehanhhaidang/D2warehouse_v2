<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'order_date',
        'delivery_date',
        'status',
        'note',
        'total_price',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
