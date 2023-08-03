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
