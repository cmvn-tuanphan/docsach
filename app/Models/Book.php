<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'book_id';
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'password',
        'email'
    ];
    use HasFactory;


    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites','book_id','user_id');
    }

    public function isFavoritedByUser($user)
    {
        return $this->favoritedByUsers->contains($user);
    }

}
