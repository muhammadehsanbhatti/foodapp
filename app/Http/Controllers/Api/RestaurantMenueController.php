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
                    $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;
                    $filePath = $image->storeAs('restaurant_file', $file_name, 'public');
                    $filePath = 'storage/restaurant_file/' . $file_name;
                    
                    $response = $this->RestaurantFileObj->saveUpdateRestaurantFile([
                        'restaurnat_menu_id' => $restaurant_menue->id,
                        'restaurant_file' => $filePath,
                    ]);
                    // $restaurant_menue[] = $response->restaurant_file;
                    // echo '<pre>'; print_r($restaurant_menue['restaurant_file']); echo '</pre>'; exit;
                }
                if (isset($request_data['required_menue_type'])) {
                    $posted_data = array();
                    $posted_data['restaurant_menue_id']=  $restaurant_menue->id;
                    $posted_data['menue_type']= $request_data['required_menue_type'];
                    $posted_data['variant_type'] = $request_data['required_variant_type'];
                    foreach ($request_data['required_variant_name'] as $variant_key => $variant_value) {
                        $posted_data['variant_name']= $variant_value;
                        $posted_data['variant_price']= $request_data['required_variant_price'][$variant_key];
                        $posted_data['variant_description']= $request_data['required_variant_description'][$variant_key];
                        $required_menue_variants[]=$this->MenueVariantsObj->saveUpdateMenueVariant($posted_data);
                    }
                    $restaurant_menue['required_menue_variants'] = $required_menue_variants;
                }
                if (isset($request_data['optional_menue_type'])) {
                    $requestdata = array();
                    $requestdata['restaurant_menue_id']=  $restaurant_menue->id;
                    $requestdata['menue_type']= $request_data['optional_menue_type'];
                    $requestdata['variant_type'] = $request_data['optional_variant_type'];

                    foreach ($request_data['optional_variant_image'] as $variant_key => $variant_image_value) {
                        $requestdata['variant_name']=$request_data['optional_variant_name'][$variant_key];
                        $requestdata['variant_price']= $request_data['optional_variant_price'][$variant_key];
                        $requestdata['variant_description']= $request_data['optional_variant_description'][$variant_key];

                        if($request->file('optional_variant_image')) {
                            $extension = $variant_image_value->getClientOriginalExtension();
                            $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;
                            $filePath =  $variant_image_value->storeAs('optional_menue_variant_images', $file_name, 'public');
                            $filePath = 'storage/optional_menue_variant_images/' . $file_name;
                            $requestdata['variant_image'] = $filePath;
                        }
                        $optional_menue_variants[] = $this->MenueVariantsObj->saveUpdateMenueVariant($requestdata);
                    }
                    $restaurant_menue['optional_menue_variants'] = $optional_menue_variants;
                }
                $restaurant_menue['restaurant_menue_files'] = $this->RestaurantFileObj->getRestaurantFile([
                    'restaurnat_menu_id' => $restaurant_menue->id,
                    // 'restaurant_file_relationship' =>true
                ]);
               
                // echo '<pre>'; print_r($restaurant_menue['restaurant_file']); echo '</pre>'; exit;
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
        $request_data['id'] = $id;
        $validator = \Validator::make($request_data, [
            'id'    => 'exists:restaurant_menues,id',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }
        $data = $this->RestaurantMenueObj->getRestaurantMenue($request_data);
        return $this->sendResponse($data, 'Success');
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
                $restaurant_menue_data = array_column($restaurant_menue_file, 'restaurant_file');
                $update_ids = array_column($restaurant_menue_file, 'id');
                foreach ($requested_data['restaurant_file'] as $restaurant_file_key =>$image) {
               
                    if (!empty($restaurant_menue_data) && isset($update_ids[$restaurant_file_key])) {
                        $base_url = public_path();
                        $url = $base_url.'/'.$restaurant_menue_data[$restaurant_file_key];
                        if (file_exists($url)) {
                            unlink($url);
                        }
                    }

                    $extension = $image->getClientOriginalExtension();
                    $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;
                    $filePath = $image->storeAs('restaurant_file', $file_name, 'public');
                    $filename = 'storage/restaurant_file/' . $file_name;

                    $requested_file =array();
                    $requested_file['restaurnat_menu_id'] = $restaurant_menue->id;
                    $requested_file['restaurant_file'] =$filename;

                   
                    if (isset($update_ids[$restaurant_file_key])) {
                       $requested_file['update_id'] =$update_ids[$restaurant_file_key];
                    }
                   
                    $response = $this->RestaurantFileObj->saveUpdateRestaurantFile($requested_file);
                    $return_data[] = $response;

                }
                // return $this->sendResponse($restaurant_menue, 'Restaurant menue added successfully.');
            }
        }else{
            $error_message['error'] = 'Somthing went wrong';  
        }
        // $restaurant_menue['restaurant_file']= $restaurant_menue_file;
        // $data = $this->BusinessObj->saveUpdateBusiness($requested_data);
        return $this->sendResponse($restaurant_menue, 'Restaurante Updated successfully');
    }

    public function required_menue_varients_store(Request $request)
    {
        $requested_data = $request->all();
        $base_url = public_path();
        $rules = array(
        
            'restaurant_menue_id'    => 'required|exists:restaurant_menues,id',
            'menue_type'        => 'required',
            'variant_type'      => 'required',
            'variant_name'    => 'required',
            'variant_price'       => 'required'
        );
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        $posted_data = array();
        $posted_data['restaurant_menue_id']=  $requested_data['restaurant_menue_id'];
        $posted_data['menue_type']= $requested_data['menue_type'];
        $posted_data['variant_type'] = $requested_data['variant_type'];
        foreach ($requested_data['variant_name'] as $variant_key => $variant_value) {
            $posted_data['variant_name']= $variant_value;
            $posted_data['variant_price']= $requested_data['variant_price'][$variant_key];
            $data[] = $this->MenueVariantsObj->saveUpdateMenueVariant($posted_data);
        }
        return $this->sendResponse($data, 'Restaurante required variants added successfully');

    }

    public function optional_menue_varients_store(Request $request)
    {
        $requested_data = $request->all();
        $rules = array(
        
            'restaurant_menue_id'    => 'required|exists:restaurant_menues,id',
            'menue_type'        => 'required',
            'variant_type'      => 'required',
            'variant_name'    => 'required',
            'variant_price'       => 'required',
            // 'variant_image' => 'required'
        );
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        $posted_data = array();
        $posted_data['restaurant_menue_id']=  $requested_data['restaurant_menue_id'];
        $posted_data['menue_type']= $requested_data['menue_type'];
        $posted_data['variant_type'] = $requested_data['variant_type'];

        foreach ($requested_data['variant_image'] as $variant_key => $variant_image_value) {
            $posted_data['variant_name']=$requested_data['variant_name'][$variant_key];
            $posted_data['variant_price']= $requested_data['variant_price'][$variant_key];

            if($request->file('variant_image')) {
                $extension = $variant_image_value->getClientOriginalExtension();
                $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;
                $filePath =  $variant_image_value->storeAs('optional_menue_variant_images', $file_name, 'public');
                $filePath = 'storage/optional_menue_variant_images/' . $file_name;
                $posted_data['variant_image'] = $filePath;
            }
            $data[] = $this->MenueVariantsObj->saveUpdateMenueVariant($posted_data);
        }
        return $this->sendResponse($data, 'Restaurante optional variants added successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->RestaurantMenueObj->deleteRestaurantMenue($id);
        return $this->sendResponse('Success', 'Restaurant menue deleted successfully');
        
    }
}
