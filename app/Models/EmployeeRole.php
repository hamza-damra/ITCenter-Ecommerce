<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmployeeRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the employees assigned to this role.
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'employee_role_id');
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? [], true);
    }

    /**
     * Check if the role has any permission within a group.
     */
    public function hasAnyPermissionInGroup(string $group): bool
    {
        $permissions = $this->permissions ?? [];

        foreach ($permissions as $perm) {
            if (str_starts_with($perm, $group . '.')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all permission keys defined in the config.
     */
    public static function getAllPermissionKeys(): array
    {
        $keys = [];
        $groups = config('permissions.groups', []);

        foreach ($groups as $group) {
            if (!empty($group['admin_only'])) {
                continue;
            }
            foreach ($group['permissions'] as $key => $label) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Generate a unique slug from the given name.
     */
    public static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = static::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
