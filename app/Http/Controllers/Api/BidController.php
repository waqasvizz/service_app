<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Bid;
use App\Models\Notification;
use App\Models\FCM_Token;

class BidController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posted_data = $request->all();
        $posted_data['paginate'] = isset($posted_data['per_page'])? $posted_data['per_page']:10;
        
        $bids = Bid::getBids($posted_data);
        $message = count($bids) > 0 ? 'Bids retrieved successfully.' : 'Bids not found against your query.';

        return $this->sendResponse($bids, $message);
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
            'description' => 'required',
            'price' => 'required',
            'post_id' => 'required',
            'provider_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);       
        }
        
        $bid = Bid::saveUpdateBid($request_data);

        $get_data = array();
        $get_data['detail'] = true;
        $get_data['bid_id'] = $bid['id'];
        $bid_data = Bid::getBids($get_data);
        $model_response = $bid_data->toArray();

        $post_id = $request_data['post_id'];
        $bid_id = $bid['id'];
        $notification_text = "A new bid is posted on you job.";

        $notification_params = array();
        $notification_params['sender'] = auth()->user()->id;
        $notification_params['receiver'] = $model_response['post']['customer']['id'];
        $notification_params['slugs'] = "new-bid";
        $notification_params['notification_text'] = $notification_text;
        $notification_params['seen_by'] = "";
        $notification_params['metadata'] = "post_id=$post_id&"."bid_id=$bid_id";
        
        $response = Notification::saveUpdateNotification([
            'sender' => $notification_params['sender'],
            'receiver' => $notification_params['receiver'],
            'slugs' => $notification_params['slugs'],
            'notification_text' => $notification_params['notification_text'],
            'seen_by' => $notification_params['seen_by'],
            'metadata' => $notification_params['metadata']
        ]);

        $firebase_devices = FCM_Token::getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
        $notification_params['registration_ids'] = array_column($firebase_devices, 'device_token');

        if ($response) {

            if ( isset($model_response['user']) )
                unset($model_response['user']);
            if ( isset($model_response['post']) )
                unset($model_response['post']);

            $notification = FCM_Token::sendFCM_Notification([
                'title' => $notification_params['slugs'],
                'body' => $notification_params['notification_text'],
                'metadata' => $notification_params['metadata'],
                'registration_ids' => $notification_params['registration_ids'],
                'details' => $model_response
            ]);
        }
        
        return $this->sendResponse($bid, 'Bid created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bid = Bid::find($id);
  
        if (is_null($bid)) {
            $error_message['error'] = 'The bid is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
   
        return $this->sendResponse($bid, 'Bid retrieved successfully.');
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
            'description' => 'required',
            'price' => 'required',
            // 'post_id' => 'required',
            // 'provider_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['id'] = $id;
        $bid = Bid::getBids($posted_data);
        if(!$bid){
            $error_message['error'] = 'The bid is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
        
        $request_data['update_id'] = $id;
 
        $bid = Bid::saveUpdateBid($request_data); 
   
        return $this->sendResponse($bid, 'Bid updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Bid::find($id)){
            Bid::deleteBid($id); 
            return $this->sendResponse([], 'Bid deleted successfully.');
        }else{
            $error_message['error'] = 'The bid is not found.';
            return $this->sendError($error_message['error'], $error_message);
        } 
    }
}