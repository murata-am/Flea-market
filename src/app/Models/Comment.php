<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'user_id',
        'item_id'
    ];

    public function items()
    {
        return $this->belongsTo(item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}