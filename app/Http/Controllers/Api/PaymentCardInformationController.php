<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentCardInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->PaymentCartInformationObj->getPaymentCartInformation([
            'user_id' => \Auth::user()->id

        ]);
        return $this->sendResponse($data, 'Success');
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
            'card_holder_name'    => 'required',
            'card_number'    => 'required|unique:payment_cart_information,card_number,'.$request->card_number,
            'exp_month'    => 'required',
            'exp_year'    => 'required',
            'cvc'    => 'required',
            'payment_status'    => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }

        $request_data['user_id'] = \Auth::user()->id;
        $user_payment_card_information = $this->PaymentCartInformationObj->saveUpdatePaymentCartInformation($request_data);
        return $this->sendResponse($user_payment_card_information, 'User payment cart information added successfully.');
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
        $request_data = $request->all(); 
        $request_data['update_id'] = $id; 

        $validator = \Validator::make($request_data, [
            
            'card_holder_name'    => 'required',
            'card_number'    => 'required',
            'exp_month'    => 'required',
            'exp_year'    => 'required',
            'payment_status'    => 'required',
            'cvc'    => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }

        $request_data['user_id'] = \Auth::user()->id;
        $user_payment_card_information = $this->PaymentCartInformationObj->saveUpdatePaymentCartInformation($request_data);
        return $this->sendResponse($user_payment_card_information, 'User payment card information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->PaymentCartInformationObj->deletePaymentCartInformation($id);
        return $this->sendResponse('Success', 'Payment cart information deleted successfully');
    }
}
