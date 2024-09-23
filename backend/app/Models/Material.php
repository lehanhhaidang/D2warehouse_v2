<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'quantity',
        'material_img',
        'status',
    ];

    public function receipts()
    {
        return $this->hasMany(MaterialReceipt::class);
    }

    public function exports()
    {
        return $this->hasMany(MaterialExport::class);
    }
}
