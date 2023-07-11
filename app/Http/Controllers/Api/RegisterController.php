<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use MikeMcLin\WpPassword\Facades\WpPassword;
use Validator;
use DB;
use App\Models\User;
use App\Models\UserEducationalInformation;
use Carbon\Carbon;

class RegisterController extends BaseController
{

//Test User
//     // List of general titles 
//     public function general_titles(Request $request){

//         $posted_data = array();
//         $posted_data['title_status'] = $request->title_status;
//         if (isset($request->title)) {
//             $posted_data['title'] = $request->title;
//         }
//         $posted_data['status'] = 'Published';
//         if (isset($request->title_status) && $request->title_status == 4) {
//             $user_industry_vertical_items = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
//                 'user_id' => \Auth::user()->id,
//                 'intrested_vertical' => 'Industry',
//                 'groupBy' => 'user_industy_vertical_items.general_title_id',
                
//             ]);
//             $general_title_id = $user_industry_vertical_items->ToArray();
//             $general_title_id = array_column($general_title_id, 'general_title_id');
//             $posted_data['general_title_id_in'] = $general_title_id;
//             $data = $this->IndustryVerticalItemObj->getIndustryVerticalItem($posted_data);
//         }
//         else{
//             $data = $this->GeneralObj->getGeneralTitle($posted_data);
//         }
//         return $this->sendResponse($data, 'List of general titles');
//     }
//     // Add of general titles 
//     public function create_general_title(Request $request){
//         $requestd_data = array();
//         $requestd_data = $request->all();
//         $rules = array(
//             'title' => 'required|unique:general_titles,title|regex:/^[a-zA-Z ]+$/u',
//             'title_status' => 'required|integer|between:1,6',
//         );

//         $validator = \Validator::make($request->all(), $rules);

//         if ($validator->fails()) {
//             return $this->sendError(["error" => $validator->errors()->first()]);
//         }
        
//         if (isset($requestd_data['title_status']) && $requestd_data['title_status'] == 4) {
//             $user_industry_vertical_items = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
//                 'user_id' => \Auth::user()->id,
//                 'intrested_vertical' => 'Industry',
//                 'groupBy' => 'user_industy_vertical_items.general_title_id',
                
//             ]);
//             $general_title_id = $user_industry_vertical_items->ToArray();
//             $general_title_id = array_column($general_title_id, 'general_title_id');

//             if(isset($general_title_id) && count($general_title_id)>0){
//                 foreach ($general_title_id as $key => $id) {
//                     // echo '<pre>'; print_r($id); echo '</pre>'; exit;
//                     $general_title[] = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem([
//                         'title' => $requestd_data['title'],
//                         'general_title_id' => $id
//                     ]);
//                 }
//             }
//         }else{
//             $general_title = $this->GeneralObj->saveUpdateGeneralTitle([
//                 'title' => $requestd_data['title'],
//                 'title_status' => $requestd_data['title_status']
//             ]);
//         }

//         return $this->sendResponse($general_title, 'General title added successfully');
//     }

//      // list of goals
//      public function goals(Request $request){

//         $posted_data = array();
//         $posted_data['goal_number'] = $request->goal_number;
//         $goals = $this->GoalObj->getGoal($posted_data);
//         return $this->sendResponse($goals, 'List of goals');
//     }

//     // List of institute name
//     public function educational_info(Request $request){
//         $education_information = $this->UserEducationalInfoObj::where('university_school_name', 'like', '%' . $request['institute_name'] . '%')->groupBy('university_school_name')->pluck('university_school_name');
//         // $education_information = $this->UserEducationalInfoObj->getUserEducationalInformation($posted_data);
//         return $this->sendResponse($education_information, 'List of institue name');
//     }

//     // List of Degrees name
//     public function degree_info(Request $request){
//         $education_information = $this->UserEducationalInfoObj::where('degree_discipline', 'like', '%' . $request['degree_name'] . '%')->groupBy('degree_discipline')->pluck('degree_discipline');
//         return $this->sendResponse($education_information, 'List of Degree name');
//     }

//     public function contact_user_list(Request $request){
//         $posted_data = array();
//         $request_data = $request->all();
//         $posted_data['except_auth_id'] = \Auth::user()->id;
//         if (isset($request_data['name'])) {
//             $posted_data['name'] = $request_data['name'];
//         }
//         if (isset($request_data['phone_numbers'])) {
//             $posted_data['phone_numbers_in'] = $request_data['phone_numbers'];
//         }
//         $posted_data['except_auth_id'] = \Auth::user()->id;
//         $return_ary['matched_connect_peoples'] = $this->UserObj->getUser($posted_data);
        
//         $matched_phone_number = $return_ary['matched_connect_peoples']->ToArray();
//         $matched_phone_number = array_column($matched_phone_number, 'phone_number');

//         $return_ary['not_matched_connect_peoples'] = array();
//         if(isset($request_data['phone_numbers'])){
//         $a1 = $matched_phone_number;
//         $a2 = $request_data['phone_numbers'];
//         $return_ary['not_matched_connect_peoples'] = array_merge(array_diff($a1, $a2),array_diff($a2,$a1));
//         }
// //echo '<pre>';
// //print_r($matched_phone_number);
// //print_r($request_data['phone_numbers']);
// //print_r($return_ary['not_matched_connect_peoples']);
// //echo '</pre>';
        
// //        $not_match_record = $this->UserObj->getUser([
// //           'phone_numbers_not_in'=>$matched_phone_number
// //        ]);
        
// //       $not_matched_phone_number = $not_match_record->ToArray();
// //        $return_ary['not_matched_connect_peoples'] = array_column($not_matched_phone_number, 'phone_number');
//         return $this->sendResponse($return_ary, 'List of users');
//     }

//     // list of connect people And Filters
//     public function connect_people_list(Request $request){
//         $posted_data = array();
//         $request_data = $request->all();
//         $posted_data['paginate'] = 10;
//         $posted_data['user_id'] = \Auth::user()->id;
//         $posted_data['status'] = 'Accept';

//         // status filter
//         if(isset($request_data['status'])){
//             $posted_data['status'] = $request_data['status'];
//         }

//         // connect_type filter
//         if(isset($request_data['connect_type'])){
//             $posted_data['connect_type'] = $request_data['connect_type'];
//         }

//         if($request_data){
//             $posted_data = array_merge($posted_data,$request_data);
//         }
        
//         $connect_peoples = $this->ConnectPeopleObj->getConnectPeople($posted_data);
        
//         $latestConnectUserId = $connect_peoples->ToArray();
//         $latestConnectUserId = array_column($latestConnectUserId['data'], 'connect_user_id');

//         // Professional role filter
//         if(isset($request_data['professional_role'])){
//             $professional_role_type_items = $this->UserProRolteItemObj->getUserProRoleTypeItem([
//                 'general_title_ids' => $request_data['professional_role'],
//                 'user_ids' => $latestConnectUserId
//             ]);
            
//             $latestConnectUserId = $professional_role_type_items->ToArray();
//             $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
//         }

//         // Industry experties filter
//         if(isset($request_data['industry_experties'])){
//             $industries_experties = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
//                 'general_title_ids' => $request_data['industry_experties'],
//                 'user_ids' => $latestConnectUserId
//             ]);
            
//             $latestConnectUserId = $industries_experties->ToArray();
//             $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
//         }

//         // Career status position filter
//         if(isset($request_data['career_status_position'])){
//             $career_status_position = $this->UserCareerStatusObj->getUserCareerStatusPosition([
//                 'general_title_ids' => $request_data['career_status_position'],
//                 'user_ids' => $latestConnectUserId
//             ]);
//             $latestConnectUserId = $career_status_position->ToArray();
//             $latestConnectUserId = array_column($latestConnectUserId, 'user_id');

//         }
        
//         // Age range filter filter
//         if(isset($request_data['age_from']) && isset($request_data['age_to'])){
//             $user_record = $this->UserObj->getUser([
//                 'user_ids' => $latestConnectUserId,
//                 'age_from'=> $request_data['age_from'],
//                 'age_to'=> $request_data['age_to'],
//             ]);
            
//             $latestConnectUserId = $user_record->ToArray();
//             $latestConnectUserId = array_column($latestConnectUserId, 'id');
//         }

//         // Gender filter
//         if(isset($request_data['gender'])){
//             $user_record = $this->UserObj->getUser([
//                 'gender' => $request_data['gender'],
//                 'user_ids' => $latestConnectUserId
//             ]);

//             $latestConnectUserId = $user_record->ToArray();
//             $latestConnectUserId = array_column($latestConnectUserId, 'id');
//         }

//         $connect_peoples = $this->UserObj->getUser([
//             'user_ids' => $latestConnectUserId
//         ]);

//         return $this->sendResponse($connect_peoples, 'User connect list');
    
//     }


//     // Post connects peoples
//     public function connects_people(Request $request){
//         if (\Auth::check()) {

//             $rules = array(
//                 'connect_user_id' => 'required|exists:users,id'
//             );
    
//             $validator = \Validator::make($request->all(), $rules);
    
//             if ($validator->fails()) {
//                 return $this->sendError(["error" => $validator->errors()->first()]);
//             }

//             $requestd_data = array();
//             $requestd_data['user_id'] = \Auth::user()->id;
//             $requestd_data['connect_user_id'] =  $request->connect_user_id;
//             $requestd_data['status'] = 'Pending';
//             $requestd_data['connect_type'] =  'phonebook';

//             $this->ConnectPeopleObj->saveUpdateConnectPeople($requestd_data);
//             return $this->sendResponse('Success', 'User connected');
//         }
//         else{
//             return $this->sendError('Something went wrong');
//         }
//     }

//     // Update connects peoples
//     public function update_connects_people(Request $request, $id){
        
//         $update_data = $request->all();
//         $update_data['update_id'] = $id;

//         $rules = array(
//             'update_id' => 'exists:connect_people,id'
//         );

//         $validator = \Validator::make($update_data, $rules);
//         if ($validator->fails()) {
//             return $this->sendError(["error" => $validator->errors()->first()]);
//         }

//         $connect_people = $this->ConnectPeopleObj->getConnectPeople([
//             'id' => $id,
//             'detail' => true
//         ]);
//         if ($connect_people) {
//             if ($update_data['status'] == 'Accept') {
                
//                 $this->ConnectPeopleObj->saveUpdateConnectPeople([
//                     'user_id' => $connect_people->connect_user_id,
//                     'connect_user_id' => \Auth::user()->id,
//                     'status' => 'Accept',
//                     'connect_type' => 'phonebook'
//                 ]);  
//             }
//         }
        
//         $this->ConnectPeopleObj->saveUpdateConnectPeople($update_data);
//         return $this->sendResponse('Success', 'User connection updated');
//     }


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
            'cuisine_type' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|required_with:password|same:password'
        );
        if ($requested_data['user_login_status'] == 'customer') {
            $rules = array(
                   
                'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                'last_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                'phone_number'  => 'required|unique:users,phone_number',
                'email' => 'required|email:rfc,dns|unique:users,email',
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
            'cuisine_type' => $requested_data['cuisine_type'],
        ]);
        }
      
        // $user = $this->UserObj->getUser([
        //     'email' => $user['email'],
        //     'detail' => true
        // ]);
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

    public function forgotPassword(Request $request)
    {

        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return $this->sendResponse('message', ['message' => trans('passwords.sent')]);


        // $rules = array(
        //     'email' => 'required||email:rfc,dns|email',
        // );
        // $validator = \Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        // } else {

        //     $users = User::where('email', '=', $request->input('email'))->first();
        //     if ($users === null) {

        //         $error_message['error'] = 'We do not recognize this email address. Please try again.';
        //         return $this->sendError($error_message['error'], $error_message);
        //     } else {
        //         $random_hash = substr(md5(uniqid(rand(), true)), 10, 10);
        //         $email = $request->get('email');
        //         $password = Hash::make($random_hash);

        //         \DB::update('update users set password = ? where email = ?', [$password, $email]);

        //         $data = [
        //             'new_password' => $random_hash,
        //             'subject' => 'Reset Password',
        //             'email' => $email
        //         ];

        //         $admin['id'] = 1;
        //         $admin['detail'] = true;
        //         $admin_data = $this->UserObj->getUser($admin);

        //         if ($admin_data) {

        //             // this email will sent to the user who have requested to forget password
        //             $email_content = EmailTemplate::getEmailMessage(['id' => 7, 'detail' => true]);

        //             $email_data = decodeShortCodesTemplate([
        //                 'subject' => $email_content->subject,
        //                 'body' => $email_content->body,
        //                 'email_message_id' => 7,
        //                 'user_id' => $users->id,
        //                 'new_password' => $random_hash,
        //             ]);

        //             EmailLogs::saveUpdateEmailLogs([
        //                 'email_msg_id' => 7,
        //                 'sender_id' => $admin_data->id,
        //                 'receiver_id' => $users->id,
        //                 'email' => $users->email,
        //                 'subject' => $email_data['email_subject'],
        //                 'email_message' => $email_data['email_body'],
        //                 'send_email_after' => 1, // 1 = Daily Email
        //             ]);
        //         }


        //         /*
        //         Mail::send('emails.reset_password', $data, function($message) use ($data) {
        //             $message->to($data['email'])
        //             ->subject($data['subject']);
        //         });
        //         */

        //         return $this->sendResponse($data, 'Your password has been reset. Please check your email.');
        
        
    }

    

    public function changePassword(Request $request)
    {
        $params = $request->all();
        $rules = array(
            'email'             => 'required|email:rfc,dns|email',
            'old_password'      => 'required',
            // 'new_password'      => 'required|min:4',
            'new_password'      => [
                'required', Password::min(8)
                    // ->letters()
                    // ->mixedCase()
                    // ->numbers()
                    // ->symbols()
                    // ->uncompromised()
            ],
            'confirm_password'  => 'required|required_with:new_password|same:new_password'
        );
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $response = $this->authorizeUser([
            'email' => $params['email'],
            'password' => $params['old_password'],
            'mode' => 'only_validate',
        ]);

        if ($params['old_password'] == $params['new_password']) {
            $error_message['error'] = 'New and old password must be different.';
            return $this->sendError($error_message['error'], $error_message);
        }

        if (!$response) {
            $error_message['error'] = 'Your old password is incorrect.';
            return $this->sendError($error_message['error'], $error_message);
        } else {
            $new_password = $params['confirm_password'];
            $email = $request->get('email');
            $password = Hash::make($new_password);

            \DB::update('update users set password = ? where email = ?', [$password, $email]);

            // $data = [
            //     'new_password' => $new_password,
            //     'subject' => 'Reset Password',
            //     'email' => $email
            // ];

            $admin['id'] = 1;
            $admin['detail'] = true;
            $admin_data = $this->UserObj->getUser($admin);
            return $this->sendResponse([], 'Your password has been updated.');
        }
    }

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
