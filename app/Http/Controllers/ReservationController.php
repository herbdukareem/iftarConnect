<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $query = Reservation::with('meal');
        if($request->has('meal')) {
            $query->with('beneficiary');
            $query->where('meal_id', $request->meal_id);
        };
        return $this->apiResponse(false, $query->get(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $meal = Reservation::where('meal_id', $request->meal_id)
        ->where('beneficiary_id', $request->beneficiary_id)
        ->where('reservation_date', date('Y-m-d'))
        ->first();
        if ($meal) {
            return $this->apiResponse(true, 'Iftar Meal already exists.', Response::HTTP_CONFLICT );
        }
        $meal = Reservation::create([
            'meal_id' => $request->meal_id,
            'beneficiary_id' => $request->beneficiary_id,
            'reservation_date' => date('Y-m-d'),
        ]);
        return $this->apiResponse(false,  $meal, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        try {
            $reservation = Reservation::findOrFail($id);
            ($reservation->reservation_date) ?  
                $reservation->reservation_date = null 
                : $reservation->reservation_date = date('Y-m-d');

            $msg = ($reservation->reservation_date) ?  'Reservation is Cancelled' 
                : 'Reservation is made';

            $reservation->save();
            return $this->apiResponse(false,  $msg, $reservation);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(true, "Could not find reservation", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
