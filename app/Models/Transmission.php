<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transmission extends Model {
    /** @use HasFactory<\Database\Factories\StateFactory> */
    use HasFactory;

    protected $table = 'transmissions';

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
