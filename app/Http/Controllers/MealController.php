<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MealController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){ // Meals that is closed by displaying on the welcome page
        try{
            $today = Carbon::now()->format('Y-m-d');

            $query = Meal::with('organizer');              
            $subquery = DB::table('reservations as r')->selectRaw('count(r.id) as total_resavations')->whereRaw('r.meal_id = meals.id')->toSql();
            if($request->latitude && $request->longitude) {
                if(!empty($request->latitude) && !empty($request->longitude))  
                    $query->distance($request->latitude, $request->longitude)->where(function($q) use($today){
                        $q->where('start_date','<=', $today)->where('end_date','>=',$today);
                    })->whereRaw("($subquery) < maximum_capacity")->orderBy('distance', 'ASC');
            };            
            return $this->apiResponse(false, $query->get(), Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, $e->errors(), Response::HTTP_BAD_REQUEST);
        }catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function mealsByOwnerType(Request $request){  // Meals based on the owner that logs in
        try {
            $user = Auth::user('api:organizer');

            $meals = Meal::with('organizer')->where('organizer_id', $user->id)
            ->paginate(50);
            return $this->apiResponse(false, $meals, Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, $e->errors(), Response::HTTP_BAD_REQUEST);
        }catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{            
            $user = Auth::user('api:organizer');
            $meal = Meal::where('organizer_id', $user->id)
            ->where('start_date', $request->get('start_date'))
            ->where('end_date', $request->get('end_date'))
            ->where('address', $request->get('address'))
            ->first();
            if ($meal) {
                return $this->apiResponse(true, 'Iftar Meal already exists.', Response::HTTP_CONFLICT);
            }

            $data = $request->all();
            $data['organizer_id'] = $user->id;
            $meal = Meal::create($data);
            return $this->apiResponse(false,  $meal, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->apiResponse(true, $e->errors(), Response::HTTP_BAD_REQUEST);
        }catch (\Exception $e) {
            return $this->apiResponse(true, $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $meal = Meal::with('organizer')->where('id', $id)->first();
        return $this->apiResponse(false, $meal, Response::HTTP_OK);
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
            return $this->apiResponse(false, $meal, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(true,"Could not find meal", Response::HTTP_NOT_FOUND);
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
