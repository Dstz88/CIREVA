<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the role associated with the user or resolve via relationship.
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mutator to handle backward compatibility for setting role by name or ID.
     */
    public function setRoleAttribute($value): void
    {
        if (is_numeric($value)) {
            $this->attributes['role_id'] = $value;
        } elseif (is_string($value)) {
            $roleName = ucfirst(strtolower($value));
            $role = Role::whereRaw('LOWER(name) = ?', [strtolower($value)])->first();
            if (!$role) {
                try {
                    $role = Role::firstOrCreate(['name' => $roleName]);
                } catch (\Throwable $e) {}
            }
            if ($role) {
                $this->attributes['role_id'] = $role->id;
            }
        }
    }

    /**
     * Accessor fallback when role attribute is requested directly.
     */
    public function getRoleAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }

        return $this->role()->getResults();
    }

    /**
     * Get normalized role name string (lowercase).
     */
    public function getRoleNameAttribute(): string
    {
        $role = $this->role;
        if (is_object($role)) {
            return strtolower((string) ($role->name ?? ''));
        }
        if (is_string($role)) {
            return strtolower($role);
        }
        return '';
    }

    /**
     * Check if user has specific role(s).
     */
    public function hasRole(string|array $roles): bool
    {
        $currentRole = $this->role_name;
        $rolesArray = array_map('strtolower', (array) $roles);
        return in_array($currentRole, $rolesArray, true);
    }

    /**
     * Get the organizer profile associated with the user.
     */
    public function organizerProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrganizerProfile::class);
    }

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
