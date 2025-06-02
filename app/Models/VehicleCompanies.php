<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCompanies extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleCompaniesFactory> */
    use HasFactory;
    protected $table = 'vehiclecompanies';
 
    protected $fillable = [
        'cmp_name',
        'cmp_logo',
        'cmp_cat',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    
}
