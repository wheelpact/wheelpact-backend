<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model {
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'shortname',
        'name',
        'phonecode',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
