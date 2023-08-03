<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe;

class PaymentController extends Controller
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

    public function processPayment(Request $request)
    {
        try {

            $posted_data = array();
            $requested_data = $request->all();
            
            $rules = array(
                'user_checkout_id' => 'required|exists:add_to_carts,user_checkout_id',
                'user_id' => 'exists:add_to_carts,user_id',
                'card_number' => 'required',
                'exp_month' => 'required|max:250',
                'exp_year' => 'required|max:250',
                'cvc' => 'required|max:250',
            );
           
            $validator = \Validator::make($requested_data, $rules);
    
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
            }
            $posted_data['user_checkout_id'] = $request->user_checkout_id;

            $add_cart_data = $this->AddToCartObj->getAddToCart([
                'user_checkout_id' => $request->user_checkout_id
            ]);
            
            $get_restaurant_menue_id = $add_cart_data->ToArray();
            $get_restaurant_menue_id = array_column($get_restaurant_menue_id, 'restaurant_menue_id');
            $return_data = array();
            foreach ($get_restaurant_menue_id as $get_restaurant_menue_key => $get_restaurant_menue_value) {
                $get_restaurant_menue = $this->RestaurantMenueObj->getRestaurantMenue([
                    'id' => $get_restaurant_menue_value,
                    'detail' => true
                ]);
                $calculate_price = $get_restaurant_menue['sale_price'];
                echo '<pre>'; print_r($calculate_price); echo '</pre>'; exit;
                $return_data[] =$get_restaurant_menue;
            }


            return $this->sendResponse($return_data, 'Success');


            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            $res = $stripe->tokens->create([
              'card' => [
                'number' => $request->card_number,
                'exp_month' =>  $request->exp_month,
                'exp_year' => $request->exp_year,
                'cvc' => $request->cvc,
              ],
            ]);
           Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $response = $stripe->charges->create([
            'amount' => $request->amount,
            'currency' => 'usd',
            'source' => $res->id,
            'description' => $request->description,
            ]);
            return $this->sendResponse($response->status, 'Thansk, Your transaction completed successfully.');


        } catch (\Exception $e) {
            return $this->sendError($e, ["error" => $e->getMessage()]);

            // return response()->json(['error' => $e->getMessage()], 500);
        }
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
        //
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
