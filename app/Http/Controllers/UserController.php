<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\AssignService;
use App\Models\User;
use App\Models\FCM_Token;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use Validator;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Passport\Token;

class UserController extends Controller
{
    
    public function testing() {

        echo '<pre>';print_r('test');'</pre>';exit;
        $last_rec = User::getUser([
            'id' => 1,
            'detail' => true
        ]);
        // ->toArray();

                    // ===================================================================
                    $bodyMessage = '<div style="text-align: left">
                                        <h5 style="text-align: center">A New '.$last_rec->Role->name.' is signed up on 3 Bids App.</h5>
                                        <p><b>Name : </b>'.$last_rec->name.'</p>
                                        <p><b>Phone number : </b>'.$last_rec->phone_number.'</p>
                                        <p><b>Type : </b>'.$last_rec->Role->name.'</p>
                                    </div>'; 
                    $data = [
                        'subject' => 'Signed up on 3 Bids App.',
                        'body' => $bodyMessage,
                        'email' => '    '
                        // 'email' => 'joerodriguezclx@gmail.com'
                    ];

                    Mail::send('emails.default_template', $data, function($message) use ($data) {
                        $message->to($data['email'])
                        ->subject($data['subject']);
                    });
                    // ===================================================================    
                
        
        $user_id = 23;
        $token_data = FCM_Token::where('user_id', '=', $user_id)->get();
        $token_exist = false;

        if (count($token_data) > 0)
        {
            foreach ($token_data as $key => $value) {
                $token_data[$key]->delete();
            }

            Token::where('user_id', $user_id)
                ->update(['revoked' => true]);
        }

        // $request->user()->token()->revoke();
        // $message = ($token_data) ? 'User successfully logout and device token also revoked.' : 'User successfully logged out but device token not found.';
        
    }
    
    public function welcome()
    {
        return view('auth_v1.login');
    }

    public function login()
    {
        return view('auth_v1.login');
    }

    public function register()
    {
        return view('auth_v1.register');
    }

    public function resetPassword()
    {
        return view('auth_v1.reset-password');
    }

    public function forgotPassword()
    {
        return view('auth_v1.forgot-password');
    }

    public function profile()
    {   
       $user = Auth::user();
       $roles = $this->RoleObj->get();  
        return view('user.profile',compact('user','roles'));
    }    
    
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function dashboard()
    {
        $posted_data = array();
        $posted_data['count'] = true;
        $data['users_count'] = $this->UserObj->getUser($posted_data);
        $data['posts_count'] = $this->PostObj->getPost($posted_data);
        $data['bids_count'] = $this->BidObj->getBids($posted_data);
        $data['revenue_count'] = $this->PaymentObj->sum('amount_captured');
      
        return view('dashboard', compact('data'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['roles'] = Role::getRoles();
        
        // $posted_data = array();
        // $posted_data['paginate'] = 10;
        // $data['users'] = User::getUser($posted_data);

        return view('user.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $posted_data = array();
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['roles'] = Role::getRoles($posted_data);


        $posted_data = array();
        $posted_data['orderBy_name'] = 'service_name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['services'] = Service::getServices($posted_data);
        
        return view('user.add',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
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
                        return back()->withErrors($error_message)->withInput();
                    }
                }

                if($posted_data['role'] == 2 && (!isset($posted_data['service']) || empty($posted_data['service']))){
                    // Session::flash('message', 'Please select service for the service provider!');
                    // return redirect()->back()->withInput();
                        $error_message['service'] = 'Please select service for the service provider.';
                    return back()->withErrors($error_message)->withInput();
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
                        $posted_data['profile_image'] = 'profile_image/'.$file_name;
                    }else{
                        return back()->withErrors([
                            'profile_image' => 'The Profile image format is not correct you can only upload (jpg, jpeg, png).',
                        ])->withInput();
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


                Session::flash('message', 'User Register Successfully!');

            } catch (Exception $e) {
                Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajax_get_users(Request $request) {

        $posted_data = $request->all();

        if ($request->ajax()) {

            if ( isset($posted_data['method']) ) unset($posted_data['method']);
            if ( isset($posted_data['url']) ) unset($posted_data['url']);
                
            if (!( isset($posted_data['role']) && $posted_data['role'] != '' && $posted_data['role'] != 0 ))
                unset($posted_data['role']);
            if (!( isset($posted_data['active_status']) && $posted_data['active_status'] != '' && $posted_data['active_status'] != '0' ))
                unset($posted_data['active_status']);
        }
        else {
            // without ajax data here
        }

    
        $user = $this->UserObj->getUser($posted_data);

        $data['users'] = $user;
        
        if (isset($posted_data['role']) && $posted_data['role'] == 1 )
            $data['users_mode'] = 'Admin';
        else if (isset($posted_data['role']) && $posted_data['role'] == 2 )
            $data['users_mode'] = 'Contractor';
        else if (isset($posted_data['role']) && $posted_data['role'] == 3 )
            $data['users_mode'] = 'Customer';

        if ($request->ajax()) {
            // if (!( isset($posted_data['module']) && $posted_data['module'] == 'bids' ))
                return view('user.ajax_records', compact('data'));
            // else
                // return response()->json(['data' => $data]);
        }
        else {
            return view('post.list', compact('data'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $AssignService = AssignService::where('user_id',3)->first();
        
        // $user = User::find(2);
        
        // // $AssignService->user()->associate($user)->save();
        // $AssignService->user()->associate($user);

 
        // echo '<pre>';
        // print_r($AssignService->toArray());
        // exit; 
        
        $posted_data = array();
        $posted_data['id'] = $id;
        $posted_data['detail'] = true;
        $data = User::getUser($posted_data);

 
        $posted_data = array();
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['roles'] = Role::getRoles($posted_data);


        $posted_data = array();
        $posted_data['orderBy_name'] = 'service_name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['services'] = Service::getServices($posted_data);


        $AssignService = AssignService::where('user_id', $id)->first();
        if($AssignService){
            // $AssignService->user()->associate($data);
            // $data = $AssignService;
            $data['assign_service'] = $data->AssignService->toArray();
        }

        // echo '<pre>';
        // print_r($data->toArray());
        // exit; 

        // $user = User::find($id);
        // $data['assign_service'] = $data->AssignService;

        // echo '<pre>';
        // print_r($data['assign_service']->toArray());
        // exit; 
        

        return view('user.add',compact('data'));
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
        $posted_data = $request->all(); 
       
        $rules = array(
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:12',
            'user_role' => 'required',
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'password' => 'nullable|min:6',
            'confirm_password' => 'nullable|required_with:password|same:password'
        );
        
        $messages = array(
            'phone_number.min' => 'The :attribute format is not correct (123-456-7890).'
        );

        $validator = \Validator::make($posted_data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {
            // echo '<pre>';
            // print_r($posted_data);
            // exit;

            try{
                $posted_data['update_id'] = $id;
                $posted_data['role'] = $posted_data['user_role'];
                $posted_data['name'] = $posted_data['user_name'];



                if($posted_data['role'] == 2 || $posted_data['role'] == 3){

                    if(empty($posted_data['address']) || empty($posted_data['latitude']) || empty($posted_data['longitude'])){
                        $error_message['address'] = 'Address field is required you must select address from the suggession.';
                    }

                    if(empty($posted_data['phone_number'])){
                        $error_message['phone_number'] = 'The Phone number field is required.';
                    }

                    // if(!$request->file('profile_image')) {
                    //     $error_message['profile_image'] = 'The Profile image field is required.';
                    // }
 
                    if((!isset($posted_data['service']) && $posted_data['role'] == 2) || (isset($posted_data['service']) && $posted_data['role'] == 2 && empty($posted_data['service']))){
                        $error_message['service'] = 'Please select service for the service provider.';
                    }

                    if(!empty($error_message)){
                        return back()->withErrors($error_message)->withInput();
                    }
                }


                //assign single services
                // =================================================================== 
                    // $user = User::find($id);

                    // $assign_service = new AssignService;
                    // $assign_service->service_id = $posted_data['service'];
                    
                    // $user = $user->AssignServiceHasOne()->save($assign_service);
                // ===================================================================


                if(isset($posted_data['service']) && $posted_data['role'] == 2 && $posted_data['service'] != ''){

                    $AssignService = AssignService::where('user_id',$id)->first();

                    if($AssignService){
                        $service = Service::find($posted_data['service']);
                        $AssignService->service()->associate($service)->save();
                    }else{
                        $user = User::find($id);
                        $assign_service = new AssignService;
                        $assign_service->service_id = $posted_data['service'];
                        $user->AssignServiceHasOne()->save($assign_service);
                    }
                    
                }else{
                    if($posted_data['role'] == 2 && (!isset($posted_data['service']) || empty($posted_data['service']))){
                        $error_message['service'] = 'Please select service for the service provider.';
                        return back()->withErrors($error_message)->withInput();
                    }
                    $delete_old_selected_services = AssignService::where('user_id',$id);
                    $delete_old_selected_services->delete(); 
                }



                //assign multiple services
                // =================================================================== 

                    // $delete_old_selected_services = AssignService::where('user_id',$id);
                    // $delete_old_selected_services->delete(); 

                    // $user = User::find($id);
    
                    // $assign_service1 = new AssignService;
                    // $assign_service1->service_id = 1;
                    
                    // $assign_service2 = new AssignService;
                    // $assign_service2->service_id = 2;
                    
                    // $user = $user->AssignService()->saveMany([$assign_service1, $assign_service2]);
                // ===================================================================
                
                // $assignService = AssignService::find(1);
                // $service = Service::find($posted_data['service']);
                // $assignService->service()->associate($service)->save();


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
                        $posted_data['profile_image'] = 'profile_image/'.$file_name;
                    }else{
                        return back()->withErrors([
                            'profile_image' => 'The Profile image format is not correct you can only upload (jpg, jpeg, png).',
                        ])->withInput();
                    }
                }

                User::saveUpdateUser($posted_data);
                Session::flash('message', 'User Update Successfully!');
                return redirect()->back();

            } catch (Exception $e) {
                Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_records(Request $request) {
        
        $posted_data = $request->all();

        $user_data = array();

        if ( isset($posted_data['update_id']) )
            $user_data['update_id'] = $posted_data['update_id'];
        if ( isset($posted_data['active_status']) )
            $user_data['active_status'] = $posted_data['active_status'];

        $response = User::saveUpdateUser($user_data);

        if ($response->id) {
            $user_id = isset($user_data['update_id']) ? $user_data['update_id'] : 0;
            $token_data = FCM_Token::where('user_id', '=', $user_id)->get();
    
            if (count($token_data) > 0)
            {
                foreach ($token_data as $key => $value) {
                    $token_data[$key]->delete();
                }
    
                Token::where('user_id', $user_id)
                    ->update(['revoked' => true]);
            }
        }

        Session::flash('message', 'User Updated Successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            
            $base_url = public_path();
            if (!empty($user->profile_image)) {
                $url = $base_url.'/'.$user->profile_image;
                if (file_exists($url)) {
                    unlink($url);
                }
            }

            User::deleteUser($id); 
            Session::flash('message', 'User deleted successfully!');
        }else{
            Session::flash('error_message', 'User already deleted!');
        }
        return redirect()->back();
        // if(Service::find($id)){
        //     Service::deleteService($id); 
        //     Session::flash('message', 'Service deleted successfully!');
        // }else{
        //     Session::flash('error_message', 'Service already deleted!');
        // }
        // return redirect()->back();
    }

    public function accountLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],  
        ]);
        $credentials['role'] = 1;

        if (Auth::attempt($credentials)) {
            return redirect('/dashboard');
        }else{
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    public function accountRegister(Request $request)
    {
        $rules = array(
            'user_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|required_with:password|same:password'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            try{
                $posted_data = $request->all();
                $posted_data['role'] = 2;
                $posted_data['name'] = $posted_data['user_name'];

                User::saveUpdateUser($posted_data);
                Session::flash('message', 'User Register Successfully!');

            } catch (Exception $e) {
                Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            return redirect('/login');
        }
    }
    
    public function accountResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|email|unique:users',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $users = User::where('email', '=', $request->input('email'))->first();
            if ($users === null) {
                // echo 'User does not exist';
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
                // Session::flash('error_message', 'Your email does not exists.');
                return redirect()->back()->withInput();
            } else {
                // echo 'User exits';
                $random_hash = substr(md5(uniqid(rand(), true)), 10, 10); 
                $email = $request->get('email');
                $password = Hash::make($random_hash);

                // $userObj = new user();
                // $posted_data['email'] = $email;
                // $posted_data['password'] = $password;
                // $userObj->updateUser($posted_data);

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
                Session::flash('message', 'Your password has been changed successfully please check you email!');
                return redirect('/login');
            }

        }
    }
}