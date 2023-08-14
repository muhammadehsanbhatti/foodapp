<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request_data = $request->all();
       
        $request_data['detail'] = true;
        $data = $this->PrivacyPolicyObj->getPrivacyPolicy($request_data);
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
            'message'    => 'required||regex:/^[a-zA-Z0-9 ]+$/u',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }

        $data = $this->PrivacyPolicyObj->saveUpdatePrivacyPolicy($request_data);
        return $this->sendResponse($data, 'Privacy policy added successfully.');
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
        $requested_data = array();
        $requested_data = $request->all();
        $requested_data['update_id'] = $id;
        
        $rules = array(
            'message'    => 'required||regex:/^[a-zA-Z0-9 ]+$/u',
        );
       
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        
        $data = $this->PrivacyPolicyObj->saveUpdatePrivacyPolicy($requested_data);
        return $this->sendResponse($data, 'Privacy policy updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->PrivacyPolicyObj->deletePrivacyPolicy($id);
        return $this->sendResponse('Success', 'Privacy policy deleted successfully');
    }
}
