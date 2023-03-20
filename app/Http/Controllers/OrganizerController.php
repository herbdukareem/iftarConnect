<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Meal;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreOrganizerRequest;

class OrganizerController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $query = Organizer::with('meals');
        if($request->has('organizer')) {
            $query->where('id', $request->organizer)->orWhere('phone_number', $request->organizer);
            $result = $query->first();
        }else{
           $result = $query->get();
        }
        return $this->apiResponse(false, $result, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $organizer = Organizer::where('phone_number', $request->phone_number)->first();
        if ($organizer) {
            return $this->apiResponse(true, 'Organizer already exists.', Response::HTTP_CONFLICT);
        }
        $organizer = Organizer::create($request->all());
        return $this->apiResponse(false, $organizer, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $organizer = Organizer::with('meals')->where('id', $id)->orWhere('phone_number', $id)->firstOrFail();
            return $this->apiResponse(false,  $organizer, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(true, "Could not find organizer", Response::HTTP_NOT_FOUND);
        }

       
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
            $organizer = Organizer::findOrFail($id);
            $organizer->update($request->all());
            return $this->apiResponse(false, $organizer, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(true, "Could not find organizer", Response::HTTP_NOT_FOUND);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        Organizer::destroy($id);
        return $this->apiResponse(false, 'Deleted', Response::HTTP_OK);
    }
}
