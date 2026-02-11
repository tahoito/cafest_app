<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'favorite_areas',   
        'favorite_moods',   
        'icon_path',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'favorite_areas' => 'array',
            'favorite_moods' => 'array',
        ];
        
    }

    public function favoriteFolders()
    {
        return $this->hasMany(FavoriteFolder::class);
    }

    public function favorites() {
        return $this->belongsToMany(Store::class, 'user_favorites')->withTimestamps();
    }

    public function viewHistories() {
        return $this->hasMany(\App\Models\ViewHistory::class);
    }

    public function favoriteByUsers() {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function migrateStorageIconToPublic(): void
    {
        $path = (string) ($this->icon_path ?? '');
        if ($path === '') return;

        $normalized = ltrim($path, '/');

        if (Str::startsWith($normalized, 'images/users/')) {
            return;
        }

        if (!Str::startsWith($normalized, ['storage/user_icons/', 'user_icons/'])) {
            return;
        }

        $storageRel = preg_replace('#^storage/#', '', $normalized);
        $storageRel = ltrim($storageRel ?? '', '/');

        if ($storageRel === '' || !Storage::disk('public')->exists($storageRel)) {
            // 参照先が無いならデフォルトに戻す
            $this->icon_path = null;
            $this->save();
            return;
        }
    }
}
