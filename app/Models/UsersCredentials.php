<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersCredentials extends Model {

    protected $table = 'userscredentials';

    protected $fillable = [
        'user_id',
        'email',
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
