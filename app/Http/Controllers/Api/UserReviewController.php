<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request_data = $request->all(); 

        $validator = \Validator::make($request_data, [
            'restaurant_id' => 'required|exists:businesses,id',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }
        $request_data['restaurant_id'] = $request->restaurant_id;
        $data = $this->UserReviewObj->getUserReview($request_data);
        return $this->sendResponse($data, 'User Reviews');
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
            'restaurant_id' => 'required|exists:businesses,id',
            'stars'    => 'required|min:1|max:5|integer',
            'message'    => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }
        $request_data['user_id'] = \Auth::user()->id;
        $rider_charges = $this->UserReviewObj->saveUpdateUserReview($request_data);
        return $this->sendResponse($rider_charges, 'Your review added successfully.');
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
        
        $this->UserReviewObj->deleteUserReview($id);
        return $this->sendResponse('Success', 'Your review deleted successfully');
    }
}
