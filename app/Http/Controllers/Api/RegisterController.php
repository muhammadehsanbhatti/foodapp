<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use MikeMcLin\WpPassword\Facades\WpPassword;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use App\Models\User;
use App\Models\UserEducationalInformation;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function user_address(Request $request)
    {
        $requested_data = array();
        $posted_data = $request->all();

        $rules = array(
            'user_id'  => 'exists:users,id',
            'country' => 'required',
            'city' => 'required',
            'address_type' => 'required',
            'address' => 'required',
        );
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        
        $requested_data['user_id'] = \Auth::user()->id;
        foreach ($posted_data['country'] as $key => $value) {
            $requested_data['country'] = $value;
            $requested_data['city'] = $posted_data['city'][$key];
            $requested_data['address_type'] = $posted_data['address_type'][$key];
            $requested_data['address'] = $posted_data['address'][$key];
            
            $data[] = $this->UserAddressObj->saveUpdateUserAddress($requested_data);
        }
        return $this->sendResponse($data, 'User Address added Successfully');
    }

    public function update_user_address(Request $request,$id){
        {
            $posted_data = $request->all();
            $posted_data['update_id'] =  $id;
            $rules = array(
                'user_id'  => 'exists:users,id',
                'country' => 'required',
                'city' => 'required',
                'address_type' => 'required',
                'address' => 'required',
            );
            $validator = \Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
            }
            $get_data = $this->UserAddressObj->getUserAddress([
                'user_id' => \Auth::user()->id,
                'id' => $id,
                'detail' => true
            ]);
            if ($get_data) {
                $posted_data['user_id'] = \Auth::user()->id;
                $data = $this->UserAddressObj->saveUpdateUserAddress($posted_data);
                return $this->sendResponse($data, 'User Address updated Successfully');
            }
            else{
                return $this->sendError(["error" => "Something went wrong"]);
            }
        }
    }
    public function useraddress(){
        $get_data = $this->UserAddressObj->getUserAddress([
            'user_id' => \Auth::user()->id,
        ]);
        if (!$get_data) {
            return $this->sendError(["error" => "Please first add your address"]);
        }
        return $this->sendResponse($get_data, 'Your address');
    }

    public function delete_user_address($id)
    {
        $get_data = $this->UserAddressObj->getUserAddress([
            'user_id' => \Auth::user()->id,
            'id' => $id,
            'detail' => true
        ]);
        if ($get_data) {
            $this->UserAddressObj->deleteUserAddress($id);
            return $this->sendResponse('Success', 'Your Address deleted successfully');
        }
        else{
            return $this->sendResponse('error', 'Something went wrong');
        }
    }
    

    public function login_user(Request $request)
    {
        $user_data = array();
        $posted_data = $request->all();

        $rules = array(
            'email'  => 'required|email:rfc,dns|exists:users,email',
            'password' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $user = $this->UserObj->getUser([
            'detail'=>true,
            'email' => $posted_data['email']
        ]);
        if (WpPassword::check($posted_data['password'], $user->password)) {
            $user_data['email'] = $posted_data['email'];
            $user_data['detail'] = true;
            $user_data = $this->UserObj->getUser($user_data);
            \Auth::login($user_data);
            $user_data['token'] =  $user_data->createToken('MyApp')->accessToken;
            return $this->sendResponse($user_data, 'User Login Successfully');
        }
      else{
        return $this->sendError("error", "Credentials does not match");
      }
        
       
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register_user(Request $request)
    {
      
        $requested_data = $request->all();

        $rules = array(
                   
            'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
            'last_name' => 'required||regex:/^[a-zA-Z ]+$/u',
            'phone_number'  => 'required|unique:users,phone_number',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'business_name' => 'required|unique:businesses,business_name',
            'user_login_status' => 'required|in:admin,customer',
            'business_type' => 'required',
            'restaurant_address' => 'required',
            'cuisine_id' => 'required|exists:business_cuisines,id',
            'password' => 'required|min:6',
            'confirm_password' => 'required|required_with:password|same:password'
        );
        if ($requested_data['user_login_status'] == 'customer') {
            $rules = array(
                   
                'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                'last_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                'phone_number'  => 'required|unique:users,phone_number',
                'email' => 'required|email||regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/u|unique:users,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|required_with:password|same:password'
            );
        }
        if ($requested_data['user_login_status'] == 'rider') {
            $rules = array(
                   
                'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                'last_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                // 'dob' => 'required|date|before:today',
                'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                'gender' => 'required|in:Male,Female,Other',
                'phone_number'  => 'required|unique:users,phone_number',
                'email' => 'required|email||regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/u|unique:users,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|required_with:password|same:password'
            );
        }
        $validator = \Validator::make($requested_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        } 

        if ($request->file('profile_image')) {
            $extension = $request->profile_image->getClientOriginalExtension();
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {

                // if (!empty(\Auth::user()->profile_image) && \Auth::user()->role != 1) {
                //     $url = $base_url.'/'.\Auth::user()->profile_image;
                //     if (file_exists($url)) {
                //         unlink($url);
                //     }
                // }

                // $file_name = time().'_'.$request->profile_image->getClientOriginalName();
                $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
                $requested_data['profile_image'] = 'storage/profile_image/' . $file_name;
            } else {
                $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
                return $this->sendError($error_message['error'], $error_message);
            }
        }

        $data = $this->UserObj->saveUpdateUser($requested_data);
        if (isset($data) && $data['user_login_status'] == 'admin') {
        $this->BusinessObj->saveUpdateBusiness([
            'user_id' => $data->id,
            'business_name' => $requested_data['business_name'],
            'business_type' => $requested_data['business_type'],
            'restaurant_address' => $requested_data['restaurant_address'],
            'cuisine_id' => $requested_data['cuisine_id'],
        ]);
        }
      
        $data = $this->UserObj->getUser([
            'id' => $data->id,
            'detail' => true
        ]);
        // \Auth::login($user);
        // $user['token'] =  $user->createToken('MyApp')->accessToken;
       
        return $this->sendResponse($data, 'User information added successfully.');
        
    }

    public function edit_profile(Request $request){
        $requested_data = $request->all();
        $requested_data['update_id'] = \Auth::user()->id;

        $rules = array(
                   
            'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
            'last_name' => 'required||regex:/^[a-zA-Z ]+$/u',
            'phone_number'  => 'required|unique:users,phone_number,'.\Auth::user()->id,
            'email' => 'required|email:rfc,dns|unique:users,email,'.\Auth::user()->id,
        );
        
        $validator = \Validator::make($requested_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        } 

        if ($request->file('profile_image')) {
            $extension = $request->profile_image->getClientOriginalExtension();
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {

                if (!empty(\Auth::user()->profile_image)) {
                    
                    $base_url = public_path();
                    $url = $base_url.'/'.\Auth::user()->profile_image;
                    if (file_exists($url)) {
                        unlink($url);
                    }
                }

                // $file_name = time().'_'.$request->profile_image->getClientOriginalName();
                $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
                $requested_data['profile_image'] = 'storage/profile_image/' . $file_name;
            } else {
                $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
                return $this->sendError($error_message['error'], $error_message);
            }
        }

        $data = $this->UserObj->saveUpdateUser($requested_data);
        return $this->sendResponse($data, 'User information updated successfully.');
        
    }

    public function register_user_backup(Request $request)
    {
        /*
        id, first_name, last_name, full_name, email, password, user_type, dob, location, country, city, state, latitude, longitude, profile_image, phone_number, user_status, register_from, last_seen, email_verified_at, time_spent, theme_mode, remember_token, created_at, updated_at
        */

        $requested_data = $request->all();
        $rules = array(
            'full_name'         => 'required',
            'email'             => $requested_data['register_from'] != 1 ? 'required|email' : 'required|email|unique:users',
            'user_type'         => 'required|in:1,2,3,4',
            'dob'               => 'nullable|date_format:Y-m-d',
            'location'          => 'nullable|min:4',
            'country'           => 'nullable|min:4',
            'city'              => 'nullable|min:4',
            'state'             => 'nullable|min:4',
            'latitude'          => 'nullable|min:4',
            'longitude'         => 'nullable|min:4',
            'profile_image'     => 'nullable',
            'phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'user_status'       => 'nullable|in:1,2',
            'register_from'     => 'required|in:1,2,3,4',
            'password'          => $requested_data['register_from'] == 'app' ?
                [
                    'required', Password::min(8)
                    // ->numbers()
                    // ->letters()
                    // ->mixedCase()
                    // ->symbols()
                    // ->uncompromised()
                ] : 'nullable',
            'confirm_password'  => 'required_with:password|same:password',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        } else {
            // $register_data = array();
            // $documents_arr = array();

            $name_arr = explode(' ', $requested_data['full_name']);
            $global_data = array();
            $global_data['first_name'] = isset($name_arr[0]) ? ucfirst($name_arr[0]) : NULL;
            $global_data['last_name'] = isset($name_arr[1]) ? ucfirst($name_arr[1]) : NULL;

            if (!filter_var($requested_data['email'], FILTER_VALIDATE_EMAIL)) {
                $error_message['error'] = 'Please eneter a valid email address.';
                return $this->sendError($error_message['error'], $error_message);
            }

            if ($requested_data['register_from'] != 1) {

                $posted_data = array();
                $posted_data['email'] = $requested_data['email'];
                $posted_data['detail'] = true;
                $user_details = User::getUser($posted_data);

                if (isset($user_details['id'])) {
                    $user = User::where('email', $requested_data['email'])->first();
                    User::saveUpdateUser(['update_id' => $user_details['id'], 'register_from' => $requested_data['register_from']]);
                    if (Auth::loginUsingId($user->id)) {
                        $user = Auth::user();
                        $response =  $user;
                        $response['token'] =  $user->createToken('MyApp')->accessToken;
                    } else {
                        $response = false;
                    }

                    if ($response)
                        return $this->sendResponse($response, 'User login successfully.');
                    else {
                        $error_message['error'] = 'This email has already been registered.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                } else {
                    $global_data['password'] = '12345678@w';
                    $global_data['email_verified_at'] = date('Y-m-d h:i:s');
                }
            }

            $user_data = array();
            $user_data['first_name'] = $global_data['first_name'];
            $user_data['last_name'] = $global_data['last_name'];
            $user_data['full_name'] = $requested_data['full_name'];
            $user_data['email'] = $requested_data['email'];

            if (isset($global_data['password']) && $global_data['password'])
                $user_data['password'] = $global_data['password'];
            else
                $user_data['password'] = $requested_data['password'];

            $user_data['user_type'] = $requested_data['user_type'];
            $user_data['dob'] = $requested_data['dob'];
            $user_data['location'] = isset($requested_data['location']) ? $requested_data['location'] : NULL;
            $user_data['country'] = isset($requested_data['country']) ? $requested_data['country'] : NULL;
            $user_data['city'] = isset($requested_data['city']) ? $requested_data['city'] : NULL;
            $user_data['state'] = isset($requested_data['state']) ? $requested_data['state'] : NULL;
            $user_data['latitude'] = isset($requested_data['latitude']) ? $requested_data['latitude'] : NULL;
            $user_data['longitude'] = isset($requested_data['longitude']) ? $requested_data['longitude'] : NULL;
            $user_data['profile_image'] = isset($requested_data['profile_image']) ? $requested_data['profile_image'] : NULL;
            $user_data['phone_number'] = isset($requested_data['phone_number']) ? $requested_data['phone_number'] : NULL;
            $user_data['user_status'] = isset($requested_data['user_status']) ? $requested_data['user_status'] : NULL;
            $user_data['register_from'] = isset($requested_data['register_from']) ? $requested_data['register_from'] : NULL;
            $user_data['last_seen'] = isset($requested_data['last_seen']) ? $requested_data['last_seen'] : NULL;

            if (isset($global_data['email_verified_at']) && $global_data['email_verified_at'])
                $user_data['email_verified_at'] = $global_data['email_verified_at'];

            if (isset($global_data['email_verified_at']) && $global_data['email_verified_at'])
                $user_data['remember_token'] = NULL;
            else
                $user_data['remember_token'] = generateRandomNumbers(4);

            $user_id = User::saveUpdateUser($user_data);

            if ($user_id) {
                $response = $this->authorizeUser([
                    'email' => $user_data['email'],
                    'password' => isset($user_data['password']) ? $user_data['password'] : '12345678@w'
                ]);

                // $notification_text = "A new user has been register into the app.";

                // $notification_params = array();
                // $notification_params['sender'] = $user_id->id;
                // $notification_params['receiver'] = 1;
                // $notification_params['slugs'] = "new-user";
                // $notification_params['notification_text'] = $notification_text;
                // $notification_params['metadata'] = "user_id=$user_id";

                // $notif_response = Notification::saveUpdateNotification([
                //     'sender' => $notification_params['sender'],
                //     'receiver' => $notification_params['receiver'],
                //     'slugs' => $notification_params['slugs'],
                //     'notification_text' => $notification_params['notification_text'],
                //     'metadata' => $notification_params['metadata']
                // ]);

                // $firebase_devices = FCM_Token::getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
                // $notification_params['registration_ids'] = array_column($firebase_devices, 'device_token');

                // if ($notif_response) {

                //     $notification = FCM_Token::sendFCM_Notification([
                //         'title' => $notification_params['slugs'],
                //         'body' => $notification_params['notification_text'],
                //         'metadata' => $notification_params['metadata'],
                //         'registration_ids' => $notification_params['registration_ids'],
                //         'details' => []
                //     ]);
                // }

                // $admin_data = User::getUser(['id' => 1, 'without_with' => true, 'detail' => true]);

                // // this email will send to the admin to notify about newly registered user
                // $email_content = EmailTemplate::getEmailMessage(['id' => 2, 'detail' => true]);

                // $email_data = decodeShortCodesTemplate([
                //     'subject' => $email_content->subject,
                //     'body' => $email_content->body,
                //     'email_message_id' => 2,
                //     'sender_id' => $user_id->id,
                //     'receiver_id' => $admin_data->id,
                // ]);

                // // here sender is the customer and receiver is the supplier
                // EmailLogs::saveUpdateEmailLogs([
                //     'email_msg_id' => 2,
                //     'sender_id' => $user_id->id,
                //     'receiver_id' => $admin_data->id,
                //     'email' => $admin_data->email,
                //     'subject' => $email_data['email_subject'],
                //     'email_message' => $email_data['email_body'],
                //     'send_email_after' => 1, // 1 = Daily Email
                // ]);


                // // this email will send to the user who has successfully registered with social apps
                // $email_content = EmailTemplate::getEmailMessage(['id' => 5, 'detail' => true]);

                // $email_data = decodeShortCodesTemplate([
                //     'subject' => $email_content->subject,
                //     'body' => $email_content->body,
                //     'email_message_id' => 5,
                //     'user_id' => $user_id->id,
                // ]);

                // // here sender is the customer and receiver is the supplier
                // EmailLogs::saveUpdateEmailLogs([
                //     'email_msg_id' => 5,
                //     'sender_id' => $admin_data->id,
                //     'receiver_id' => $user_id->id,
                //     'email' => $user_id->email,
                //     'subject' => $email_data['email_subject'],
                //     'email_message' => $email_data['email_body'],
                //     'send_email_after' => 1, // 1 = Daily Email
                // ]);

                $data = [
                    'subject' => 'Welcome to the App',
                    'email_mode' => 'welcome_mail',
                    'name' => $requested_data['full_name'],
                    'email' => $requested_data['email'],
                    'token' => '',
                ];

                Mail::send('emails.generic_template', ['email_data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->subject($data['subject']);
                });

                if (!(isset($global_data['email_verified_at']) && $global_data['email_verified_at'])) {
                    $data = [
                        'subject' => 'Account Verification',
                        'email_mode' => 'send_otp',
                        'name' => $requested_data['full_name'],
                        'email' => $requested_data['email'],
                        'otp_token' => $user_data['remember_token'],
                        'token' => '',
                    ];

                    Mail::send('emails.generic_template', ['email_data' => $data], function ($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });
                }

                if ($response)
                    return $this->sendResponse($response, 'User successfully registered. OTP also sent to your email.');
                else {
                    $error_message['error'] = 'The user credentials are not valid.';
                    return $this->sendError($error_message['error'], $error_message);
                }
            }



            // if( $requested_data['role'] != 2 && $requested_data['role'] != 3 && $requested_data['role'] != 4 ){
            //     $error_message['error'] = 'You entered the invalid role.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            if (isset($request->company_documents)) {
                $allowedfileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
                foreach ($request->company_documents as $mediaFiles) {
                    $extension = strtolower($mediaFiles->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if (!$check) {
                        $error_message['error'] = 'Invalid file format you can only add jpg, jpeg, png and pdf file format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }
            }

            $user_detail = User::saveUpdateUser($requested_data);
            $user_id = $user_detail->id;

            $login_response = $this->authorizeUser([
                'email' => $requested_data['email'],
                'password' => isset($requested_data['password']) ? $requested_data['password'] : '12345678@w'
            ]);

            $message = ($user_id) > 0 ? 'User is successfully registered.' : 'Something went wrong during registration.';
            if ($user_id) {

                if ($requested_data['role'] == 3 || $requested_data['role'] == 2) {
                    $address_arr['user_id'] = $user_id;
                    $address_arr['title'] = $requested_data['title'];
                    $address_arr['address'] = $requested_data['address'];
                    $address_arr['country'] = $requested_data['country'];
                    $address_arr['city'] = isset($requested_data['city']) ? $requested_data['city'] : NULL;
                    $address_arr['state'] = isset($requested_data['state']) ? $requested_data['state'] : NULL;
                    $address_arr['code'] = isset($requested_data['code']) ? $requested_data['code'] : NULL;
                    $address_arr['iso_code'] = isset($requested_data['iso_code']) ? $requested_data['iso_code'] : NULL;
                    $address_arr['postal_code'] = $requested_data['postal_code'];
                    $data = UserMultipleAddresse::saveUpdateUserMultipleAddresse($address_arr);
                }

                if (isset($request->company_documents)) {
                    $allowedfileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
                    foreach ($request->company_documents as $mediaFiles) {

                        $extension = strtolower($mediaFiles->getClientOriginalExtension());

                        $check = in_array($extension, $allowedfileExtension);
                        if ($check) {

                            $response = upload_files_to_storage($request, $mediaFiles, 'other_assets');

                            if (isset($response['action']) && $response['action'] == true) {
                                $arr = [];
                                $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                                $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                            }

                            $asset_id = UserAssets::saveUpdateUserAssets([
                                'user_id'       => $user_id,
                                'asset_type'    => 1,
                                'filepath'      => $arr['file_path'],
                                'filename'      => $arr['file_name'],
                                'mimetypes'     => $mediaFiles->getClientMimeType(),
                                'asset_status'  => 0,
                                'asset_view'    => 0,
                            ]);

                            $arr['asset_id'] = $asset_id;
                            $documents_arr[] = $arr;
                        } else {
                            $error_message['error'] = 'Invalid file format you can only add jpg, jpeg, png and pdf file format.';
                            return $this->sendError($error_message['error'], $error_message);
                        }
                    }
                }
                $user_detail = User::getUser([
                    'id'       => $user_id,
                    'detail'       => true
                ]);

                $notification_text = "A new user has been register into the app.";

                $notification_params = array();
                $notification_params['sender'] = $user_id;
                $notification_params['receiver'] = 1;
                $notification_params['slugs'] = "new-user";
                $notification_params['notification_text'] = $notification_text;
                $notification_params['metadata'] = "user_id=$user_id";

                $response = Notification::saveUpdateNotification([
                    'sender' => $notification_params['sender'],
                    'receiver' => $notification_params['receiver'],
                    'slugs' => $notification_params['slugs'],
                    'notification_text' => $notification_params['notification_text'],
                    'metadata' => $notification_params['metadata']
                ]);

                $firebase_devices = FCM_Token::getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
                $notification_params['registration_ids'] = array_column($firebase_devices, 'device_token');

                if ($response) {

                    if (isset($model_response['user']))
                        unset($model_response['user']);
                    if (isset($model_response['post']))
                        unset($model_response['post']);

                    $notification = FCM_Token::sendFCM_Notification([
                        'title' => $notification_params['slugs'],
                        'body' => $notification_params['notification_text'],
                        'metadata' => $notification_params['metadata'],
                        'registration_ids' => $notification_params['registration_ids'],
                        'details' => $user_detail
                    ]);
                }

                /*
                $data = [
                    'subject' => 'Email Verification',
                    'name' => $request->get('full_name'),
                    'email' => $request->get('email'),
                    'token' => $token,
                ];
                */

                $admin_data['id'] = 1;
                $admin_data['detail'] = true;
                $response = User::getUser($admin_data);

                // this email will sent to the newly registered user via mobile app
                $email_content = EmailTemplate::getEmailMessage(['id' => 6, 'detail' => true]);

                $email_data = decodeShortCodesTemplate([
                    'subject' => $email_content->subject,
                    'body' => $email_content->body,
                    'email_message_id' => 6,
                    'user_id' => $user_id,
                    'email_verification_url' => $token,
                ]);

                EmailLogs::saveUpdateEmailLogs([
                    'email_msg_id' => 6,
                    'sender_id' => $response->id,
                    'receiver_id' => $user_id,
                    'email' => $request->get('email'),
                    'subject' => $email_data['email_subject'],
                    'email_message' => $email_data['email_body'],
                    'send_email_after' => 1, // 1 = Daily Email
                ]);

                /*
                Mail::send('emails.welcome_email', ['email_data' => $data], function($message) use ($data) {
                    $message->to($data['email'])
                            ->subject($data['subject']);
                });
                */

                if ($response) {

                    /*
                    $data = [
                        'subject' => 'New User Registered',
                        'name' => $response->name,
                        'email' => $response->email,
                        'text_line' => "A new user ".$request->get('full_name')." has been registered on ".config('app.name'),
                    ];
                    */

                    // this email will sent to the admin on new user registeration
                    $email_content = EmailTemplate::getEmailMessage(['id' => 2, 'detail' => true]);

                    $email_data = decodeShortCodesTemplate([
                        'subject' => $email_content->subject,
                        'body' => $email_content->body,
                        'email_message_id' => 2,
                        'sender_id' => $user_id,
                        'receiver_id' => $response->id,
                    ]);

                    EmailLogs::saveUpdateEmailLogs([
                        'email_msg_id' => 2,
                        'sender_id' => $user_id,
                        'receiver_id' => $response->id,
                        'email' => $response->email,
                        'subject' => $email_data['email_subject'],
                        'email_message' => $email_data['email_body'],
                        'send_email_after' => 1, // 1 = Daily Email
                    ]);

                    /*
                    Mail::send('emails.general_email', ['email_data' => $data], function($message) use ($data) {
                        $message->to($data['email'])
                                ->subject($data['subject']);
                    });
                    */
                }

                $user_detail['token'] = isset($login_response['token']) ? $login_response['token'] : '';
                return $this->sendResponse($user_detail, $message);
            } else {
                $error_message['error'] = $message;
                return $this->sendError($error_message['error'], $error_message);
            }
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login_user_old(Request $request)
    {
        $user_data = array();
        $posted_data = $request->all();

        $rules = array(
            'phone_number'      => 'required|exists:users,phone_number',
            // 'password'          => $posted_data['source'] != 1 ? 'nullable' : 'required',
            // 'source'            => 'required|integer|in:1,2,3,4',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        if ($posted_data['source'] != 1) {
            $user_data['phone_number'] = $posted_data['phone_number'];
            $user_data['detail'] = true;
            $user_data = $this->UserObj->getUser($user_data);

            if (isset($user_data->id) && isset($user_data->register_from) && $user_data->register_from != 1) {
                $response = $this->authorizeUser([
                    'phone_number' => $posted_data['phone_number'],
                    'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                ]);

                if ($response) {
                    return $this->sendResponse($response, 'User login successfully.');
                } else {
                    $error_message['error'] = 'Unauthorised';
                    return $this->sendError($error_message['error'], $error_message);
                }
            } else {

                // $user_data = array();
                // $user_data['phone_number'] = $posted_data['phone_number'];
                // $user_data['role'] = 2;
                // $user_data['account_status'] = 1;
                // $user_data['password'] = '12345678@w';

                // if ( isset($posted_data['facebook_id']) && !isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 2; //facebook;
                // if ( !isset($posted_data['facebook_id']) && isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 3; //google;

                // $user_detail = $this->UserObj->saveUpdateUser($user_data);
                // $user_id = $user_detail->id;
                // if ($user_id) {
                //     $response = $this->authorizeUser([
                //         'phone_number' => $posted_data['phone_number'],
                //         'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                //     ]);

                //     if ($response){
                //         return $this->sendResponse($response, 'User login successfully.');
                //     }
                //     else{
                //         $error_message['error'] = 'Unauthorised';
                //         return $this->sendError($error_message['error'], $error_message);
                //     }
                // }
            }
        } else if ($posted_data['source'] == 1) {

            $response = $this->authorizeUser([
                'phone_number' => isset($posted_data['phone_number']) ? $posted_data['phone_number'] : '12345678901',
                'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
            ]);

            if ($response) {
                return $this->sendResponse($response, 'User login successfully.');
            } else {
                $error_message['error'] = 'Please enter correct phone_number and password.';
                return $this->sendError($error_message['error'], $error_message);
            }
        } else {

            // if( (!isset($posted_data['phone_number']) || empty($posted_data['phone_number'])) ){
            //     $error_message['error'] = 'The phone_number address is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // if( (!isset($posted_data['password']) || empty($posted_data['password'])) ){
            //     $error_message['error'] = 'The password is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // $error_message['error'] = 'Please post the valid credentials for login.';
            // return $this->sendError($error_message['error'], $error_message);
        }
    }

    public function login_user_backup(Request $request)
    {
        $user_data = array();
        $posted_data = $request->all();

        $rules = array(
            'email'             => 'required|email',
            'password'          => $posted_data['source'] != 1 ? 'nullable' : 'required',
            'source'            => 'required|in:1,2,3,4',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        if ($posted_data['source'] != 1) {
            $user_data['email'] = $posted_data['email'];
            $user_data['detail'] = true;
            $user_data = $this->UserObj->getUser($user_data);

            if (isset($user_data->id) && isset($user_data->register_from) && $user_data->register_from != 1) {
                $response = $this->authorizeUser([
                    'email' => $posted_data['email'],
                    'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                ]);

                if ($response) {
                    return $this->sendResponse($response, 'User login successfully.');
                } else {
                    $error_message['error'] = 'Unauthorised';
                    return $this->sendError($error_message['error'], $error_message);
                }
            } else {

                // $user_data = array();
                // $user_data['email'] = $posted_data['email'];
                // $user_data['role'] = 2;
                // $user_data['account_status'] = 1;
                // $user_data['password'] = '12345678@w';

                // if ( isset($posted_data['facebook_id']) && !isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 2; //facebook;
                // if ( !isset($posted_data['facebook_id']) && isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 3; //google;

                // $user_detail = $this->UserObj->saveUpdateUser($user_data);
                // $user_id = $user_detail->id;
                // if ($user_id) {
                //     $response = $this->authorizeUser([
                //         'email' => $posted_data['email'],
                //         'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                //     ]);

                //     if ($response){
                //         return $this->sendResponse($response, 'User login successfully.');
                //     }
                //     else{
                //         $error_message['error'] = 'Unauthorised';
                //         return $this->sendError($error_message['error'], $error_message);
                //     }
                // }
            }
        } else if ($posted_data['source'] == 1) {

            $response = $this->authorizeUser([
                'email' => isset($posted_data['email']) ? $posted_data['email'] : 'xyz@admin.com',
                'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
            ]);

            if ($response) {
                return $this->sendResponse($response, 'User login successfully.');
            } else {
                $error_message['error'] = 'Please enter correct email and password.';
                return $this->sendError($error_message['error'], $error_message);
            }
        } else {

            // if( (!isset($posted_data['email']) || empty($posted_data['email'])) ){
            //     $error_message['error'] = 'The email address is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // if( (!isset($posted_data['password']) || empty($posted_data['password'])) ){
            //     $error_message['error'] = 'The password is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // $error_message['error'] = 'Please post the valid credentials for login.';
            // return $this->sendError($error_message['error'], $error_message);
        }
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function authorizeUser($posted_data)
    {
        $email = isset($posted_data['email']) ? $posted_data['email'] : '';
        $password = isset($posted_data['password']) ? $posted_data['password'] : '';

        if (\Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = \Auth::user();
            $response =  $user;

            if (isset($posted_data['mode']) && $posted_data['mode'] == 'only_validate') {
                return $response;
            }

            $response['token'] =  $user->createToken('MyApp')->accessToken;
            return $response;
        } else {
            return false;
        }
    }

    public function verifyUserEmail($token)
    {

        $where_query = array(['remember_token', '=', isset($token) ? $token : 0]);
        $verifyUser = User::where($where_query)->first();

        $email_data = [
            'name' => isset($verifyUser->name) ? $verifyUser->name : 'Dear User',
            'text_line' => 'This verfication code is invalid. Please contact to the customer support',
        ];

        if ($verifyUser) {
            if ($verifyUser->email_verified_at == NULL) {

                $model_response = User::saveUpdateUser([
                    'update_id' => $verifyUser->id,
                    'remember_token' => NULL,
                    'email_verified_at' => date('Y-m-d h:i:s')
                ]);

                if (!empty($model_response)) {
                    $email_data = [
                        'name' => $verifyUser->name,
                        'text_line' => 'Congratulations! You email is successfully verified. Welcome to ' . config('app.name'),
                    ];
                }
            } else {
                $email_data = [
                    'name' => $verifyUser->name,
                    'text_line' => 'Your email is already verified. Welcome to ' . config('app.name'),
                ];
            }
        }
        return view('emails.general_email', compact('email_data'));
    }

    // public function forgotPassword(Request $request)
    // {
    //     $rules = array(
    //         // 'email' => 'required|email|unique:users',
    //         'email' => 'required|email',
    //     );
    //     $validator = \Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         $error = $validator->errors()->first();
    //         return $this->sendError($error);
    //     } else {

    //         $users = $this->UserObj::where('email', '=', $request->input('email'))->first();
    //         if ($users === null) {
    //             // echo 'User does not exist';
    //             return $this->sendError('We do not recognize this email address. Please try again.');

    //             // \Session::flash('We do not recognize this email address. Please try again.');
    //             // return redirect('/forgot-password');
    //             // return redirect('/login');
    //         } else {
    //             // echo 'User exits';
    //             $random_hash = substr(md5(uniqid(rand(), true)), 10, 10); 
    //             $email = $request->get('email');
    //             $password = Hash::make($random_hash);

    //             // $posted_data['email'] = $email;
    //             // $posted_data['password'] = $password;
    //             // $this->UserObj->saveUpdateUser($posted_data);

    //             \DB::update('update users set password = ? where email = ?',[$password,$email]);

    //             $data = [
    //                 'new_password' => $random_hash,
    //                 'subject' => 'Reset Password',
    //                 'email' => $email,
    //                 'email_template' => 'emails.reset_password',
    //                 'body' => 'emails.reset_password'
    //             ];
    //             validateAndSendEmail($data);
    //             return $this->sendResponse($data['email'], 'Your password has been reset. Please check your email!');

    //             // \Session::flash('message', 'Your password has been reset. Please check your email!');
    //             // return redirect('/login');
    //         }

    //     }

        
    // }

    public function forgotPassword(Request $request)
    {
        $request_data = $request->all();
        $rules = array(
            'email' => 'required|email|exists:users'
        );

        $messages = array(
            'email.exists' => 'We do not recognize this email address. Please try again.',
        );

        $validator = \Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());     
        } else {

        
            $otp = substr(md5(uniqid(rand(), true)), 5, 5); 
            $userDetail = $this->UserObj->getUser([
                'email' => $request_data['email'],
                'detail' => true
            ]);
            
            if($userDetail){
                $response = $this->UserObj->saveUpdateUser([
                    'update_id' => $userDetail->id,
                    'email_verification_code' => $otp,
                ]);
                if($response){
                    saveEmailLog([
                        'user_id' => $response->id,
                        'email_template_id' => 6, //OTP Verification
                        'otp_code' =>$otp
                    ]);
                    return $this->sendResponse($otp, 'Your password has been reset. Please check your email.');
                }
            }

            // if($userDetail){
            //     $response = $this->UserObj->saveUpdateUser([
            //         'update_id' => $userDetail->id,
            //         'password' => $password
            //     ]);
            //     if($response){
            //         saveEmailLog([
            //             'user_id' => $response->id,
            //             'email_template_id' => 5, //email forgot password
            //             'new_password' => $password
            //         ]);
            //         return $this->sendResponse($password, 'Your password has been reset. Please check your email.');
            //     }
            // }


            return $this->sendResponse([], 'We do not recognize this email address. Please try again.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request_data = $request->all();
        $rules = array(
            'email_verification_code' => 'required',
            'new_password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@^&*()_+]).*$/',
            'confirm_password'  => 'required_with:new_password|same:new_password',
        );

        $messages = array(
            'new_password.regex' => 'Password must be atleast eight characters long and must use atleast one letter, number and special character.',
        );

        $validator = \Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());     
        } else {

            $userDetail = $this->UserObj->getUser([
                'email_verification_code' => $request_data['email_verification_code'],
                'detail' => true
            ]);
            
            if($userDetail){
                $response = $this->UserObj->saveUpdateUser([
                    'update_id' => $userDetail->id,
                    'password' => $request_data['new_password'],
                    'email_verification_code' => 'NULL'
                ]);
                if($response){
                    saveEmailLog([
                        'user_id' => $response->id,
                        'email_template_id' => 5, //email forgot password
                        'new_password' => $request_data['new_password']
                    ]);
                    return $this->sendResponse($request_data['email_verification_code'], 'Your password has been reset. Please check your email.');
                }
            }
            return $this->sendResponse([], 'We do not recognize this email address. Please try again.');
        }
    }

    
    public function changePassword(Request $request)
    {
        $requested_data = $request->all();
        $rules = array(
            'email'             => 'required|email|exists:users',
            'old_password'      => 'required',
            'new_password'      => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@^&*()_+]).*$/',
            'confirm_password'  => 'required|required_with:new_password|same:new_password'
        );

        $messages = array(
            'new_password.regex' => 'Password must be atleast eight characters long and must use atleast one letter, number and special character.',
        );
        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors()); 
        }

        $response = $this->authorizeUser([
            'email' => $requested_data['email'],
            'password' => $requested_data['old_password'],
            'mode' => 'only_validate',
        ]);

        if (!$response) {
            return $this->sendError('Your old password is incorrect.');
        }
        else {

            if ($requested_data['old_password'] == $requested_data['new_password']) {
                return $this->sendError('New and old password must be different.');
            }

            $this->UserObj->saveUpdateUser([
                'update_id' => $response->id,
                'password' => $requested_data['new_password']
            ]);

            saveEmailLog([
                'user_id' => $response->id,
                'email_template_id' => 2, //email changed password
                'new_password' => $requested_data['new_password']
            ]);
            
            return $this->sendResponse([], 'Your password has been changed successfully.');
        }
    }


    // public function changePassword(Request $request)
    // {
    //     $params = $request->all();
    //     $rules = array(
    //         'email'             => 'required|email:rfc,dns|email',
    //         'old_password'      => 'required',
    //         // 'new_password'      => 'required|min:4',
    //         'new_password'      => [
    //             'required', Password::min(8)
    //                 // ->letters()
    //                 // ->mixedCase()
    //                 // ->numbers()
    //                 // ->symbols()
    //                 // ->uncompromised()
    //         ],
    //         'confirm_password'  => 'required|required_with:new_password|same:new_password'
    //     );
    //     $validator = \Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
    //     }

    //     $response = $this->authorizeUser([
    //         'email' => $params['email'],
    //         'password' => $params['old_password'],
    //         'mode' => 'only_validate',
    //     ]);

    //     if ($params['old_password'] == $params['new_password']) {
    //         $error_message['error'] = 'New and old password must be different.';
    //         return $this->sendError($error_message['error'], $error_message);
    //     }

    //     if (!$response) {
    //         $error_message['error'] = 'Your old password is incorrect.';
    //         return $this->sendError($error_message['error'], $error_message);
    //     } else {
    //         $new_password = $params['confirm_password'];
    //         $email = $request->get('email');
    //         $password = Hash::make($new_password);

    //         \DB::update('update users set password = ? where email = ?', [$password, $email]);

    //         // $data = [
    //         //     'new_password' => $new_password,
    //         //     'subject' => 'Reset Password',
    //         //     'email' => $email
    //         // ];

    //         $admin['id'] = 1;
    //         $admin['detail'] = true;
    //         $admin_data = $this->UserObj->getUser($admin);
    //         return $this->sendResponse([], 'Your password has been updated.');
    //     }
    // }

    public function logoutUser(Request $request)
    {
        if (!empty(\Auth::user())) {
            $user = \Auth::user()->token();
            $user->revoke();
        }
        return $this->sendResponse([], 'User is successfully logout.');
    }

    public function get_profile(Request $request)
    {
        if (!empty(\Auth::user())) {

            $posted_data = array();
            $posted_data['id'] = \Auth::user()->id;
            $posted_data['detail'] = true;
            $user = User::getUser($posted_data);
            return $this->sendResponse($user, 'User profile is successfully loaded.');
        } else {
            $error_message['error'] = 'Please login to get profile data.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }
}
