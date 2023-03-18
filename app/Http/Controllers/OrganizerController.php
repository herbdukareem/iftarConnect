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
    public function index(){
        return $this->apiResponse('success', 'Organizers', Organizer::all());
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
            return $this->apiResponse('error', 'Organizer already exists.');
        }
        $organizer = Organizer::create($request->all());
        return $this->apiResponse('success', 'Organizer Profile created', $organizer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $organizer = Organizer::with('meals')->where('id', $id)->orWhere('phone_number', $id)->first();
        return $this->apiResponse('success', 'Organizer', $organizer);
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
            return $this->apiResponse('success', 'Updated', $organizer);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse('error', 'Not Updated', "Could not find organizer");
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
        return $this->apiResponse('success', 'Deleted');
    }
}
