<?php

namespace App\Models;
use Malhal\Geographical\Geographical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model{
    use Geographical;
    use HasFactory;
    protected $fillable = [
        'organizer_id', 'meal_type', 'start_date', 'end_date', 'time_slot', 'maximum_capacity', 'description',
        'address', 'longitude', 'latitude', 'address_url', 'landmark', 'phone_number'
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function getTotalReservationsAttribute()
    {
        return Reservation::where(['meal_id' => $this->id ])->count();
    }
    
    public function getReserversAttribute(){
        return Reservation::where('meal_id', $this->id)->get()->pluck('beneficiary_phone_number');
    }


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    protected $appends = [
        'total_reservations',
        'reservers'
    ];
}
