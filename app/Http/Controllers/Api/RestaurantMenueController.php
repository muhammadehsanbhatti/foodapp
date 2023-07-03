<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantMenueController extends Controller
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
        $request_data = $request->all(); 
        $base_url = public_path();
        
        $validator = \Validator::make($request_data, [
            'restaurant_id'    => 'required|exists:businesses,id',
            'item_name'        => 'required||regex:/^[a-zA-Z ]+$/u',
            'description'      => 'required||regex:/^[a-zA-Z0-9 ]+$/u',
            'regular_price'    => 'required',
            'sale_price'       => 'required|',
            'stock'            => 'required',
            'category'         => 'required',
            'category_type'    => 'required',
            'restaurant_file'  => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }

        $restaurant_menue = $this->RestaurantMenueObj->saveUpdateRestaurantMenue($request_data);

        if ( isset($restaurant_menue->id) ){
            if($request->file('restaurant_file')) {
                foreach ($request_data['restaurant_file'] as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $file_name = time() . '_' . $image->getClientOriginalName();
                    $filePath = $image->storeAs('restaurant_file', $file_name, 'public');
                    $filePath = 'storage/restaurant_file/' . $file_name;
                    $response = $this->RestaurantFileObj->saveUpdateRestaurantFile([
                        'restaurnat_menu_id' => $restaurant_menue->id,
                        'restaurant_file' => $filePath,
                    ]);
                    $return_data[] = $response;
                }
                return $this->sendResponse($restaurant_menue, 'Restaurant menue added successfully.');
            }
        }else{
            $error_message['error'] = 'Somthing went wrong';  
        }
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
        $base_url = public_path();
        $requested_data['update_id'] = $id;
        
        $rules = array(
            
            'restaurant_id'    => 'required|exists:businesses,id',
            'item_name'        => 'required||regex:/^[a-zA-Z ]+$/u',
            'description'      => 'required||regex:/^[a-zA-Z0-9 ]+$/u',
            'regular_price'    => 'required',
            'sale_price'       => 'required|',
            'stock'            => 'required',
            'category'         => 'required',
            'category_type'    => 'required',
            'restaurant_file'  => 'required',
        );
       
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $restaurant_menue = $this->RestaurantMenueObj->saveUpdateRestaurantMenue($requested_data);
        // echo '<pre>'; print_r($restaurant_menue); echo '</pre>'; exit;

        if (isset($restaurant_menue->id)){
            if($request->file('restaurant_file')) {

                $restaurant_menue_file = $this->RestaurantFileObj->getRestaurantFile([
                    'restaurnat_menu_id' => $restaurant_menue->id,
                ])->ToArray();
                $data = array_column($restaurant_menue_file,'restaurant_file');
              
            foreach ($requested_data['restaurant_file'] as $restaurant_file_key =>$image) {

                if (!empty($data)) {
                    $url = public_path().'/'.$data[$restaurant_file_key];
                    // echo '<pre>'; print_r($url); echo '</pre>'; exit;
                    if (file_exists($url)) {
                        // echo '<pre>'; print_r($url); echo '</pre>'; exit;
                        unlink($url);
                    }
                }

                $extension = $image->getClientOriginalExtension();
                $file_name = time() . '_' . $image->getClientOriginalName();
                $filePath = $image->storeAs('restaurant_file', $file_name, 'public');
                $filePath = 'storage/restaurant_file/' . $file_name;
                $response = $this->RestaurantFileObj->saveUpdateRestaurantFile([
                    'restaurnat_menu_id' => $restaurant_menue->id,
                    'restaurant_file' => $filePath,
                ]);
                $return_data[] = $response;
            }
            // return $this->sendResponse($restaurant_menue, 'Restaurant menue added successfully.');
        }
        }else{
            $error_message['error'] = 'Somthing went wrong';  
        }

        // $data = $this->BusinessObj->saveUpdateBusiness($requested_data);
        return $this->sendResponse($restaurant_menue, 'Restaurante Updated successfully');
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
