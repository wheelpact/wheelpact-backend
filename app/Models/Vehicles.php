<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleCompanies as VehicleCompany;
use App\Models\VehicleType;
use App\Models\VehicleBodyTypes;
use App\Models\FuelTypes;
use App\Models\Branches;
use App\Models\Indiarto;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\VehicleCompanyModel;
use App\Models\Transmission;
use App\Models\VehicleImages;
use App\Models\VehicleCompaniesModelVariants;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicles extends Model {
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'vehicles';
    protected $primaryKey = 'id';

    protected $fillable = [
        'unique_id',
        'branch_id',
        'vehicle_type',
        'cmp_id',
        'model_id',
        'fuel_type',
        'body_type',
        'variant_id',
        'mileage',
        'kms_driven',
        'owner',
        'transmission_id',
        'color_id',
        'featured_status',
        'search_keywords',
        'onsale_status',
        'onsale_percentage',
        'manufacture_year',
        'registration_year',
        'registered_state_id',
        'rto',
        'insurance_type',
        'insurance_validity',
        'accidental_status',
        'flooded_status',
        'last_service_kms',
        'last_service_date',
        'car_no_of_airbags',
        'car_central_locking',
        'car_seat_upholstery',
        'car_sunroof',
        'car_integrated_music_system',
        'car_rear_ac',
        'car_outside_rear_view_mirrors',
        'car_power_windows',
        'car_engine_start_stop',
        'car_headlamps',
        'car_power_steering',
        'bike_headlight_type',
        'bike_odometer',
        'bike_drl',
        'bike_mobile_connectivity',
        'bike_gps_navigation',
        'bike_usb_charging_port',
        'bike_low_battery_indicator',
        'bike_under_seat_storage',
        'bike_speedometer',
        'bike_stand_alarm',
        'bike_low_fuel_indicator',
        'bike_low_oil_indicator',
        'bike_start_type',
        'bike_kill_switch',
        'bike_break_light',
        'bike_turn_signal_indicator',
        'regular_price',
        'selling_price',
        'pricing_type',
        'emi_option',
        'avg_interest_rate',
        'tenure_months',
        'reservation_amt',
        'thumbnail_url',
        'is_active',
        'is_admin_approved',
        'soldReason',
        'created_by',
        'created_datetime',
        'updated_by',
        'admin_approval_dt',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function branches() {
        return $this->belongsTo(Branches::class, 'branch_id', 'id');
    }

    /**
     * Get the vehicle company associated with the vehicle. defined in table vehiclecompanies
     */
    public function vehicleCompany() {
        return $this->belongsTo(VehicleCompany::class, 'cmp_id', 'id');
    }

    /**
     * Get the vehicle model associated with the vehicle. defined in table vehiclecompaniesmodels
     */
    public function model() {
        return $this->belongsTo(VehicleCompanyModel::class, 'model_id', 'id');
    }

    /**
     * Get the vehicle variant associated with the vehicle. defined in table vehiclecompaniesmodelvariants
     */
    public function variant() {
        return $this->belongsTo(VehicleCompaniesModelVariants::class, 'variant_id', 'id');
    }

    /**
     * Get the vehicle fuel type associated with the vehicle. defined in table fueltypes
     */
    public function fuelType() {
        return $this->belongsTo(FuelTypes::class, 'fuel_type', 'id');
    }

    // public function getVehicleTypeNameAttribute() {
    //     $types = config('constants.VEHICLE_TYPES');
    //     return $types[$this->vehicle_type] ?? 'Unknown';
    // }

    public function vehicleType() {
        return $this->belongsTo(VehicleType::class, 'vehicle_type', 'id');
    }

    public function bodyType() {
        return $this->belongsTo(VehicleBodyTypes::class, 'body_type', 'id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state() {
        return $this->belongsTo(State::class, 'registered_state_id', 'id');
    }

    
    // public function city() {
    //     return $this->belongsTo(City::class, 'city_id', 'id');
    // }

    public function images() {
        return $this->hasOne(VehicleImages::class, 'vehicle_id');
    }

    public function registeredState() {
        return $this->belongsTo(State::class, 'registered_state_id');
    }

    public function indiarto() {
        return $this->belongsTo(Indiarto::class, 'rto');
    }

    public function transmission() {
        return $this->belongsTo(Transmission::class, 'transmission_id');
    }


}
