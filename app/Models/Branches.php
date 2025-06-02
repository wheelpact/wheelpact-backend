<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\vehicles;


class Branches extends Model {
    use HasFactory;

    protected $table = 'branches'; // Ensure this matches your DB table name

    protected $fillable = [
        'dealer_id',
        'name',
        'branch_banner1',
        'branch_banner2',
        'branch_banner3',
        'branch_thumbnail',
        'branch_logo',
        'branch_type',
        'branch_supported_vehicle_type',
        'branch_services',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'contact_number',
        'whatsapp_no',
        'email',
        'short_description',
        'branch_map',
        'map_latitude',
        'map_longitude',
        'map_city',
        'map_district',
        'map_state',
        'is_active',
        'is_admin_approved',
        'admin_approved_dt',
        'created_at',
        'updated_at',
        'deleted_at'
    ]; // Add relevant fields


    public function dealer() {
        return $this->belongsTo(User::class, 'dealer_id', 'id');
    }

    public function vehicles() {
        return $this->hasMany(Vehicles::class, 'branch_id', 'id');
    }

    public function branches() {
        return $this->belongsTo(Branches::class, 'branch_id', 'id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state() {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
