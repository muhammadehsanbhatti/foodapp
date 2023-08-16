<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request_data = $request->all();
        $request_data['user_id'] = \Auth::user()->id;
       
       
        $data = $this->FavMenuObj->getFavMenu($request_data);
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
            'restaurant_menue_id'    => 'required|exists:restaurant_menues,id',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }
        if (\Auth::user()) {
            $request_data['user_id'] = \Auth::user()->id;
        }
        else{
            $request_data['ip_address'] = \Request::ip();
        }

        $data = $this->FavMenuObj->saveUpdateFavMenu($request_data);
        return $this->sendResponse($data, 'Menu in Favourite list added successfully.');
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

        $validator = \Validator::make($request_data, [
            'user_id' => 'exists:users,'.\Auth::user()->id,  
            'restaurant_menue_id'    => 'exists:restaurant_menues,'.$id,
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }

        $data = $this->FavMenuObj->deleteFavMenu(0,['id'=>$id,'user_id'=>\Auth::user()->id]);
        return $this->sendResponse('success', 'Remove Favourite Menu list successfully.');
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
