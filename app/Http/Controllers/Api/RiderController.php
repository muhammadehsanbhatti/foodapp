<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiderController extends Controller
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
    public function rider_earnings(){
        $data = $this->PaymentHistroyObj->getPaymentHistroy([
            'user_id' => \Auth::user()->id,
        ]);
        return $this->sendResponse($data, 'Rider earnings');
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
        $posted_data = array();
        $request_data = $request->all(); 

        $validator = \Validator::make($request_data, [
            'company'    => 'required',
            'color'    => 'required',
            'model'    => 'required',
            'vehicle_number'    => 'required|unique:rider_vehicle_information,vehicle_number',
            'vehicle_condition'    => 'required|in:New,Normal,Rough',
            'vehicle_type'    => 'required|in:Car,Bike',
            'licence_image'    => 'required|max:2048',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }
        $request_data['user_id'] = \Auth::user()->id;
        $vehicle_data = $this->RiderVehicleInformationObj->saveUpdateRiderVehicleInformation($request_data);
        if (isset($vehicle_data)) {
            $posted_data['vehicle_id'] = $vehicle_data->id;

            foreach ($request_data['licence_image'] as $key => $licence_image_file) {
                if ($request->file('licence_image')) {
                    $extension = $licence_image_file->getClientOriginalExtension();
                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
        
                        $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;
        
                        $filePath = $licence_image_file->storeAs('vehicle_assets', $file_name, 'public');
                        $posted_data['image'] = 'storage/vehicle_assets/' . $file_name;
                        $posted_data['asset_type'] = 'Vehicle';
                    } else {
                        $error_message['error'] = 'Only allowled jpg, jpeg or png image format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }
                $data[] = $this->RiderAssetObj->saveUpdateRiderAsset($posted_data);
            }
            $vehicle_data = $this->RiderVehicleInformationObj->getRiderVehicleInformation([
                'id'=>$vehicle_data->id
            ]);
        }     
        return $this->sendResponse($vehicle_data, 'Vehicle information added successfully.');
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

    // Schedule Apis
    public function schedule()
    {
        $data = $this->ScheduleObj->getSchedule([
            'user_id' => \Auth::user()->id,
        ]);
        return $this->sendResponse($data, 'User Schedules');
    }

    public function schedule_store(Request $request)
    {
        $posted_data = array();
        $request_data = $request->all(); 

        $validator = \Validator::make($request_data, [
            'start_date' => 'required|date_format:Y-m-d h:i:s a|before_or_equal:end_date',
            'end_date' => 'required|date_format:Y-m-d h:i:s a|after_or_equal:start_date',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }
        $request_data['user_id'] = \Auth::user()->id;
        $data = $this->ScheduleObj->saveUpdateSchedule($request_data);
        return $this->sendResponse($data, 'Schedules added successfully');
    }

    public function schedule_update(Request $request,$id)
    {
        $posted_data = array();
        $request_data = $request->all(); 
        $request_data['update_id'] = $id; 

        $validator = \Validator::make($request_data, [
            'start_date' => 'required|date_format:Y-m-d h:i:s a|before_or_equal:end_date',
            'end_date' => 'required|date_format:Y-m-d h:i:s a|after_or_equal:start_date',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);   
        }

        $data = $this->ScheduleObj->saveUpdateSchedule($request_data);
        return $this->sendResponse($data, 'Schedules updated successfully');
    }
    public function destroy_schedule($id)
    {
        $this->ScheduleObj->deleteSchedule($id);
        return $this->sendResponse('Success', 'Schedules deleted successfully');
    }
}
