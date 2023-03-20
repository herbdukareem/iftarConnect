<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Meal;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreOrganizerRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

    public function login(Request $request){
        try {
            $credentials = $request->only('phone_number', 'password');
            $organizer = Organizer::where('phone_number', $credentials['phone_number'])->first();
            if(empty($organizer)){
                throw new \Exception('Invalid Phone number or password', Response::HTTP_NOT_FOUND);
            }

            if(!Hash::check($credentials['password'], $organizer->password)){
                throw new \Exception('Invalid Credentials', Response::HTTP_NOT_FOUND);
            }
            auth()->login($organizer);
            $accessToken = $organizer->createToken('iftarConnect')->accessToken;
            return $this->apiResponse(false, ['accessToken' => $accessToken, 'user'=> $organizer], Response::HTTP_OK);
           
        } catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), Response::HTTP_BAD_REQUEST);
          }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganizerRequest $request) {
        try {
            $validated = $request->validated();
            $validated->password =  Hash::make($validated->password);
            $organizer = Organizer::where($validated)->first();
            if (!empty($organizer)) {
                return $this->apiResponse(true, 'Organizer already exists.', Response::HTTP_CONFLICT);
            }
            $organizer = Organizer::create($validated);
            return $this->apiResponse(false, $organizer, Response::HTTP_CREATED);
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
