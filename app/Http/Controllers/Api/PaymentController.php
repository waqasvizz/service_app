<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use RahulHaque\Filepond\Models\Filepond;
use App\Models\Filepond as Filepond_Model;
use Validator;
use App\Models\Payment;
use App\Models\PostAssets;
use App\Models\User;
use App\Models\Notification;
use App\Models\FCM_Token;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $params = $request->all();
        // $assets_array = array();

        $posted_data =  $params;
        $posted_data['paginate'] = 10;

        if (isset($params['post_id']))
            $posted_data['id'] = $params['post_id'];
        if (isset($params['service_id']))
            $posted_data['service_id'] = $params['service_id'];
        if (isset($params['customer_id']))
            $posted_data['customer_id'] = $params['customer_id'];
        if (isset($params['post_title']))
            $posted_data['title'] = $params['post_title'];
        if (isset($params['per_page']))
            $posted_data['paginate'] = $params['per_page'];
        
        // $posts = Payment::getPost($posted_data)->ToArray();
        $posts = Payment::getPost($posted_data);
        $message = count($posts) > 0 ? 'Posts retrieved successfully.' : 'Posts not found against your query.';
        return $this->sendResponse($posts, $message);

        
        // // $posts = Payment::getPost($posted_data);

        // if (count($posts) > 0) {
        //     foreach ($posts['data'] as $key => $item) {
        //         // foreach ($posts as $key => $item) {
        //         $posts['data'][$key]['images'] = PostAssets::getPostAssets(['post_id' => $item['id'], 'asset_type' => 'image'])->ToArray();
        //         $posts['data'][$key]['videos'] = PostAssets::getPostAssets(['post_id' => $item['id'], 'asset_type' => 'video'])->ToArray();

        //         // echo '<pre>';
        //         // print_r($item->PostAssets);
        //         // exit;
        //         // $payment = Payment::find($item->id);
        //         // $posts[$key]['images'] = $payment->PostAssets;
                
        //         // $posts[$key]['images'] = PostAssets::whereHas('payment', function($q) {
        //         //     $q->where('asset_type', 'image');
        //         // })->where('post_id', $item->id)->get();
                
        //         // $posts[$key]['video'] = PostAssets::whereHas('payment', function($q) {
        //         //     $q->where('asset_type', 'video');
        //         // })->where('post_id', $item->id)->get();

        //         // echo '<pre>';
        //         // print_r($posts[$key]['service_id']);
        //         // exit;
        
        //         // $posts['data'][$key]['images'] = Payment::PostAssets();

        //         // $assets_array = [];
        //         // foreach ($posts_assets as $posts_key => $posts_item) {
        //         //     if (isset($posts_item['asset_type'])) {
        //         //         $assets_array['asset_id'] = $posts_item['id'];
        //         //         $assets_array['asset_type'] = $posts_item['asset_type'];
        //         //         $assets_array['filename'] = $posts_item['filename'];
        //         //         $assets_array['filepath'] = $posts_item['filepath'];
        //         //     }

        //         //     if ($posts_item['asset_type'] == 'image')
        //         //         $posts['data'][$key]['images'][] = $assets_array;
        //         //     else if ($posts_item['asset_type'] == 'video')
        //         //         $posts['data'][$key]['videos'][] = $assets_array;
        //         // }
        //     }
        // }

        // $message = count($posts) > 0 ? 'Posts retrieved successfully.' : 'Posts not found against your query.';

        // return $this->sendResponse($posts, $message);
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
        $posted_data = $request->all(); 

        // $images_arr = array();
        // $videos_arr = array();

        $validator = Validator::make($posted_data, [
            'user_id' => 'required',
            'amount_captured' => 'required',
            'currency' => 'required',
            'expiry_date' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $user_data = array();
        $user_data['detail'] = true;
        $user_data['id'] = $posted_data['user_id'];
        $user_record = User::getUser($user_data);

        if (!isset($user_record['email'])) {
            $error_message['error'] = 'The user id is invalid.';
            return $this->sendError($error_message['error'], $error_message);
        }

        $payment_data = array();
        $payment_data['user_id'] = $posted_data['user_id'];
        $payment_data['amount_captured'] = $posted_data['amount_captured'];
        $payment_data['currency'] = $posted_data['currency'];
        $payment_data['response_object'] = isset($posted_data['response_object']) ? $posted_data['response_object'] : NULL;
        $payment_data['payment_expiry'] = $posted_data['expiry_date'];

        $payment_response = Payment::saveUpdatePayment($payment_data);
        $message = ($payment_response->id) > 0 ? 'Payment is successfully updated.' : 'Something went wrong during payment adding.';
        $error_message['error'] = ($payment_response->id) > 0 ? 'Payment is successfully added.' : 'Something went wrong during payment adding.';
        
        if (isset($payment_response->id)) {
            $user_data = array();
            $user_data['update_id'] = $posted_data['user_id'];
            $user_data['account_status'] = 'yes';
            $user_data['account_expiry'] = $posted_data['expiry_date'];
            $user_response = User::saveUpdateUser($user_data);
            
            return $this->sendResponse([], $message);
        }
        else
            return $this->sendError($error_message['error'], $error_message);
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
    public function update(Request $request, $id = 0)
    {
        if ($id != 0) {
            $images_arr = array();
            $videos_arr = array();
    
            $posted_data = $request->all(); 
            $validator = Validator::make($posted_data, [
            //     // 'service_id'    => 'required',
            //     // 'customer_id'   => 'required',
            //     // 'price'         => 'required',
            //     // 'title'         => 'required',
            //     // 'description'   => 'required',
            //     // 'pay_with'      => 'required',
            //     // 'post_images' => 'required',
            //     // 'post_videos' => 'required',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
            }

            if($id == 0){
                $error_message['error'] = 'This payment is not found.';
                return $this->sendError($error_message['error'], $error_message);
            }

            $post_data = array();
            $post_data['detail'] = true;
            $post_data['id'] = $id;
            $post_record = Payment::getPost($post_data);
            if(!$post_record){
                $error_message['error'] = 'This payment is not found.';
                return $this->sendError($error_message['error'], $error_message);
            }
            
            if (isset($request->post_images)) {
                $allowedfileExtension = ['jpeg','jpg','png'];

                foreach($request->post_images as $mediaFiles) {

                    $extension = $mediaFiles->getClientOriginalExtension();
    
                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $mediaFiles, 'post_assets');
    
                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }
    
                        $filepond = Filepond::create([
                            'filepath' => $arr['file_path'],
                            'filename' => $arr['file_name'],
                            'extension' => $mediaFiles->getClientOriginalExtension(),
                            'mimetypes' => $mediaFiles->getClientMimeType(),
                            'disk' => 'public',
                            'created_by' => auth()->id(),
                            'expires_at' => now()->addMinutes(config('filepond.expiration', 30))
                        ]);
                        $arr['asset_id'] = $filepond->id;
    
                        $images_arr[] = $arr;
                    }
                    else {
                        return response()->json(['invalid_file_format'], 422);
                    }
                }
            }

            if (isset($request->post_videos)) {
                $allowedfileExtension = ['mp4','mov'];
                
                foreach($request->post_videos as $mediaFiles) {

                    $extension = $mediaFiles->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $mediaFiles, 'post_assets');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }

                        $filepond = Filepond::create([
                            'filepath' => $arr['file_path'],
                            'filename' => $arr['file_name'],
                            'extension' => $mediaFiles->getClientOriginalExtension(),
                            'mimetypes' => $mediaFiles->getClientMimeType(),
                            'disk' => 'public',
                            'created_by' => auth()->id(),
                            'expires_at' => now()->addMinutes(config('filepond.expiration', 30))
                        ]);
                        $arr['asset_id'] = $filepond->id;

                        $videos_arr[] = $arr;
                    }
                    else {
                        return response()->json(['invalid_file_format'], 422);
                    }
                }
            }

            foreach ($images_arr as $key => $item) {
                PostAssets::saveUpdatePostAssets([
                    'post_id' => $post_record['id'],
                    'filepond_id' => $item['asset_id'],
                    'asset_type' => 'image',
                ]);
            }

            foreach ($videos_arr as $key => $item) {
                PostAssets::saveUpdatePostAssets([
                    'post_id' => $post_record['id'],
                    'filepond_id' => $item['asset_id'],
                    'asset_type' => 'video',
                ]);
            }

            $posted_data['update_id'] = $post_record['id'];
            $payment = Payment::saveUpdatePost($posted_data);

            $message = ($payment) ? 'Payment is successfully updated.' : 'Something went wrong during payment update.';
            return $this->sendResponse([], $message);
        }
        else {
            $error_message['error'] = 'The payment is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id != '' || $id != 0) {

            $posted_data = array();
            $posted_data['post_id'] = $id;
            $data = PostAssets::getPostAssets($posted_data);

            foreach ($data as $key => $value) {
                delete_files_from_storage($value['filepond']['filepath']);
                Filepond_Model::deleteRecord($value['filepond']['id']);
            }
            
            Payment::deletePost($id);
            return $this->sendResponse([], 'Payment is deleted successfully.');
        }
        else {
            $error_message['error'] = 'The payment is not found OR already deleted.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }
}