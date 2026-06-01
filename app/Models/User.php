<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'address',
    'city',
    'state',
    'postal_code',
    'country',
    'bio',
    'avatar',
    'slug',
    'specialization',
    'artist_statement',
    'education',
    'exhibitions',
    'awards',
    'press',
    'location',
    'cover_image',
    'is_verified',
    'years_active',
    'first_name',
    'last_name',
    'role',
    'is_active',
    'status',
    'is_approved',
    'participate_in_orders',
    // Additional workflow fields
    'verification_status',
    'deactivated_at',
    'deactivation_reason',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'bio',
        'avatar',
        'slug',
        'specialization',
        'artist_statement',
        'education',
        'exhibitions',
        'awards',
        'press',
        'location',
        'cover_image',
        'is_verified',
        'years_active',
        'first_name',
        'last_name',
        'participate_in_orders',
        'website',
        'instagram',
        'facebook',
    ];

    protected $guarded = [
        'role',
        'is_active',
        'status',
        'is_approved',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Set name field as combination of first_name and last_name
            if (empty($user->name) && !empty($user->first_name) && !empty($user->last_name)) {
                $user->name = trim($user->first_name . ' ' . $user->last_name);
            }
            
            if (empty($user->slug)) {
                $user->slug = $user->generateSlug();
            }
        });

        static::updating(function ($user) {
            // Update name field when first_name or last_name changes
            if ($user->isDirty('first_name') || $user->isDirty('last_name')) {
                $user->name = trim($user->first_name . ' ' . $user->last_name);
                $user->slug = $user->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug from first_name and last_name.
     */
    public function generateSlug(): string
    {
        $slug = strtolower(trim($this->first_name . '-' . $this->last_name));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        
        $originalSlug = $slug;
        $counter = 1;
        
        while (self::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        
        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the full name attribute.
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
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
            'is_approved' => 'boolean',
            'is_verified' => 'boolean',
            'participate_in_orders' => 'boolean',
            'education' => 'array',
            'exhibitions' => 'array',
            'awards' => 'array',
            'press' => 'array',
            'preferences' => 'array',
        ];
    }

    /**
     * Get the artworks created by the user (if artist).
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class, 'artist_id');
    }

    /**
     * Get the certificates issued by the user (if artist).
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'artist_id');
    }

    /**
     * Get the ownerships of the user.
     */
    public function ownerships(): HasMany
    {
        return $this->hasMany(Ownership::class, 'owner_id');
    }

    /**
     * Get the current artworks owned by the user.
     */
    public function currentArtworks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Artwork::class,
            Ownership::class,
            'owner_id',
            'id',
            'id',
            'artwork_id'
        )->where('ownerships.is_current_owner', true);
    }

    /**
     * Get the transactions where the user is the buyer.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    /**
     * Get the transactions where the user is the seller.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    /**
     * Get the resale listings of the user.
     */
    public function resales(): HasMany
    {
        return $this->hasMany(Resale::class, 'owner_id');
    }

    /**
     * Get the users that this user follows.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    /**
     * Get the users that follow this user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    /**
     * Check if the user is following a specific user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow a user.
     */
    public function follow(User $user): void
    {
        if (!$this->isFollowing($user) && $this->id !== $user->id) {
            $this->following()->attach($user->id);
        }
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user): void
    {
        if ($this->isFollowing($user)) {
            $this->following()->detach($user->id);
        }
    }

    /**
     * Get the likes of the user.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the liked artworks of the user.
     */
    public function likedArtworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class, 'likes')
            ->withTimestamps();
    }

    /**
     * Get the carts of the user.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get all cart items for the user across all carts.
     */
    public function cartItems(): HasMany
    {
        return $this->hasManyThrough(CartItem::class, Cart::class);
    }

    /**
     * Get the wishlist items of the user.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an artist.
     */
    public function isArtist(): bool
    {
        return $this->role === 'artist';
    }

    /**
     * Check if the user is a collector.
     */
    public function isCollector(): bool
    {
        return $this->role === 'collector';
    }

    /**
     * Check if the user is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' || $this->isAdmin();
    }

    /**
     * Get the roles for the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get all permissions for the user through roles.
     */
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->where('is_active', true)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->where('is_active', true)->exists();
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->where('is_active', true)->count() === count($roleSlugs);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Super admins have all permissions
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug)->where('is_active', true);
            })
            ->exists();
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        // Super admins have all permissions
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($permissionSlugs) {
                $query->whereIn('slug', $permissionSlugs)->where('is_active', true);
            })
            ->exists();
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        // Super admins have all permissions
        if ($this->hasRole('super_admin')) {
            return true;
        }

        $userPermissions = $this->roles()
            ->where('is_active', true)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug')
            ->unique()
            ->toArray();

        return count(array_intersect($permissionSlugs, $userPermissions)) === count($permissionSlugs);
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(Role|string $role): self
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching($role);

        return $this;
    }

    /**
     * Assign multiple roles to the user.
     */
    public function assignRoles(array $roles): self
    {
        $roleIds = [];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $r = Role::where('slug', $role)->first();
                if ($r) {
                    $roleIds[] = $r->id;
                }
            } elseif ($role instanceof Role) {
                $roleIds[] = $role->id;
            }
        }

        $this->roles()->syncWithoutDetaching($roleIds);

        return $this;
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(Role|string $role): self
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->detach($role);

        return $this;
    }

    /**
     * Remove all roles from the user.
     */
    public function removeAllRoles(): self
    {
        $this->roles()->detach();

        return $this;
    }

    /**
     * Sync roles for the user.
     */
    public function syncRoles(array $roles): self
    {
        $roleIds = [];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $r = Role::where('slug', $role)->first();
                if ($r) {
                    $roleIds[] = $r->id;
                }
            } elseif ($role instanceof Role) {
                $roleIds[] = $role->id;
            }
        }

        $this->roles()->sync($roleIds);

        return $this;
    }

    /**
     * Get the avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return asset('images/placeholder-avatar.jpg');
        }
        
        // If avatar already starts with http, return as-is
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }
        
        return asset('storage/' . $this->avatar);
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if (!$this->cover_image) {
            return asset('images/placeholder-artist-cover.jpg');
        }
        
        // If cover_image already starts with http, return as-is
        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }
        
        return asset('storage/' . $this->cover_image);
    }

    /**
     * Get the profile image URL (alias for avatar).
     */
    public function getProfileImageUrlAttribute(): string
    {
        return $this->avatar_url;
    }

    /**
     * Get the social links as an array.
     */
    public function getSocialLinksAttribute(): array
    {
        return [
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'website' => $this->website,
        ];
    }
}
