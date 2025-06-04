<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'surname',
        'first_name',
        'other_names',
        'email',
        'password',
        'role_id',
        'status',
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public function transferHistories()
    {
        return $this->hasMany(TransferHistory::class, 'user_id');
    }

    public function promotionHistories()
    {
        return $this->hasMany(PromotionHistory::class, 'user_id');
    }

    public function commendationAward()
    {
        return $this->hasMany(CommendationAward::class, 'user_id');
    }

    public function queriesMisconduct()
    {
        return $this->hasMany(QueriesMisconduct::class, 'user_id');
    }

//     public function hasFeaturePermission($featureName)
// {
//     return $this->permissions()
//         ->whereHas('platform_feature', function($query) use ($featureName) {
//             $query->where('feature', $featureName);
//         })
//         ->exists();
// }

public function hasFeaturePermission($featureId, $permission = null)
{
    $query = $this->role->permissions()->where('feature_id', $featureId);
    
    if ($permission === 'create') {
        $query->where('can_create');
    } elseif ($permission === 'edit') {
        $query->where('can_edit');
    } elseif ($permission === 'delete') {
        $query->where('can_delete');
    }
    
    return $query->exists();
}



// public function hasFeatureByName($featureName, $permission = null)
// {
//     $feature = \App\Models\PlatformFeature::where('feature', $featureName)->first();
    
//     if (!$feature) {
//         return false;
//     }
    
//     return $this->hasFeaturePermission($feature->id, $permission);
// }

// Show Employee Profile Photo on the Header
public function getPassportUrlAttribute()
{
    if ($this->employee && $this->employee->passport) {
        return asset('storage/' . $this->employee->passport);
    }
    return asset('backend/assets/images/avatars/avatar-1.jpg');
}

// Start User Trait
public function isActive()
{
    return $this->status === 'active';
}

public function isSuspended()
{
    return $this->status === 'suspended';
}

public function isBanned()
{
    return $this->status === 'banned';
}

public function canPerformAction($action = null)
{
    // Define actions that suspended users can still do
    $allowedForSuspended = ['view_profile', 'logout', 'change_password'];
    
    if ($this->isBanned()) {
        return false;
    }

    if ($this->isSuspended()) {
        return $action && in_array($action, $allowedForSuspended);
    }

    return $this->isActive();
}

public function scopeActive($query)
{
    return $query->where('status', 'active');
}

public function scopeSuspended($query)
{
    return $query->where('status', 'suspended');
}

public function scopeBanned($query)
{
    return $query->where('status', 'banned');
}
//End User Status Trait


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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
}
