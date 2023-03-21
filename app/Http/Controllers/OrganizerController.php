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
            $organizer = Organizer::with('meals')->where('phone_number', $credentials['phone_number'])->first();
            if(empty($organizer)){
                throw new \Exception('Invalid Phone number or password');
            }

            if(!Hash::check($credentials['password'], $organizer->password)){
                throw new \Exception('Invalid Credentials');
            }
            auth()->login($organizer);
            $accessToken = $organizer->createToken('iftarConnect')->accessToken;
            return $this->apiResponse(false, ['accessToken' => $accessToken, 'user'=> $organizer], Response::HTTP_OK);
           
        } catch (\Exception $e) {
            return $this->apiResponse(true, $e->getMessage(), Response::HTTP_BAD_REQUEST);
          }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|unique:organizers|max:14',
                'password' => 'required|string|max:255',
                'address' => 'required|string|max:255'
            ]);
            $data = $request->all();
            $data['password'] =  Hash::make($data['password']);
            $organizer = Organizer::where($data)->first();
            if (!empty($organizer)) {
                return $this->apiResponse(true, 'Organizer already exists.', Response::HTTP_CONFLICT);
            }
            $organizer = Organizer::create($data);
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

    public function logout(){
        try {
            auth()->logout(Auth::user());
            return $this->apiResponse(false, 'Logout', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
      
    }
}
