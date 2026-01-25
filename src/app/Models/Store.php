<?php

namespace App\Models;
use App\Models\StoreHour;
use App\Models\PaymentMethod;
use App\Models\StoreImage;
use App\Models\Review;
use App\Models\MenuPhoto;
use App\Models\RecommendedItem;
use App\Models\FavoriteFolder;
use App\Models\StoreSocialLink;
use Illuminate\Support\Facades\Storage;
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
        'slide_updated_at',
        'gallery_updated_at',
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
        'slide_updated_at' => 'datetime',
        'gallery_updated_at' => 'datetime',
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
        return $this->hasMany(RecommendedItem::class)
            ->orderBy('sort_order');    
    }

    public function favoriteFolders() {
        return $this->belongsToMany(
            FavoriteFolder::class, 
            'favorite_folders_store', 
            'store_id', 
            'favorite_folder_id'
        )->withTimestamps();
    }


    public function favoriteByUsers() {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function socialLinks() {
        return $this->hasMany(StoreSocialLink::class);
    }

    public function images() {
        return $this->hasMany(StoreImage::class);
    }

    public function latestImage() {
        return $this->hasOne(StoreImage::class)->latestOfMany();
    }

    public function viewHistories()
    {
        return $this->hasMany(ViewHistory::class);
    }


    public function getCardImageUrlAttribute(): string 
    {
        $default = Storage::url('store/card.png');
        
        $img = $this->relationLoaded('slideImages')
            ? $this->slideImages->firstWhere('is_used_on_card', true) ?? $this->slideImages->first()
            : $this->slideImages()->where('is_used_on_card', true)->first()
                ?? $this->slideImages()->orderBy('sort_order')->first();


        if (!$img || !$img->path) return $default;

        $value = trim((string) $img->path);
        if ($value === '') return $default;

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) return $value;
        if (str_starts_with($value, '/storage/')) return $value;

        $path = preg_replace('#^/?storage/#', '', ltrim($value, '/'));
        return Storage::url($path);

    }

}
