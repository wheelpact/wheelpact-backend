<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestDriveRequest extends Model {
    use HasFactory;

    protected $table = 'test_drive_request';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'branch_id',
        'vehicle_id',
        'dateOfVisit',
        'timeOfVisit',
        'comments',
        'license_file_path',
        'status',
        'is_active',
        'reason_selected',
        'dealer_comments',
        'update_by',
    ];

    public $timestamps = true;

    // Relationships
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'update_by');
    }
}
