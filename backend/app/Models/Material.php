<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'unit',
        'quantity',
        'category_id',
        'material_img',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function receipts()
    {
        return $this->hasMany(MaterialReceipt::class);
    }

    public function exports()
    {
        return $this->hasMany(MaterialExport::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function shelfDetails()
    {
        return $this->hasMany(ShelfDetail::class);
    }
}
