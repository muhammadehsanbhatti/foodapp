<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function rider_earnings(){
        $data = $this->PaymentHistroyObj->getPaymentHistroy([
            'user_id' => \Auth::user()->id,
        ]);
        return $this->sendResponse($data, 'Rider earnings');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = $request->all(); 

        $validator = \Validator::make($request_data, [
            'user_id'    => 'required|exists:users,id',
            'company'    => 'required',
            'color'    => 'required',
            'model'    => 'required',
            'vechicle_number'    => 'required',
            'vechicle_condition'    => 'required|in:New,Normal,Rough',
            'vechicle_type'    => 'required|in:Car,Bike',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }

        $rider_charges = $this->RiderChargeObj->saveUpdateRiderCharge($request_data);
        return $this->sendResponse($rider_charges, 'Rider charges added successfully.');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
