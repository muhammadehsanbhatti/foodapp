<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  function __construct()
    // {
    //     parent::__construct();
    //     $this->middleware('permission:restaurant-list|restaurant-edit|restaurant-delete', ['only' => ['index']]);
    //     $this->middleware('permission:restaurant-create', ['only' => ['create','store']]);
    //     $this->middleware('permission:restaurant-edit', ['only' => ['edit','update']]);
    //     $this->middleware('permission:restaurant-delete', ['only' => ['destroy']]);

    //     $this->middleware('permission:restaurant_menue-list|restaurant_menue-edit|restaurant_menue-delete', ['only' => ['index']]);
    //     $this->middleware('permission:restaurant_menue-create', ['only' => ['create','store']]);
    //     $this->middleware('permission:restaurant_menue-edit', ['only' => ['edit','update']]);
    //     $this->middleware('permission:restaurant_menue-delete', ['only' => ['destroy']]);

    // }

    public function index(Request $request)
    {

        $posted_data = array();
        $request_data = $request->all();
        $posted_data['paginate'] = 10;
        if($request_data){
            $posted_data = array_merge($posted_data,$request_data);
        }
        $data = $this->BusinessObj->getBusiness($posted_data);
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
        $base_url = public_path();
        
        $validator = \Validator::make($request_data, [
            'restaurant_id'    => 'required|exists:businesses,id',
            'item_name'    => 'required||regex:/^[a-zA-Z ]+$/u',
            'description'    => 'required||regex:/^[a-zA-Z0-9 ]+$/u',
            'regular_price'    => 'required',
            'sale_price'    => 'required|',
            'stock'    => 'required',
            'category'    => 'required',
            'category_type'    => 'required',
            'restaurant_file'    => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);   
        }

        $restaurant_menue = $this->RestaurantMenueObj->saveUpdateRestaurantMenue($request_data);

        if ( isset($restaurant_menue->id) ){

            // $restaurant_menue_file = $this->RestaurantFileObj->getRestaurantFile([
            //     'restaurnat_menu_id' => $restaurant_menue->id,
            // ]);
            // echo '<pre>'; print_r($restaurant_menue_file); echo '</pre>'; exit;
            // if (isset($restaurant_menue_file)) {
            //     $url = $base_url.'/'.$restaurant_menue_file->restaurant_file;
            //     if (file_exists($url)) {
            //         unlink($url);
            //     }
            // }
             

            if($request->file('restaurant_file')) {
                foreach ($request->file('restaurant_file') as $image) {
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
