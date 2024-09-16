<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function receipts()
    {
        return $this->hasMany(ProductReceipt::class);
    }

    public function exports()
    {
        return $this->hasMany(ProductExport::class);
    }
}
