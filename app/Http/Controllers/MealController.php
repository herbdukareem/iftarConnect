<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MealController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Meal::with('organizer');
        if($request->has('reservations')) $query->with('reservations');
        if($request->has('organizer')) {
            $query->where('organizer_id', $request->organizer);
        };

        if($request->has('latitude') && $request->has('longitude')) {
            if(!empty($request->latitude) && !empty($request->longitude))  
                $query->distance($request->latitude, $request->longitude)->orderBy('distance', 'ASC');
        };
        return $this->apiResponse('success', 'Collection of iftar', $query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $meal = Meal::where('organizer_id', $request->organizer_id)
        ->where('start_date', $request->start_date)
        ->where('end_date', $request->end_date)
        ->first();
        if ($meal) {
            return $this->apiResponse('error', 'Iftar Meal already exists.');
        }
        $meal = Meal::create($request->all());
        return $this->apiResponse('success', 'Iftar Meal created', $meal);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $meal = Meal::with('organizer')->where('id', $id)->first();
        return $this->apiResponse('success', 'Organizer', $meal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $meal = Meal::findOrFail($id);
            $meal->update($request->all());
            return $this->apiResponse('success', 'Updated', $meal);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse('error', 'Not updated', "Could not find meal");
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
