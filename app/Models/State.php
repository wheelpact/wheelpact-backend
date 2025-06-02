<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model {
    /** @use HasFactory<\Database\Factories\StateFactory> */
    use HasFactory;

    protected $table = 'states';

    protected $fillable = [
        'short_code',
        'name',
        'country_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
