<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
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
        return $this->apiResponse('success', 'Collection of iftar', $query->get());
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
            return $this->apiResponse('error', 'Already a beneciary');
        }
        $beneficiary = Beneficiary::create($request->all());
        return $this->apiResponse('success', 'Welcome, you are now a beneficiary ', $beneficiary);
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
