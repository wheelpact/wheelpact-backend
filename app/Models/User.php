<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;

use App\Models\Branches;

/*

    * Authenticatable model for users in the application.
    * This model represents the users table and includes various attributes
    * such as name, email, contact numbers, addresses, and social links.
    * It uses Laravel's built-in authentication features and can be extended
    * to include additional functionality as needed.
    * The User model extends the Authenticatable class, which provides
    * the necessary methods for user authentication in Laravel.
    -   getAuthIdentifier() is used to retrieve the unique identifier for the user, 
    typically the user ID.
    
    -   getAuthPassword() is used to retrieve the user's password for authentication.
    
    -   getRememberToken() is used to retrieve the "remember me" token for the user.
    
    * The User model can be used to manage user data, authenticate users,
    * and handle user-related operations in the application.
    
*/

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'users';

    protected $fillable = [
        'name',
        'user_code',
        'email',
        'addr_residential',
        'addr_permanent',
        'date_of_birth',
        'gender',
        'profile_image',
        'country_id',
        'state_id',
        'city_id',
        'zipcode',
        'contact_no',
        'whatsapp_no',
        'social_fb_link',
        'social_twitter_link',
        'social_linkedin_link',
        'social_skype_link',
        'role_id',
        'otp',
        'otp_status',
        'is_active',
        'reset_token',
        'token_expiration',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vehicles() {
        return $this->hasManyThrough(
            Vehicles::class, // Target model
            Branches::class,  // Intermediate model
            'dealer_id',    // Foreign key on branches table
            'branch_id',    // Foreign key on vehicles table
            //'id',           // Local key on users table
            //'id'            // Local key on branches table
        );
    }
    // User.php (Dealer)
    public function branches() {
        return $this->hasMany(Branches::class, 'dealer_id');
    }
}
