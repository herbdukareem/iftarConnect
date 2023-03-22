<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Beneficiary extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'name', 'phone_number', 'email', 'address'
    ];

    protected $casts = [
        'created_at' => 'datetime:d F, Y',
        'updated_at' => 'datetime:d F, Y',
    ];

   

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
    public function getReservationIdsAttribute(){
        return Reservation::where(['beneficiary_id'=> $this->id, 'reservation_date', date('Y-m-d')])->pluck('id');
    }

    protected $appends = [
        'reservation_ids'
    ];
}
