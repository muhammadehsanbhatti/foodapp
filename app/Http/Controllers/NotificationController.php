<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if ( isset($params['filter']) && $params['filter'] == 'today'){
            $params['one_day_time'] = date("Y-m-d");
        }
        if ( isset($params['filter']) && $params['filter'] == 'last-day'){
            $params['last_day_time'] = date("Y-m-d", strtotime( '-1 days' ) );
        }
        if ( isset($params['filter']) && $params['filter'] == 'seven-day'){
            $params['last_seven_day_time'] = date("Y-m-d", strtotime( '-7 days' ) );
        }
        if ( isset($params['user_id']) && $params['user_id'] != '') {
            $params['receiver_id'] = $params['user_id'];
            unset($params['user_id']);
        }
        else $params['receiver_id'] = \Auth::user()->id;
                    
        $params['paginate'] = 10;

        
        if ( isset($params['notificationReadAll']) && $params['notificationReadAll'] == 'true'){
            Notification::saveUpdateNotification([
                'whereUpdate' => true,
                'where_seen_at_null' => true,
                'receiver_id' => $params['receiver_id'],
                'seen_at' => now(),
            ]);
        }


        $notification = Notification::getNotifications($params);
        unset($params['paginate']);
        $params['count'] = true;
        $params['unread'] = true;

        $unreadNotification = Notification::getNotifications($params);
       
        $html ='';
        foreach ($notification as $key => $value) {

        $is_seen_at = 'read_noti';
        if(is_null($value->seen_at)){
            $is_seen_at = 'not_read_noti';
        }

        $redirect_url ='javascript:void(0)';
        $meta_data = json_decode($value->meta_data); 
        $meta_data = (array)$meta_data;
        if (isset($meta_data['enquery_id'])){
            $id = $meta_data['enquery_id'];
            if(\Auth::user()->role == 2){
                $redirect_url = url('orderview').'/'.$id;
            }else if(\Auth::user()->role == 3 || \Auth::user()->role == 1){
                $redirect_url = url('order/create').'/'.$id;
            }
        }

        $html .= '<a class="d-flex" href="'.$redirect_url.'">
                <div class="media d-flex align-items-start '. $is_seen_at .'">
                    <div class="media-left">
                       <div class="avatar"><img src= "'. is_image_exist($value->senderDetails->profile_image).'" alt="Not Found" width="32" height="32"></div>
                    </div>
                    <div class="media-body">
                         <p class="media-heading"><span class="font-weight-bolder">' . $value->senderDetails->first_name. '' .$value->senderDetails->last_name.  '</span></p>
                        <small class="notification-text">' . $value->notification_text .  '</small><br>
                        
                        <small class="notification-text">' . time_elapsed_string($value->created_at) .  '</small>
                    </div>
                </div>
            </a>';
        }

        $data = array();
    
        $data['unreadNotification'] = $unreadNotification;
        $data['notificationListing'] = $html;
        $data['totlNotification'] = count($notification);
        // echo '<pre>';print_r($params);echo '</pre>';exit;
        echo json_encode($data); exit;
    }
    public function get_notificatiion_token(Request $request){     
        $posted_data = $request->all();
        $posted_data['device_id'] = \Session::getId();
        $posted_data['user_id'] = \Auth::user()->id;
        $posted_data['detail'] = true;
        $get_notification_tokens = $this->FcnTokenObj->getFcmTokens($posted_data);
        if(!$get_notification_tokens){
            $this->FcnTokenObj->saveUpdateFcmToken($posted_data);
        }
        return response()->json(['message' => 'Data submitted']);
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
   
        $validator = Validator::make($request_data, [
            'receiver_id' => 'required',
            'sender_id' => 'required',
            'notification_text' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $notification = Notification::saveUpdateNotification($request_data);

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['notification_message_id'] = $notification['id'];

        $notification_data = Notification::getNotifications($posted_data);

        return $this->sendResponse($notification_data, 'Notification posted successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);
  
        if (is_null($notification)) {
            $error_message['error'] = 'Notification is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
        return $this->sendResponse($notification, 'Notification retrieved successfully.');
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
        $validator = Validator::make($request_data, [
            'receiver_id' => 'required',
            'sender_id' => 'required',
            'meta_data' => 'required',
            'notification_text' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);       
        }

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['id'] = $id;
        $notification = Notification::getNotifications($posted_data);
        if(!$notification){
            $error_message['error'] = 'Notification is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
        
        $request_data['update_id'] = $id;
        $notification = Notification::saveUpdateNotification($request_data);

        return $this->sendResponse($notification, 'Notification updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Notification::find($id)){
            Notification::deleteNotification($id); 
            return $this->sendResponse([], 'Notification deleted successfully.');
        }else{
            $error_message['error'] = 'Notification is already deleted.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }
    
}