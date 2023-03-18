<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'phone_number', 'address'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
