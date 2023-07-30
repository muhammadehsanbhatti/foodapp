<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request_data = $request->all();
       
        $data = $this->AboutObj->getAbout($request_data);
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

        $data = $this->AboutObj->saveUpdateAbout($request_data);
        return $this->sendResponse($data, 'About us added successfully.');
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
        $requested_data =array();
        $requested_data['id'] = $id;
        
        $data = $this->AboutObj->getAbout($requested_data);
        return $this->sendResponse($data, 'About us');
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
        echo '<pre>'; print_r("dfsd"); echo '</pre>'; exit;
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

        
        $data = $this->AboutObj->saveUpdateAbout($requested_data);
        return $this->sendResponse($data, 'About us updated successfully.');

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