<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicleBodyTypes extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleBodyTypesFactory> */
    use HasFactory;

    protected $table = 'vehiclebodytypes';

    protected $fillable = [
        'title',
        'vehicle_type',
        'is_active',
        'img_body_type'
    ];
}
