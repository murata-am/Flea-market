<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'description',
        'condition',
        'image',
        'brand_name'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function likes()
    {
        return $this->hasMany(LikeButton::class, 'item_id');
    }

    public function isLikedBy($user)
    {
        if (!$user)
            return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function Seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sold_item()
    {
        return $this->hasOne(SoldItem::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getLikeCountAttribute()
    {
        return $this->likes()->count();
    }

}