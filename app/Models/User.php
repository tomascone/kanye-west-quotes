<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LaravelPermissionToVueJS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['roles'];

    /**
     * Get all of the quotes for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(FavouriteQuote::class);
    }

    public function getAllPermissionsAttribute() {
        $permissions = [];
            foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }

        return $permissions;
    }

    /**
     * Check if the user is superadmin.
     *
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('permission.super_admin_name'));
    }
}
