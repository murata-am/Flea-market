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
        return $this->belongsToMany(Category::class);
    }

    public function like_button()
    {
        return $this->hasOne(Like_button::class);
    }

    public function Seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sold_item()
    {
        return $this->hasOne(Sold_item::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

}

class Comments extends Model
{
    use HasFactory;
    protected $fillable = [
        'content', 'user_id', 'item_id'
    ];

    public function items()
    {
        return $this->belongsTo(item::class);
    }

    public function users()
    {
        return $this->belongsTo(user::class);
    }
}

class likeButtones extends Model
{
    use HasFactory;
    protected $fillable = [ 'user_id', 'item_id'];

    public function items()
    {
        return $this->belongsTo(item::class);
    }

    public function users()
    {
        return $this->belongsTo(user::class);
    }
}