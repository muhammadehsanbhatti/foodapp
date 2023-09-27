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
    public function index(Request $request)
    { 
        $requested_data = array();
        $requested_data = $request->all();
        // $requested_data['detail'] = true;
        
        $get_restaurant_detail = $this->UserObj->getUser([
            'detail' => true,
            'id' => \Auth::user()->id,
            // 'user_login_status' => 'admin',
        ]);

        if ($get_restaurant_detail->user_login_status == 'super-admin') {
            $requested_data = $request->all();
            // $get_order_list = $this->PaymentHistroyObj->getPaymentHistroy($requested_data);
        }
        if ($get_restaurant_detail->user_login_status == 'admin') {
            $requested_data['restaurant_id'] = $get_restaurant_detail->busines->id;
        }
        if ($get_restaurant_detail->user_login_status == 'customer') {
            $requested_data['user_id'] = \Auth::user()->id;
        }
        
        if ($get_restaurant_detail->user_login_status == 'rider') {
            $requested_data['delivery_status_not'] = "Customer Own Order Pickup";
            $requested_data['rider_id_null'] = true;
        }
        
        $data = $this->PaymentHistroyObj->getPaymentHistroy($requested_data);
        return $this->sendResponse($data, 'Order histroy.');

    }
    public function order_list(Request $request){
        $get_restaurant_detail = $this->UserObj->getUser([
            'detail' => true,
            'id' => \Auth::user()->id,
        ]);
        if ($get_restaurant_detail->user_login_status == 'super-admin') {
                $get_order_list = $this->PaymentHistroyObj->getPaymentHistroy();
        }
        else if ($get_restaurant_detail['busines']['id'] && $get_restaurant_detail->user_login_status == 'admin') {
            $get_order_list = $this->PaymentHistroyObj->getPaymentHistroy([
                'restaurant_id' => $get_restaurant_detail['busines']['id']
            ]);
        }
        else{
            return $this->sendError("error" , 'You can not access to check order list contact with admin');
        }
        return $this->sendResponse($get_order_list, 'Order list.');
    }
    public function change_order_status(Request $request, $id)
    {
       
        $get_restaurant_detail = $this->UserObj->getUser([
            'detail' => true,
            'id' => \Auth::user()->id,
        ]);
        if (isset($get_restaurant_detail['busines']['id']) && $get_restaurant_detail->user_login_status == 'admin') {
            $posted_data = array();
            $requested_data = $request->all();
            $requested_data['update_id'] = $id;
    
            $rules = array(
                'id' => 'exists:payment_histroys,id',
                'order_completing_time' => 'required|integer',
                'order_status' => 'required|in:Pending,Preparing,InProgress,Late,Rejected,Ready To Deliver,Delivered',
            );
            
            $validator = \Validator::make($requested_data, $rules);
    
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
            }
            $data = $this->PaymentHistroyObj->saveUpdatePaymentHistroy($requested_data);
            return $this->sendResponse($data, 'Order status updated');
        }
        else{
            return $this->sendError("error" , 'You can not access to change order statuts');
        }
    }

    public function processPayment(Request $request)
    {
        try {

            $posted_data = array();
            $requested_data = $request->all();
            

            $rules = array(
                'user_id' => 'exists:add_to_carts,user_id',
                'address_id' => 'required_unless:delivery_status,Customer Own Order Pickup',
                'exists:user_addresses,id',
                // 'address_id' => 'required|exists:user_addresses,id',
                'payment_cart_information_id' => 'required_without_all:payment_status,CashOnDelivery,delivery_status,Customer Own Order Pickup',
                'exists:payment_cart_information,id',
                // 'payment_cart_information_id' => 'required|exists:payment_cart_information,id',
                'restaurant_id' => 'required|exists:restaurant_menues,restaurant_id',
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

                $payment_histroy_data = array();
                $payment_histroy_data['user_id'] = \Auth::user()->id;
                $payment_histroy_data['user_address_id'] = $get_user_address->id;
                $payment_histroy_data['restaurant_id'] = $request->restaurant_id;
                $payment_histroy_data['item_delivered_quantity'] = $total_quantity;
                $payment_histroy_data['rider_charges'] = 50;
                $payment_histroy_data['order_status'] = 'Pending';
                $payment_histroy_data['delivery_status'] = isset($request->delivery_status) && $request->delivery_status == 'Customer Own Order Pickup' ? $request->delivery_status:'Pending';

                if ($get_user_cart_information && !isset($request->payment_status) && isset($request->delivery_status) && $request->delivery_status == 'Customer Own Order Pickup') {
                    $stripe = new \Stripe\StripeClient(
                        env('STRIPE_SECRET')
                    );
                        
                    $res = $stripe->tokens->create([
                        'card' => [
                        'number' => $get_user_cart_information->card_number,
                        'exp_month' =>  $get_user_cart_information->exp_month,
                        'exp_year' => $get_user_cart_information->exp_year,
                        'cvc' => $get_user_cart_information->cvc,
                        ],
                    ]);
                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            
                    $response = $stripe->charges->create([
                        'amount' =>  ($total_price * $total_quantity) + $get_rider_service_charges->per_killometer_price ,
                        'currency' => $request->currency,
                        'source' => $res->id,
                        'description' => $request->description,
                    ]);
                    
                    $payment_histroy_data['payment_card_information_id'] = $get_user_cart_information->id;
                    $payment_histroy_data['customer_name'] = $get_user_cart_information->card_holder_name;
                    $payment_histroy_data['currency'] = $request->currency;
                    $payment_histroy_data['amount_captured'] = $total_price * $total_quantity;
                    $payment_histroy_data['payment_status'] = isset($request->delivery_status) && ($request->delivery_status == 'Customer Own Order Pickup') ? NULL : $get_user_cart_information->payment_status;
                }
                else if (isset($request->payment_status) && !isset($request->payment_cart_information_id)) {
                    $payment_histroy_data['customer_name'] = \Auth::user()->first_name;
                    $payment_histroy_data['payment_status'] = $request->payment_status;
                }
                else if (!isset($request->payment_cart_information_id) && $request->delivery_status == 'Customer Own Order Pickup') {
                    $payment_histroy_data['customer_name'] = \Auth::user()->first_name;
                    $payment_histroy_data['payment_status'] = NULL;
                }
                else{
                    return $this->sendError("eror", "Invalid cart information");
                } 

                $data = $this->PaymentHistroyObj->saveUpdatePaymentHistroy($payment_histroy_data);

                if ($data) {
                    $this->AddToCartObj->deleteAddToCart(0,['user_id' => \Auth::user()->id]);
                    return $this->sendResponse(isset($response->status) ?$response->status:'Success', 'Thansk, Your order completed successfully.');
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
            'delivery_status'    => 'required',
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
            $data = $this->PaymentHistroyObj->saveUpdatePaymentHistroy([
                'update_id' => $id,
                'rider_id' => \Auth::user()->id,
                'delivery_status' => $requested_data['delivery_status']
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
