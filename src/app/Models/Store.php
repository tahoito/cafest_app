<?php

namespace App\Models;
use App\Models\StoreHour;
use App\Models\PaymentMethod;
use App\Models\StoreImage;
use App\Models\Review;
use App\Models\MenuPhoto;
use App\Models\RecommendedItems;
use App\Models\FavoriteFolder;
use App\Models\StoreSocialLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Store extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'description',
        'phone',
        'area',
        'mood',
        'budget_min',
        'budget_max',
        'tiktok_url',
        'instagram_url',
        'x_url',
        'website_url',
        'basic_updated_at',
        'description_updated_at',
        'contact_updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_public' => 'boolean',
        'basic_updated_at' => 'datetime',
        'description_updated_at' => 'datetime',
        'contact_updated_at' => 'datetime',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hours(){
        return $this->hasMany(StoreHour::class);
    }

    public function paymentMethods(){
        return $this->belongsToMany(PaymentMethod::class,'store_payment_methods');
    }

    public function slideImages(){
        return $this->hasMany(StoreImage::class)
            ->where('type','slide')
            ->orderBy('sort_order');
    }

    public function galleryImages(){
        return $this->hasMany(StoreImage::class)
            ->where('type','gallery')
            ->orderBy('sort_order');
    }

    public function menuPhotos(){
        return $this->hasMany(MenuPhoto::class)
            ->orderBy('sort_order');    
    }

    public function recommendedItems(){
        return $this->hasMany(RecommendedItems::class)
            ->orderBy('sort_order');    
    }

    public function favoriteFolders() {
        return $this->belongsToMany(
            FavoriteFolder::class, 'favorite_folders_store'
        )->withTimestamps();
    }


    public function favoriteByUsers() {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function socialLinks() {
        return $this->hasMany(StoreSocialLink::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'favorite_folders_store')
            ->withTimestamps();
    }


    public function images() {
        return $this->hasMany(StoreImage::class);
    }

    public function latestImage() {
        return $this->hasOne(StoreImage::class)->latestOfMany();
    }

}
