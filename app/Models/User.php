<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'status',
        'banned',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }
    /**
     * Check if the user has view permission.
     *
     * @param int $permissionId
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function isViewPermission($permissionId)
    {
        if ($this->hasPermission($permissionId, 2)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the user has edit permission.
     *
     * @param int $permissionId
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function isEditPermission($permissionId)
    {
        if ($this->hasPermission($permissionId, 4)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the user has delete permission.
     *
     * @param int $permissionId
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function isDeletePermission($permissionId)
    {
        if ($this->hasPermission($permissionId, 8)) {
            return true;
        }
        return false;
    }

    /**
     * Generic function to check permission based on the permission value.
     *
     * @param int $permissionId
     * @param int $permissionValue
     * @return bool
     */
    protected function hasPermission($permissionId, $permissionValue)
    {
        // Retrieve permissions from the user_permissions table
        $userPermissions = $this->getPermissions();

        // Check if the user has the permission_id and the appropriate permission_value using bitwise AND
        if (isset($userPermissions[$permissionId])) {
            return ($userPermissions[$permissionId]->permission_value & $permissionValue) !== 0;
        }

        return false;
    }

    public function getPermissions()
    {
        // Retrieve permissions directly from the user_permissions table
        return \App\Models\UserPermission::where('user_id', $this->id)
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->get()
            ->keyBy('permission_code');
    }

    public function registerMediaCollections(): void
    {
        // Define any media collections (if needed)
        $this->addMediaCollection('avatars')->singleFile();
    }
}
