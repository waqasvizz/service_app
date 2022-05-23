<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\AssignJob;
use App\Models\Notification;
use App\Models\FCM_Token;

class AssignJobController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $posted_data =  $request->all();
        $posted_data['paginate'] = 10;

        if (isset($params['post_id']))
            $posted_data['post_id'] = $params['post_id'];
        if (isset($params['provider_id']))
            $posted_data['user_id'] = $params['provider_id'];
        if (isset($params['per_page']))
            $posted_data['paginate'] = $params['per_page'];
        
        $AssignJobs = AssignJob::getAssignJobs($posted_data);
        $message = count($AssignJobs) > 0 ? 'Assign Jobs retrieved successfully.' : 'Assign Jobs not found against your query.';
        return $this->sendResponse($AssignJobs, $message);
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
        $rules = array(
            'provider_id' => 'required',
            'post_id' => 'required'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        } else {
            $AssignJob = AssignJob::where('post_id',$posted_data['post_id'])->first();
            $post_data = array();

            if($AssignJob){
                $user = User::find($posted_data['provider_id']);
                $AssignJob->user()->associate($user)->save();
                $post = Post::find($posted_data['post_id']);
                if(!empty($post))
                    $post_data = Post::saveUpdatePost(['update_id' => $posted_data['post_id'], 'status' => 2]);
                // $AssignJob->post()->associate($post)->save();
                $res = $AssignJob;
            }else{
                $post = Post::find($posted_data['post_id']);
                $assign_job = new AssignJob;
                $assign_job->user_id = $posted_data['provider_id'];
                $res = $post->AssignJobHasOne()->save($assign_job);
                if(!empty($post))
                    $post_data = Post::saveUpdatePost(['update_id' => $posted_data['post_id'], 'status' => 2]);
            }

            $get_data = array();
            $get_data['detail'] = true;
            $get_data['id'] = $post_data['id'];
            $data = Post::getPost($get_data);
            $model_response = $data->toArray();
            
            $post_id = $posted_data['post_id'];
            $notification_text = "You job offer has been accepted.";
    
            $notification_params = array();
            $notification_params['sender'] = auth()->user()->id;
            $notification_params['receiver'] = $posted_data['provider_id'];
            $notification_params['slugs'] = "assign-job";
            $notification_params['notification_text'] = $notification_text;
            $notification_params['seen_by'] = "";
            $notification_params['metadata'] = "post_id=$post_id";
    
            // $notification_params['receiver_devices'] = array_column($firebase_devices, 'device_token');
            // $response = Notification::saveUpdateNotification($notification_params);
            
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
            
            $notification = false;
            if ($response) {
                $notification = FCM_Token::sendFCM_Notification([
                    'title' => $notification_params['slugs'],
                    'body' => $notification_params['notification_text'],
                    'metadata' => $notification_params['metadata'],
                    'registration_ids' => $notification_params['registration_ids'],
                    'details' => $model_response
                ]);
            }
            
            return $this->sendResponse($res, 'Assign Job Successfully.');
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}