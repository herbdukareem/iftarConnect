<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Organizer extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name', 'phone_number', 'address'
    ];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
