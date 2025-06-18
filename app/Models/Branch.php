<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model {
    use SoftDeletes, HasFactory;

    protected $table = 'branches';

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
        'admin_approval_dt',
    ];

    public $timestamps = true;

    protected $casts = [
        'admin_approval_dt' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Relationships */

    public function dealer() {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    public function deliverables() {
        return $this->hasMany(BranchDeliverableImage::class, 'branch_id');
    }

    public function ratings() {
        return $this->hasMany(BranchRating::class, 'branch_id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }
}
