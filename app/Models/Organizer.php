<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Organizer extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'name', 'phone_number', 'address', 'password'
    ];

    protected $casts = [
        'created_at' => 'datetime:d F, Y',
        'updated_at' => 'datetime:d F, Y',
    ];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
