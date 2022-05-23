<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;
use App\Models\User;
use App\Models\Service;
use App\Models\AssignService;
use App\Models\FCM_Token;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $posted_data = $request->all();
        $rules = array(
            'user_name'         => 'required|max:80',
            'user_type'         => 'required|max:80',
            'email'             => 'required|email|max:80',
            'role'              => 'required|max:50',
            'phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
            'address'           => 'nullable|max:250',
            'latitude'          => 'nullable',
            'longitude'         => 'nullable',
            'service_id'        => 'nullable|max:5000',
            
            /*
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|required_with:password|same:password'
            license_document
            insurance_document
            experience:5 years
            no_of_jobs_completed:10
            */
        );
        
        $messages = array(
            'phone_number.min' => 'The :attribute format is not correct (123-456-7890).'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        } else {

            try {
                $posted_data = $request->all();
                $posted_data['name'] = $posted_data['user_name'];
                
                if($posted_data['role'] != 2 && $posted_data['role'] != 3){
                    $error_message['error'] = 'The role is not valid';
                    return $this->sendError($error_message['error'], $error_message);
                }

                if( isset($posted_data['user_type']) && ( $posted_data['user_type'] == 'facebook' || $posted_data['user_type'] == 'google' ) ){

                    $user_data = array();
                    $user_data['email'] = $posted_data['email'];
                    $user_data['detail'] = true;
                    $user_data = $this->UserObj->getUser($user_data);

                    // if ( isset($user_data['id']) && isset($user_data['user_type']) && $user_data['user_type'] != 'app' ) {
                    if ( isset($user_data['id']) && isset($user_data['user_type']) ) {

                        $user = User::where('email', $posted_data['email'])->first();    
                        if (Auth::loginUsingId($user->id)){
                            $user = Auth::user();
                            $response =  $user;
                            $response['token'] =  $user->createToken('MyApp')->accessToken;
                        }else {
                            $response = false;
                        }

                        if ($response)
                            return $this->sendResponse($response, 'User login successfully.');
                        else {
                            $error_message['error'] = 'This email has already been registered.';
                            return $this->sendError($error_message['error'], $error_message);
                        }
                    }
                    else {
        
                        $user_data = array();
                        $user_data['name'] = $posted_data['user_name'];
                        $user_data['email'] = $posted_data['email'];
                        $user_data['role'] = 3;
                        $user_data['account_status'] = $user_data['role'] == 2 ? 'no' : 'yes';
                        $user_data['password'] = '12345678@d';
                        $user_data['user_type'] = $posted_data['user_type'];
            
                        $user_id = $this->UserObj->saveUpdateUser($user_data);
                        
                        if ($user_id) {
                            $response = $this->authorizeUser([
                                'email' => $posted_data['email'],
                                'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@d'
                            ]);
            
                            if ($response)
                                return $this->sendResponse($response, 'User login successfully.');
                            else
                                $error_message['error'] = 'The user credentials are not valid.';
                                return $this->sendError($error_message['error'], $error_message);
                        }
                    }
                }

                $user_data = array();
                $user_data['email'] = $posted_data['email'];
                $user_data['detail'] = true;
                $user_data = $this->UserObj->getUser($user_data);

                if(isset($user_data['email']) && ($posted_data['email'] == $user_data['email'])){
                    $error_message['error'] = 'The email has already been taken';
                    return $this->sendError($error_message['error'], $error_message);
                }

                if(empty($posted_data['address']) || empty($posted_data['latitude']) || empty($posted_data['longitude'])){
                    $error_message['error'] = 'Address field is required you must select address from the suggession.';
                    return $this->sendError($error_message['error'], $error_message);
                }

                if(empty($posted_data['phone_number'])){
                    $error_message['error'] = 'The phone number field is required.';
                    return $this->sendError($error_message['error'], $error_message);
                }

                if( empty($posted_data['password']) || empty($posted_data['confirm_password']) ) {
                    $error_message['error'] = 'The password and confirm password must not be empty.';
                    return $this->sendError($error_message['error'], $error_message);
                }

                if( isset($posted_data['password']) && isset($posted_data['confirm_password']) ) {
                    if ( $posted_data['password'] != $posted_data['confirm_password'] ) {
                        $error_message['error'] = 'The password and confirm password must be same.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }

                /*
                if(!$request->file('profile_image')) {
                    $error_message['profile_image'] = 'The Profile image field is required.';
                }else{
                    $extension = $request->profile_image->getClientOriginalExtension();
                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
                    }else{
                        $error_message['profile_image'] = 'The Profile image format is not correct you can only upload (jpg, jpeg, png).';
                    }
                }
                */

                if(!$request->file('license_document') && $posted_data['role'] == 2) {
                    $error_message['error'] = 'The license document field is required.';
                    return $this->sendError($error_message['error'], $error_message);
                }
                
                if(!$request->file('insurance_document') && $posted_data['role'] == 2) {
                    $error_message['error'] = 'The insurance document field is required.';
                    return $this->sendError($error_message['error'], $error_message);
                }
                    
                if($posted_data['role'] == 2 && (!isset($posted_data['service_id']) || empty($posted_data['service_id']))){
                    $error_message['error'] = 'Please select service for the service provider.';
                    return $this->sendError($error_message['error'], $error_message);
                }

                if($posted_data['role'] == 2 && isset($posted_data['service_id']) && !empty($posted_data['service_id'])){
                    $chk_service = Service::find($posted_data['service_id']);
                    if(!$chk_service){
                        $error_message['error'] = 'Service is not available please select another service.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }

                if($request->file('license_document')) {
                    $allowedfileExtension = ['pdf','jpg','jpeg','png'];
                    $extension = $request->license_document->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $request->license_document, 'provider_documents');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }
                        
                        $posted_data['license_document'] = $arr['file_path'];
                    }
                    else {
                        $error_message['error'] = 'Invalid file format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }

                if($request->file('insurance_document')) {
                    $allowedfileExtension = ['pdf','jpg','jpeg','png'];
                    $extension = $request->insurance_document->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $request->insurance_document, 'provider_documents');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }
                        
                        $posted_data['insurance_document'] = $arr['file_path'];
                    }
                    else {
                        $error_message['error'] = 'Invalid file format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }

                $last_rec = User::saveUpdateUser($posted_data);

                if($posted_data['role'] == 2 && isset($posted_data['service']) && !empty($posted_data['service'])){
                //assign single services
                // =================================================================== 
                    $user = User::find($last_rec->id);
                    $assign_service = new AssignService;
                    $assign_service->service_id = $posted_data['service'];
                    $user = $user->AssignServiceHasOne()->save($assign_service);
                // ===================================================================
                }

                return $this->sendResponse($last_rec, 'User Register Successfully.');

            } catch (Exception $e) {
                $error_message['error'] = $e->getMessage();
                return $this->sendError($error_message['error'], $error_message);
            }
            $error_message['error'] = 'Something went wrong during registration.';
            return $this->sendError($error_message['error'], $error_message);
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

        if(Auth::attempt(['email' => $email, 'password' => $password])){ 
            $user = Auth::user();
            $response =  $user;
            $response['token'] =  $user->createToken('MyApp')->accessToken;

            return $response;
        }
        else{
            return false;
        }
    }

   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    { 
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            $success =  $user;
            $success['token'] =  $user->createToken('MyApp')->accessToken;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            $error_message['error'] = 'The user credentials are not valid';
            return $this->sendError($error_message['error'], $error_message);
        } 
    }

    
    public function getProfile(Request $request)
    {
        if (!empty(Auth::user()) ) {
            $user = Auth::user();
            return $this->sendResponse($user, 'User profile is successfully loaded.');
        }
        else {
            $error_message['error'] = 'Please do login to get profile data.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }


    public function forgotPassword(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
        );
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Please post the valid information.', ["error"=>$validator->errors()->first()]);
        }
        else {

            $users = User::where('email', '=', $request->input('email'))->first();
            if ($users === null) {

                $error_message['error'] = 'We do not recognize this email address. Please try again.';
                return $this->sendError($error_message['error'], $error_message);
            } else {
                $random_hash = substr(md5(uniqid(rand(), true)), 10, 10); 
                $email = $request->get('email');
                $password = Hash::make($random_hash);

                DB::update('update users set password = ? where email = ?',[$password,$email]);

                $data = [
                    'new_password' => $random_hash,
                    'subject' => 'Reset Password',
                    'email' => $email
                ];

                Mail::send('emails.reset_password', $data, function($message) use ($data) {
                    $message->to($data['email'])
                    ->subject($data['subject']);
                });

                return $this->sendResponse($data, 'Your password has been reset. Please check your email.');

            }

        }
    }

    public function updateProfile(Request $request)
    {
        $posted_data = $request->all();
        $rules = array(
            'profile_id' => 'required',
            'first_name' => 'nullable|max:50',
            'last_name' => 'nullable|max:50',
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
            'address' => 'nullable|max:100',
            // 'profile_image' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'service' => 'nullable',
        );
        
        $messages = array(
            'phone_number.min' => 'The :attribute format is not correct (123-456-7890).'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        } else {

            try{
                $posted_data = $request->all();
                // $user_data = false;

                // if (isset($posted_data['profile_id']))
                //     $user_data = User::find($posted_data['profile_id']);

                // $posted_data['role'] = $user_data->role;

                // if(empty($posted_data['address']) || empty($posted_data['latitude']) || empty($posted_data['longitude'])){
                //     $error_message['address'] = 'Address field is required you must select address from the suggession.';
                // }

                // if(empty($posted_data['phone_number'])){
                //     $error_message['phone_number'] = 'The Phone number field is required.';
                // }

                if(!$request->file('profile_image')) {
                    // $error_message['profile_image'] = 'The Profile image field is required.';
                }else{
                    $extension = $request->profile_image->getClientOriginalExtension();
                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                    }else{
                        $error_message['error'] = 'The Profile image format is not correct you can only upload (jpg, jpeg, png).';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }

                // if(!$request->file('license_document') && $posted_data['role'] == 2) {
                //     $error_message['license_document'] = 'The License document field is required.';
                // }

                // if(!$request->file('insurance_document') && $posted_data['role'] == 2) {
                //     $error_message['insurance_document'] = 'The Insurance document field is required.';
                // }

                if(isset($posted_data['role']) && $posted_data['role'] == 2 && (!isset($posted_data['service']) || empty($posted_data['service']))){
                    $error_message['error'] = 'Please select service for the service provider.';
                    return $this->sendError($error_message['error'], $error_message);
                }

                // if($posted_data['role'] == 2 && isset($posted_data['service']) && !empty($posted_data['service'])){
                //     $chk_service = Service::find($posted_data['service']);
                //    if(!$chk_service){
                //         $error_message['service'] = 'Service is not available please select another service.';
                //     }
                // }

                $base_url = public_path();
                if($request->file('profile_image')) {
                    $file_name = time().'_'.$request->profile_image->getClientOriginalName();
                    $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
                    $posted_data['profile_image'] = 'profile_image/'.$file_name;
                }

                if($request->file('license_document')) {
                    $file_name = time().'_'.$request->license_document->getClientOriginalName();
                    $filePath = $request->file('license_document')->storeAs('license_document', $file_name, 'public');
                    $posted_data['license_document'] = 'license_document/'.$file_name;
                }

                if($request->file('insurance_document')) {
                    $file_name = time().'_'.$request->insurance_document->getClientOriginalName();
                    $filePath = $request->file('insurance_document')->storeAs('insurance_document', $file_name, 'public');
                    $posted_data['insurance_document'] = 'insurance_document/'.$file_name;
                }
                
                $posted_data['update_id'] = $posted_data['profile_id'];
                $last_rec = User::saveUpdateUser($posted_data);

                if(isset($posted_data['service']) && !empty($posted_data['service'])){

                    //assign single services
                    // =================================================================== 
                        // $user = User::find($last_rec->id);
                        // $assign_service = new AssignService;
                        // $assign_service->service_id = $posted_data['service'];
                        // $user = $user->AssignServiceHasOne()->save($assign_service);
                    // ===================================================================

                
                    $AssignService = AssignService::where('user_id',$last_rec->id)->first();

                    if($AssignService){
                        $service = Service::find($posted_data['service']);
                        $AssignService->service()->associate($service)->save();

                    }else{
                        $user = User::find($last_rec->id);
                        $assign_service = new AssignService;
                        $assign_service->service_id = $posted_data['service'];
                        $user->AssignServiceHasOne()->save($assign_service);
                    }
                }

                return $this->sendResponse($last_rec, 'User Register Successfully.');

            } catch (Exception $e) {
                $error_message['error'] = $e->getMessage();
                return $this->sendError($error_message['error'], $error_message);
            }
            $error_message['error'] = 'Something went wrong during update.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }

    public function logoutProfile(Request $request)
    {
        $params = $request->all();
        
        $validatedRule = [
            'device_id' => 'required|max:1500',
        ];

        $validator = Validator::make($params, $validatedRule);

        if($validator->fails()){
            $error_message['error'] = 'Please provide the device_id to logout user.';
            return $this->sendError($error_message['error'], $error_message);
        }
        else {
            
            // if(!Auth::check()){
            //     exit('Outside fails');
            //     $error_message['error'] = 'Please login again you account is logout.';
            //     return $this->sendError($error_message['error'], $error_message);
            // }

            $user_id = isset($params['user_id']) ? $params['user_id'] : Auth::user()->id;
            $token_data = FCM_Token::where('device_id', '=', $params['device_id'])->first();
            $token_exist = false;

            if ($token_data)
            {
                $token_data->delete();
                $token_exist = true;
            }

            $request->user()->token()->revoke();
            $message = ($token_data) ? 'User successfully logout and device token also revoked.' : 'User successfully logged out but device token not found.';

            return $this->sendResponse([], $message);
        }
    }
}