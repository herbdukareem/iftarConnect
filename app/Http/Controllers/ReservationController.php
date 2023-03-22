<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Meal;
use App\Models\Reservation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try{                    
            $reservations = Reservation::with('meal')->where(['reservation_date'=>date('Y-m-d'),"meal_id"=> $request->meal_id])->get()->pluck('beneficiary_phone_number');      
            return $this->apiResponse(false, $reservations, Response::HTTP_OK);
        }catch(\Exception $e){
            return $this->apiResponse(true, $e->getMessage(), 400);
        }
    }

    public function beneficiaryReservations(Request $request){
        try{        
            $user = Auth::user('api:beneficiary');
            $reservations = Reservation::with('meal.organizer')->where(['beneficiary_id'=> $user->id, 'reservation_date'=>date('Y-m-d')])->get()->pluck('meal');      
            return $this->apiResponse(false, $reservations, Response::HTTP_OK);
        }catch(\Exception $e){
            return $this->apiResponse(true, $e->getMessage(), $e->getCode());
        }
    }


  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try{        
            $user = Auth::user('api:beneficiary');
            
            $reservation = Reservation::where(['meal_id'=>$request->get('meal_id'), 'reservation_date'=> date('Y-m-d')])->get();
            
            if ($reservation->where('beneficiary_id' , $user->id)->count()>0) {
                return $this->apiResponse(true, ["data"=>$reservation, "message"=>'Iftar Meal already reserved.'], Response::HTTP_CONFLICT );
            }
            
            $meal = Meal::where('id',$request->get('meal_id'))->first();
            if (empty($meal)) {
                throw new \Exception('Meal not found',Response::HTTP_NOT_FOUND );                
            }
            
            if( $reservation->count() < $meal->maximum_capacity){
                $newreservation = Reservation::insert([
                    'meal_id' => $request->get('meal_id'),
                    'beneficiary_id' => $user->id,
                    'reservation_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d'),
                ]);
                return $this->apiResponse(false,  $newreservation, Response::HTTP_CREATED);
            }
            throw new \Exception("Sorry, all slots taken.", Response::HTTP_TOO_MANY_REQUESTS);                       
        }catch(\Exception $e){
            return $this->apiResponse(true, $e->getMessage(), $e->getCode());
        }
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
