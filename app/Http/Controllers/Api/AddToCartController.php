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
        $cart_variants =array();
        $request_data = $request->all(); 
        $validator = \Validator::make($request_data, [
            'restaurant_menue_id'    => 'required|exists:restaurant_menues,id',
            'menue_varient_id' => 'exists:menue_variants,id',
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
        $posted_data['restaurant_menue_id'] = $request->restaurant_menue_id;
        $posted_data['quantity'] = $request->quantity;
        $add_to_cart_data = $this->AddToCartObj->saveUpdateAddToCart($posted_data);
        if (isset($request_data['menue_varient_id'])) {
            foreach ($request_data['menue_varient_id'] as $restaurant_menue_varient_key => $restaurant_menue_varient_value) {
                $cart_variants['menue_variant_id'] = $restaurant_menue_varient_value;
                $cart_variants['add_to_cart_id'] = $add_to_cart_data->id;
                $cart_variants['user_id'] = \Auth::user()->id;
                $addToCartData[] = $this->UserCartMenueVariantsObj->saveUpdateUserCartMenueVariants($cart_variants);
            }
            $add_to_cart_data = $this->AddToCartObj->getAddToCart([
                'detail'=>true,
                'id'=>$add_to_cart_data->id,
            ]);
        }
        return $this->sendResponse($add_to_cart_data, 'Add to cart added successfully.');
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

        $request_data['restaurant_menue_id'] = $request->restaurant_menue_id;
        $request_data['quantity'] = $request->quantity;
        $add_to_cart_data = $this->AddToCartObj->saveUpdateAddToCart($request_data);
        

        if (isset($request_data['menue_varient_id'])) {
            $get_cart_variants = $this->UserCartMenueVariantsObj->getUserCartMenueVariants([
                'add_to_cart_id' => $add_to_cart_data->id,
            ])->ToArray();

                $update_ids = array_column($get_cart_variants, 'id');
                foreach ($request_data['menue_varient_id'] as $restaurant_menue_varient_key => $restaurant_menue_varient_value) {
                    // if ($update_ids) {
                    // }
                    $cart_variants['update_id'] = $update_ids[$restaurant_menue_varient_key];
                    $cart_variants['menue_variant_id'] = $restaurant_menue_varient_value;
                    $cart_variants['add_to_cart_id'] = $add_to_cart_data->id;
                    $cart_variants['user_id'] = \Auth::user()->id;
                
                    $this->UserCartMenueVariantsObj->saveUpdateUserCartMenueVariants($cart_variants);
                }
            
            // foreach ($request_data['menue_varient_id'] as $restaurant_menue_varient_key => $restaurant_menue_varient_value) {
            //     $cart_variants['menue_variant_id'] = $restaurant_menue_varient_value;
            //     $cart_variants['add_to_cart_id'] = $add_to_cart_data->id;
            //     $cart_variants['user_id'] = \Auth::user()->id;
            //     $addToCartData[] = $this->UserCartMenueVariantsObj->saveUpdateUserCartMenueVariants($cart_variants);
            // }
            $add_to_cart_data = $this->AddToCartObj->getAddToCart([
                'detail'=>true,
                'id'=>$add_to_cart_data->id,
            ]);
        }
        
        return $this->sendResponse($add_to_cart_data, 'Add to cart updated successfully.');
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
