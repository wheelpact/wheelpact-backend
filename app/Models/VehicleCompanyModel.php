<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCompanyModel extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleCompanyModelFactory> */
    use HasFactory;

    protected $table = 'vehiclecompaniesmodels';

    protected $fillable = [
        'cmp_id',
        'model_name',
        'cmp_cat',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
