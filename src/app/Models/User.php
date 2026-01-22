<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
namespace App\Models\ViewHistory;

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
        return $this->hasMany(ViewHistory::class);
    }
}
