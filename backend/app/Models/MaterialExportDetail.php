<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialExportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_export_id',
        'material_id',
        'shelf_id',
        'unit',
        'quantity',
    ];

    public $timestamps = false;

    public function materialExport()
    {
        return $this->belongsTo(MaterialExport::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }
}
