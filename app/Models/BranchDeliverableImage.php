<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchDeliverableImage extends Model {
    use SoftDeletes, HasFactory;

    protected $table = 'branch_deliverable_images';

    protected $fillable = [
        'branch_id',
        'img_name',
        'type',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
