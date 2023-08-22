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
        $data = $this->PaymentHistroyObj->getPaymentHistroy([
            'detail' =>true
        ]);
        return $this->sendResponse($data, 'Order histroy.');

    }

    public function processPayment(Request $request)
    {
        try {

            $posted_data = array();
            $requested_data = $request->all();
            
            $rules = array(
                'user_id' => 'exists:add_to_carts,user_id',
                'address_id' => 'required|exists:user_addresses,id',
                'payment_cart_information_id' => 'required|exists:payment_cart_information,id',
                'restaurant_id' => 'required|exists:restaurant_menues,restaurant_id',
                'cvc' => 'required|max:250',
            );
           
            $validator = \Validator::make($requested_data, $rules);
    
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
            }

            $add_cart_data = $this->AddToCartObj->getAddToCart([
                'user_checkout_id' => \Auth::user()->id,
            ]);
            $get_restaurant_menue_id = $add_cart_data->ToArray();
            $return_data = array();
            $total_price = 0;
            $total_quantity =0;
            foreach ($get_restaurant_menue_id as $get_restaurant_menue_key => $get_restaurant_menue_value) {
                $get_restaurant_menue = $this->RestaurantMenueObj->getRestaurantMenue([
                    'id' => $get_restaurant_menue_value['restaurant_menue_id'],
                    'restaurant_id' => $request->restaurant_id,
                    'detail' => true
                ]);

                // $user_cart_variants = $this->UserCartMenueVariantsObj->getUserCartMenueVariants([
                //     'user_id' =>\Auth::user()->id,
                //     'add_to_cart_id' => $get_restaurant_menue_value['id'],
                //     'menue_variant_id' => $get_restaurant_menue_value['restaurant_menue_id'],
                //     'detail' => true

                // ]);
                // if ($user_cart_variants) {
                //     $user_variants_price = $this->MenueVariantsObj->getMenueVariant([
                //         'restaurant_menue_id' =>$get_restaurant_menue_value['restaurant_menue_id'],
                //         'detail' => true
                        
                //     ]);
                //     // echo '<pre>'; print_r($user_variants_price->ToArray()); echo '</pre>'; 
                //     $user_variant_price = (int)$user_variants_price->variant_price;
                //     // echo '<pre>'; print_r($user_variant_price); echo '</pre>'; exit;
                // }      
                $total_price += (int)$get_restaurant_menue['sale_price'];
                $total_quantity += $get_restaurant_menue_value['quantity'];
                $return_data[] = $get_restaurant_menue;
            }
            $get_user_address = $this->UserAddressObj->getUserAddress([
                'user_id' => \Auth::user()->id,
                'id' => $request->address_id,
                'detail' => true
            ]);
            if ($get_user_address) {
            
                $get_user_cart_information = $this->PaymentCartInformationObj->getPaymentCartInformation([
                    'user_id' => \Auth::user()->id,
                    'id' => $request->payment_cart_information_id,
                    'detail' => true
                ]);

                $get_rider_service_charges = $this->RiderChargeObj->getRiderCharge([
                    'detail' => true
                ]);

                if ($get_user_cart_information) {
                    $stripe = new \Stripe\StripeClient(
                        env('STRIPE_SECRET')
                    );
                    
                    $res = $stripe->tokens->create([
                      'card' => [
                        'number' => $get_user_cart_information->card_number,
                        'exp_month' =>  $get_user_cart_information->exp_month,
                        'exp_year' => $get_user_cart_information->exp_year,
                        'cvc' => $request->cvc,
                      ],
                    ]);
                   Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
                    $response = $stripe->charges->create([
                        'amount' =>  ($total_price * $total_quantity) + $get_rider_service_charges->per_killometer_price ,
                        'currency' => $request->currency,
                        'source' => $res->id,
                        'description' => $request->description,
                    ]);
                    
                    $posted_data = array();
                    $posted_data['user_id'] = \Auth::user()->id;
                    $posted_data['user_address_id'] = $get_user_address->id;
                    $posted_data['payment_card_information_id'] = $get_user_cart_information->id;
                    $posted_data['restaurant_id'] = $request->restaurant_id;
                    $posted_data['customer_name'] = \Auth::user()->first_name. \Auth::user()->last_name;
                    $posted_data['currency'] = $request->currency;
                    $posted_data['amount_captured'] = $total_price * $total_quantity;
                    $posted_data['item_delivered_quantity'] = $total_quantity;
                    $posted_data['payment_status'] = $get_user_cart_information->payment_status;
                    $posted_data['order_status'] = 'Pending';
                    $posted_data['delivery_status'] = 'Pending';
                    $data = $this->PaymentHistroyObj->saveUpdatePaymentHistroy($posted_data);
                    if ($data) {
                        $this->AddToCartObj->deleteAddToCart(0,['user_id' => \Auth::user()->id]);
                    }
                    return $this->sendResponse($response->status, 'Thansk, Your transaction completed successfully.');
                }
                else{
                    return $this->sendError("eror", "Invalid cart information");
                } 
            }
            else{
                return $this->sendError("eror", "First you add address");
            }
        } catch (\Exception $e) {
            return $this->sendError($e, ["error" => $e->getMessage()]);

            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function order_pickup(Request $request, $id)
    {
        $requested_data = array();
        $requested_data = $request->all();
        $rules = array(
            'order_status'    => 'required',
        );
       
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $check_user = $this->UserObj->getUser([
            'id' => \Auth::user()->id,
            'user_login_status' => 'rider',
            'is_blocked' => 0
        ]);
        if ($check_user) {
            $data = $this->PrivacyPolicyObj->saveUpdatePrivacyPolicy([
                'update_id' => $id,
                'rider_id' => \Auth::user()->id,
                'order_status' => $requested_data['order_status']
            ]);
            return $this->sendResponse($data, 'Order status updated successfully.');
        }
        else{
            return $this->sendError("error" ,'Something went wrong');

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
