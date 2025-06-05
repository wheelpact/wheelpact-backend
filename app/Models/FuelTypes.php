<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelTypes extends Model {
    /** @use HasFactory<\Database\Factories\FuelTypesFactory> */
    use HasFactory;

    protected $table = 'fueltypes';
    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
