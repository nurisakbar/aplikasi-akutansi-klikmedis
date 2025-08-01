<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'accountancy_company_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the company that owns the user.
     */
    public function accountancyCompany()
    {
        return $this->belongsTo(AccountancyCompany::class, 'accountancy_company_id');
    }

    /**
     * Get the user's description for AdminLTE.
     */
    public function adminlte_desc()
    {
        return $this->hasRole('superadmin') ? 'Super Administrator' : 'Company Administrator';
    }

    /**
     * Get the user's image for AdminLTE.
     */
    public function adminlte_image()
    {
        return null;
    }

    /**
     * Get the user's profile URL for AdminLTE.
     */
    public function adminlte_profile_url()
    {
        return route('profile.edit');
    }
}
