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
            'profile_image' => 'required',
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:12',
            'user_role' => 'required',
            'user_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|required_with:password|same:password'
        );
        
        $messages = array(
            'phone_number.min' => 'The :attribute format is not correct (123-456-7890).'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());  
        } else {

            try{
                $posted_data = $request->all();
                $posted_data['role'] = $posted_data['user_role'];
                $posted_data['name'] = $posted_data['user_name'];

                if($posted_data['role'] == 2 || $posted_data['role'] == 3){

                    if(empty($posted_data['address']) || empty($posted_data['latitude']) || empty($posted_data['longitude'])){
                        $error_message['address'] = 'Address field is required you must select address from the suggession.';
                    }

                    if(empty($posted_data['phone_number'])){
                        $error_message['phone_number'] = 'The Phone number field is required.';
                    }

                    if(!$request->file('profile_image')) {
                        $error_message['profile_image'] = 'The Profile image field is required.';
                    }

                    if(!empty($error_message)){
                        return $this->sendError('Validation Error.', $error_message);  
                    }
                }

                if($posted_data['role'] == 2 && (!isset($posted_data['service']) || empty($posted_data['service']))){
                    $error_message['service'] = 'Please select service for the service provider.';
                    return $this->sendError('Validation Error.', $error_message);  
                }

                if($posted_data['role'] == 2 && isset($posted_data['service']) && !empty($posted_data['service'])){
                    $chk_service = Service::find($posted_data['service']);
                   if(!$chk_service){
                        $error_message['service'] = 'Service is not available please select another service.';
                        return $this->sendError('Validation Error.', $error_message);  
                    }
                }


                $base_url = public_path();
                if($request->file('profile_image')) {
                    $extension = $request->profile_image->getClientOriginalExtension();
                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                        if (!empty(\Auth::user()->profile_image)) {
                            $url = $base_url.'/'.\Auth::user()->profile_image;
                            if (file_exists($url)) {
                                unlink($url);
                            }
                        }   
                        
                        $file_name = time().'_'.$request->profile_image->getClientOriginalName();
                        $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
                        $posted_data['profile_image'] = 'storage/profile_image/'.$file_name;
                    }else{
                        $error_message['profile_image'] = 'The Profile image format is not correct you can only upload (jpg, jpeg, png).';
                        return $this->sendError('Validation Error.', $error_message);
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
                $error_message['exception'] = $e->getMessage();
                return $this->sendError('Validation Error.', $error_message);
            }
            return $this->sendError('Validation Error.', $error_message);
        }
    }

    // public function register_bk(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'role' => 'required',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required',
    //         'c_password' => 'required|same:password',
    //     ]);
   
    //     if($validator->fails()){
    //         return $this->sendError('Validation Error.', $validator->errors());       
    //     }
   
    //     $input = $request->all();
    //     $input['password'] = bcrypt($input['password']);
    //     $user = User::create($input);
    //     $success['token'] =  $user->createToken('MyApp')->accessToken;
    //     $success['name'] =  $user->name;
    //     $success['name'] =  $user->role;
   
    //     return $this->sendResponse($success, 'User register successfully.');
    // }
   
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
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }


    public function forgotPassword(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
        );
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());  
        } else {

            $users = User::where('email', '=', $request->input('email'))->first();
            if ($users === null) {

                $error_message['email'] = 'We do not recognize this email address. Please try again.';
                return $this->sendError('Validation Error.', $error_message); 
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
}