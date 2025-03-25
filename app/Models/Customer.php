<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model {
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'contact_no', 'whatsapp_no', 'profile_image', 'address', 'country_id', 'state_id', 'city_id', 'zipcode', 'otp', 'otp_status', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
}
