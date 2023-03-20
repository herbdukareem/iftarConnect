<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response as FacadesResponse;

class BeneficiaryController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $query = Beneficiary::with('reservations');
        if($request->has('id')) {
            $query->where('id', $request->id)->orWhere('phone_number', $request->id);
        };
        return $this->apiResponse(false, $query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $beneficiary = Beneficiary::where('phone_number', $request->phone_number)->first();
        if ($beneficiary) {
            return $this->apiResponse(true, 'Already a beneciary', Response::HTTP_CONFLICT);
        }
        $beneficiary = Beneficiary::create($request->all());
        return $this->apiResponse(false,  $beneficiary);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $organizer = Beneficiary::with('reservations')->where('id', $id)->orWhere('phone_number', $id)->firstOrFail();
            return $this->apiResponse(false, $organizer);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(true, "Could not find Beneficiary", Response::HTTP_NOT_FOUND);
        }
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
        //
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
