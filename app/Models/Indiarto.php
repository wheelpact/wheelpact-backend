<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indiarto extends Model {
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    protected $table = 'indiarto';
    protected $primaryKey = 'id';

    protected $fillable = [
        'state_id',
        'rto_state_code',
        'place'
    ];
}
