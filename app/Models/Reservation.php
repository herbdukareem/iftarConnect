<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'meal_id', 'beneficiary_id', 'reservation_date'
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function getBeneficiaryPhoneNumberAttribute(){
        return Beneficiary::where('id', $this->beneficiary_id)->first()->phone_number;
    }
   

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    protected $appends = [
        'beneficiary_phone_number'
    ];
}
