<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'password',
        'email'
    ];
    public function roles()
    {
        return $this->hasOne(UserRole::class, 'user_id');
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'user_favorites', 'user_id', 'book_id');
    }
}
