<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchRating extends Model {
    use SoftDeletes;

    protected $table = 'branch_ratings';

    protected $fillable = [
        'branch_id',
        'customer_id',
        'rating',
        'message',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
