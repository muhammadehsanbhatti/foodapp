<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddToCartController extends Controller
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
        $posted_data =array();
        $request_data = $request->all(); 
        $validator = \Validator::make($request_data, [
            'restaurant_menue_id'    => 'required|exists:restaurant_menues,id',
            'quantity'        => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }
        
        if (\Auth::check()) {
            $posted_data['user_id'] = \Auth::user()->id;
            $posted_data['user_checkout_id'] = \Auth::user()->id;
        }
        else{
            $posted_data['session_id'] =\Request::getClientIp(true);
        }
        if (isset($request_data['restaurant_menue_id'])) {
            foreach ($request_data['restaurant_menue_id'] as $restaurant_menue_key => $restaurant_menue_value) {
                $posted_data['restaurant_menue_id'] = $restaurant_menue_value;
                $posted_data['quantity'] = $request_data['quantity'][$restaurant_menue_key];
                $addToCartData[] = $this->AddToCartObj->saveUpdateAddToCart($posted_data);
            }
        }
        return $this->sendResponse($addToCartData, 'Add to cart added successfully.');
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
            'restaurant_menue_id'    => 'required|exists:restaurant_menues,id',
            'quantity'        => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }
        
        if (\Auth::check()) {
            $request_data['user_id'] = \Auth::user()->id;
            $request_data['user_checkout_id'] = \Auth::user()->id;
            
        }
        else{
            $request_data['session_id'] =\Request::getClientIp(true);
        }
        
        $addToCartData = $this->AddToCartObj->saveUpdateAddToCart($request_data);
        
        return $this->sendResponse($addToCartData, 'Add to cart updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->AddToCartObj->deleteAddToCart($id);
        return $this->sendResponse('Success', 'User successfully removed add to cart data successfully');
        
    }
}
