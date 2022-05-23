<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Notification;
use App\Models\User;
use App\Models\Bid;
use App\Models\Chat;
use App\Models\Post;
use Auth;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if ( isset($params['filter']) && $params['filter'] == 'today')
            $params['one_day_time'] = date("Y-m-d");
        if ( isset($params['filter']) && $params['filter'] == 'last-day')
            $params['last_day_time'] = date("Y-m-d", strtotime( '-1 days' ) );
        if ( isset($params['filter']) && $params['filter'] == 'seven-day')
            $params['last_seven_day_time'] = date("Y-m-d", strtotime( '-7 days' ) );
        
        if ( isset($params['user_id']) && $params['user_id'] != '') {
            $params['receiver'] = $params['user_id'];
            unset($params['user_id']);
        }
        else $params['receiver'] = Auth::user()->id;
                    
        $params['paginate'] = 10;

        $notification = Notification::getNotifications($params)->ToArray();

        foreach ($notification['data'] as $key => $item) {
            $response = split_metadata_strings($item['metadata']);

            if (isset($item['slugs']) && $item['slugs'] == 'new-chat' ) {

                if ( array_key_exists('chat_id', $response) ) {
                    $get_data = array();
                    $get_data['detail'] = true;
                    $get_data['chat_id'] = $response['chat_id'];
                    $response_data = Chat::getChats($get_data);
                    $notification['data'][$key]['details'] = $response_data->toArray();
                }
                else {
                    $notification['data'][$key]['details'] = [];
                }
                
            }
            else if (isset($item['slugs']) && ( $item['slugs'] == 'assign-job' || $item['slugs'] == 'new-post'  || $item['slugs'] == 'new-bid' )) {

                if ( array_key_exists('post_id', $response) ) {
                    $get_data = array();
                    $get_data['detail'] = true;
                    $get_data['id'] = $response['post_id'];
                    $response_data = Post::getPost($get_data);
                    if($response_data){
                        $notification['data'][$key]['details'] = $response_data->toArray();
                    }else{
                        $notification['data'][$key]['details'] = [];
                    }
                }
                else {
                    $notification['data'][$key]['details'] = [];
                }
            }
        }

        $message = !empty($notification) ? 'Notifications retrieved successfully.' : 'Notifications not found against your query.';

        return $this->sendResponse($notification, $message);
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
            'text' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $notification = Notification::saveUpdateNotification($request_data);

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['notification_id'] = $notification['id'];

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
            'stars' => 'required',
            'description' => 'required'
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