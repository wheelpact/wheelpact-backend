<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'name',
        'state_id',
        'popularFlag',
        'latitude',
        'longitude',
        'img',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
