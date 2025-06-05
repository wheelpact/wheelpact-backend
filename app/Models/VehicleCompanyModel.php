<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicles as Vehicle;

class VehicleCompanyModel extends Model {
    /** @use HasFactory<\Database\Factories\VehicleCompanyModelFactory> */
    use HasFactory;

    protected $table = 'vehiclecompaniesmodels';

    protected $fillable = [
        'cmp_id',
        'model_name',
        'cmp_cat',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * A model belongs to a company.
     */
    public function company() {
        return $this->belongsTo(VehicleCompanies::class, 'cmp_id', 'id');
    }

    /**
     * A vehicle has models.
     */
    public function vehicles() {
        return $this->hasMany(Vehicles::class, 'model_id', 'id');
    }

    /**
     * A model has many variants.
     */
    public function variants() {
        return $this->hasMany(VehicleCompaniesModelVariants::class, 'model_id', 'id');
    }
}
