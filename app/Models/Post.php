<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    // protected $fillable = [
    //     'user_id',
    //     'device_id',
    //     'device_token'
    // ];

    // db fields ==> id	service_id	customer_id	price	title	description	pay_with    status      created_at	updated_at	

    // protected $table = 'post_assets';

    
    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Models\User','customer_id')->with('role');
    }
    
    public function images()
    {
        return $this->hasMany(PostAssets::class)->where('asset_type', 'image')->with('filepond');
    }
    
    public function videos()
    {
        return $this->hasMany(PostAssets::class)->where('asset_type', 'video')->with('filepond');
        // return $this->hasMany(PostAssets::class)->where('asset_type', 'video');
    }
    
    public function assign_post()
    {
        return $this->hasOne('App\Models\AssignJob')->with('user');
    }
    
    public function PostAssets()
    {
        return $this->hasMany(PostAssets::class);
        // return $this->hasMany(PostAssets::class,'id');
    }

    public function AssignJob()
    {
        return $this->hasMany(AssignJob::class);
    }

    public function AssignJobHasOne()
    {
        return $this->hasOne(AssignJob::class);
    }

    public function getPost($posted_data = array()) {

        $query = post::with('service')->with('customer')->with('images')->with('videos')->with('assign_post');

        if(isset($posted_data['id'])){
            $query = $query->where('posts.id', $posted_data['id']);
        }
        if(isset($posted_data['service_id'])){
            $query = $query->where('posts.service_id', $posted_data['service_id']);
        }
        if(isset($posted_data['customer_id'])){
            $query = $query->where('posts.customer_id', $posted_data['customer_id']);
        }
        if(isset($posted_data['provider_id'])){
            $query = $query->where('assign_jobs.user_id', $posted_data['provider_id']);
        }
        if(isset($posted_data['price'])){
            $query = $query->where('posts.price', $posted_data['price']);
        }
        if(isset($posted_data['title'])){
            $query = $query->where('posts.title', 'like', '%' . $posted_data['title'] . '%');
        }
        if(isset($posted_data['description'])){
            $query = $query->where('posts.description', $posted_data['description']);
        }
        if(isset($posted_data['pay_with'])){
            $query = $query->where('posts.pay_with', $posted_data['pay_with']);
        }
        if(isset($posted_data['status'])){
            $query = $query->where('posts.status', $posted_data['status']);
        }
        if(isset($posted_data['status'])){
            $query = $query->where('posts.status', $posted_data['status']);
        }
        if(isset($posted_data['provider_id'])){
            $query->join('assign_jobs', 'assign_jobs.post_id', '=', 'posts.id');
        }
        
        if ( isset($posted_data['latitude']) && isset($posted_data['longitude']) ) {
            // $query = $query->select('posts.*', 'services.service_name', 'users.name as customer_name', DB::raw("(6373 * acos( 
            $query = $query->select('posts.*', DB::raw("(6373 * acos( 
                cos( radians(posts.lat) ) 
              * cos( radians( ".$posted_data['latitude']." ) ) 
              * cos( radians( ".$posted_data['longitude']." ) - radians(posts.long) ) 
              + sin( radians(posts.lat) ) 
              * sin( radians( ".$posted_data['latitude']." ) )
                ) ) as distance"));
        }else{
            if(isset($posted_data['columns'])){
                $query->select($posted_data['columns']);
            }
            else {
                $query->select('posts.*');
            }
            // $query->select('posts.*', 'services.service_name', 'users.name as customer_name');
        }
        if (isset($posted_data['near_by_radius'])) {
            $query = $query->having('distance', '<=', $posted_data['near_by_radius']);
        }
        
        $query->getQuery()->orders = null;
        if(isset($posted_data['orderBy_name'])){
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        }else{
            $query->orderBy('id', 'DESC');
        }

        if(isset($posted_data['paginate'])){
            $result = $query->paginate($posted_data['paginate']);
        }else{
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            } else if (isset($posted_data['array'])) {
                $result = $query->get()->ToArray();
            } else {
                $result = $query->get();
            }
        }


        // // $result = $query->toSql();
        // // echo '<pre>';
        // // print_r($result);
        // // exit;
        // if(isset($posted_data['web_paginate'])){
        //     $return_ary = array();
        //     $return_ary['web_pagination'] = $result;
        // }
 
        // $newResult = Post::associateRecords($result, $posted_data);
        // // if($newResult){
        //     if (!empty($result))
        //         $result = $result->toArray();

        //     if (isset($posted_data['paginate'])) {
        //         $result['data'] = $newResult;
        //     }else{
        //         $result = $newResult;
        //     }
        // // }
        // // echo '<pre>';
        // // print_r($result);
        // // exit;
        // if(isset($posted_data['web_paginate'])){
        //     $return_ary['records'] = $result;
        //     return $return_ary;
        // }
        return $result;
    }
    
    // public function associateRecords($result_ary, $posted_data)
    // {
    //     $res = array();
    //     if($result_ary){
    //         $result_ary = $result_ary->toArray();
    //         if (isset($posted_data['paginate'])) {
    //             $result_ary = $result_ary['data'];
    //         }else if (isset($posted_data['detail'])) {
    //             $conv_result_ary[] = $result_ary;
    //             $result_ary = $conv_result_ary;
    //         }
                
    //         if(isset($result_ary) && count($result_ary)>0){
    //             foreach($result_ary as $record){
    //                 $post = Post::find($record['id']);
    //                 if(isset($record['distance'])){
    //                     $post['distance'] = $record['distance'];
    //                 }
    //                 // $post['images'] = post::find($record['id'])->PostAssets()->where('asset_type', 'image')->get()->toArray();
    //                 // $post['videos'] = post::find($record['id'])->PostAssets()->where('asset_type', 'video')->get()->toArray();
    //                 // $service = Service::find($post['service_id']);
    //                 // $post = $post->service()->associate($service);
 
    //                 // $customer_user = User::find($post['customer_id']);
    //                 // $post = $post->User()->associate($customer_user);

    //                 // $cust_role = Role::find($customer_user['role']);
    //                 // $customer_user = $customer_user->role()->associate($cust_role); 
                    
    //                 $post['images'] = PostAssets::getPostAssets([
    //                     'post_id'=> $record['id'],
    //                     'asset_type'=> 'image',
    //                 ]);

    //                 $post['videos'] = PostAssets::getPostAssets([
    //                     'post_id'=> $record['id'],
    //                     'asset_type'=> 'video',
    //                 ]);


    //                 $service = Service::getServices([
    //                     'id'=> $record['service_id'],
    //                     'detail'=> true,
    //                 ]);
    //                 $user = User::getUser([
    //                     'id'=> $record['customer_id'],
    //                     'detail'=> true,
    //                 ]);
    //                 $post->user()->associate($user);
    //                 $post->service()->associate($service);


    //                 $post['customer'] = $post['user_id'];
    //                 unset($post['user_id']);
                    
    //                 if (isset($posted_data['detail'])) {
    //                     $res = $post->toArray();
    //                 }else{
    //                     $res[] = $post->toArray();
    //                 }
    //             }
    //         }
    //     }
    //     $response = $res;
    //     return $response; 
    // }

    public function saveUpdatePost($posted_data = array()) {
        if(isset($posted_data['update_id'])){
            $data = Post::find($posted_data['update_id']);
        }else{
            $data = new Post;
        }
        if(isset($posted_data['service_id'])){
            $data->service_id = $posted_data['service_id'];
        }
        if(isset($posted_data['customer_id'])){
            $data->customer_id = $posted_data['customer_id'];
        }
        if(isset($posted_data['service'])){
            $data->service_id = $posted_data['service'];
        }
        if(isset($posted_data['customer'])){
            $data->customer_id = $posted_data['customer'];
        }
        if(isset($posted_data['price'])){
            $data->price = $posted_data['price'];
        }
        if(isset($posted_data['title'])){
            $data->title = $posted_data['title'];
        }
        if(isset($posted_data['lat'])){
            $data->lat = $posted_data['lat'];
        }
        if(isset($posted_data['long'])){
            $data->long = $posted_data['long'];
        }
        if(isset($posted_data['address'])){
            $data->address = $posted_data['address'];
        }
        if(isset($posted_data['description'])){
            $data->description = $posted_data['description'];
        }
        if(isset($posted_data['pay_with'])){
            $data->pay_with = $posted_data['pay_with'];
        }
        if(isset($posted_data['status'])){
            $data->status = $posted_data['status'];
        }
        if(isset($posted_data['task_completion_date'])){
            $data->task_completion_date = $posted_data['task_completion_date'];
        }
        $data->save();
        return $data;
        // return $data->id;
    }

    public function deletePost($id=0) {
        $data = Post::find($id);
        return $data->delete();
    }
}