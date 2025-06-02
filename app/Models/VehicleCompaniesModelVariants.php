<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCompaniesModelVariants extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleCompaniesModelVariantsFactory> */
    use HasFactory;

    protected $table = 'vehiclecompaniesmodelvariants';

    protected $fillable = [
        'model_id',
        'name',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
