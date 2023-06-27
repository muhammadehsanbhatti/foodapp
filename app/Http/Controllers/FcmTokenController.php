<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FcmToken;


class FcmTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $posted_data =  $params;
        $posted_data['paginate'] = 10;

        if (isset($params['sender_id']))
            $posted_data['sender_id'] = $params['sender_id'];
        if (isset($params['receiver_id']))
            $posted_data['receiver_id'] = $params['receiver_id'];
        if (isset($params['per_page']))
            $posted_data['paginate'] = $params['per_page'];
        
        $fcm_token = FcmToken::getFcmTokens($posted_data);
        $message = !empty($fcm_token) ? 'FCM_Token retrieved successfully.' : 'FCM_Token not found against your query.';

        return $this->sendResponse($fcm_token, $message);
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
            'user_id' => 'required',
            'device_id' => 'required',
            'device_token' => 'required',
            'device_type' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $data['device_id'] = $request_data['device_id'];
        $data['detail'] = true;
        
        $token_res = FcmToken::getFcmTokens($data);
        
        if ($request_data['device_type'] == 'ios') {
            $request_data['device_token'] = $this->fetchIOSToken($request_data)['device_token'];
        }

        if ($token_res)
            $request_data['update_id'] = $token_res->id;

        $token = FcmToken::saveUpdateFcmToken($request_data);

        $posted_data = array();
        $posted_data['id'] = $token->id;
        $posted_data['detail'] = true;
        $token = FcmToken::getFcmTokens($posted_data);

        return $this->sendResponse($token, 'Token posted successfully.');
    }

       
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request_data = $request->all(); 
   
        $validator = Validator::make($request_data, [
            'user_id' => 'required',
            'device_id' => 'required',
            'device_token' => 'required',
            'device_type' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        if ($request_data['device_type'] == 'ios') {
            $request_data['device_token'] = $this->fetchIOSToken($request_data)['device_token'];
        }

        $request_data['detail'] = true;
        $token_res = FcmToken::getFcmTokens($request_data);
        
        if ($token_res)
            $request_data['update_id'] = $token_res->id;
        else {
            $error_message['error'] = 'This token is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
            

        $token = FcmToken::saveUpdateFcmToken($request_data);

        $posted_data = array();
        $posted_data['id'] = $token->id;
        $posted_data['detail'] = true;
        $token = FcmToken::getFcmTokens($posted_data);

        return $this->sendResponse($token, 'Token is updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = 0)
    {
        $request_data = $request->all();

        $validator = Validator::make($request_data, [
            'user_id' => 'required',
            'device_id' => 'required',
            'device_token' => 'required',
            'device_type' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        if ($request_data['device_type'] == 'ios') {
            $request_data['device_token'] = $this->fetchIOSToken($request_data)['device_token'];
        }

        $request_data['detail'] = true;
        $token = FcmToken::getFcmTokens($request_data);

        if($token) {
            $response = FcmToken::deleteFCM_Token($token['id']);
            return $this->sendResponse([], 'Token deleted successfully.');
        }
        else {
            $error_message['error'] = 'This token is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }

    public function fetchIOSToken($posted_data)
    {
        if ( !empty($posted_data) ) {
            $fcm_authorization_key  = "AAAA1x62L-A:APA91bHPEZuPTTVn8tWhggUur4h2_k92s4cRWIu5L9lkRgS2pHtYJKMgCIkg4UcIMui1lWcXRGStyKxjIgrlH7KXefS0CkSS8tlrR0yDWiNRUkeYsNuivIgnV2rgep6QCmQL75-QpBTd";

            $data = array(
                'application'       => 'com.3bids.app',
                'sandbox'           => true,
                'apns_tokens'       => array(
                    $posted_data['device_token']
                )
            );
            
            $headers	= array(
                'Authorization: key='.$fcm_authorization_key,
                'Content-Type: application/json',
            );

            #Send Reponse To FireBase Server
            $ch	= curl_init();

            curl_setopt( $ch,CURLOPT_URL, 'https://iid.googleapis.com/iid/v1:batchImport' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($data) );

            $response = curl_exec($ch);
            if($response === false)
                die('Curl failed ' . curl_error());

            curl_close($ch);

            $response = json_decode($response, true);
            if (!empty($response)) {
                if ( isset($response['results'][0]['registration_token']) ) {

                    if ( $response['results'][0]['status'] == 'OK' )
                        $data['device_token'] = $response['results'][0]['registration_token'];
                    else 
                        $data['device_token'] = 'NULL';
                }
            }
            // ob_flush();

            return $data;
        }
    }
}