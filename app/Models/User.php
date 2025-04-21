<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Complaint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name','email','password'];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function hasComplaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function comments()
    {
    return $this->hasMany(Comment::class);
    }
}
