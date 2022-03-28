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

    public function PostAssets()
    {
        return $this->hasMany(PostAssets::class,'id');
    }


    public function getPost($posted_data = array()) {
        
        $query = Post::latest();

        if(isset($posted_data['id'])){
            $query = $query->where('posts.id', $posted_data['id']);
        }
        if(isset($posted_data['service_id'])){
            $query = $query->where('posts.service_id', $posted_data['service_id']);
        }
        if(isset($posted_data['customer_id'])){
            $query = $query->where('posts.customer_id', $posted_data['customer_id']);
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
        
        $query->join('services', 'services.id', '=', 'posts.service_id');
        $query->join('users', 'users.id', '=', 'posts.customer_id');


        

        
        if ( isset($posted_data['latitude']) && isset($posted_data['longitude']) ) {
            $query = $query->select('posts.*', 'services.service_name', 'users.name as customer_name', 'users.latitude', 'users.longitude', DB::raw("(6373 * acos( 
                cos( radians(users.latitude) ) 
              * cos( radians( ".$posted_data['latitude']." ) ) 
              * cos( radians( ".$posted_data['longitude']." ) - radians(users.longitude) ) 
              + sin( radians(users.latitude) ) 
              * sin( radians( ".$posted_data['latitude']." ) )
                ) ) as distance"));
        }else{
            $query->select('posts.*', 'services.service_name', 'users.name as customer_name');
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
            if(isset($posted_data['detail'])){
                $result = $query->first();
            }else{
                $result = $query->get();
            }
        }
        // $result = $query->toSql();
        // echo '<pre>';
        // print_r($result);
        // exit;
        return $result;
    }

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
        if(isset($posted_data['price'])){
            $data->price = $posted_data['price'];
        }
        if(isset($posted_data['title'])){
            $data->title = $posted_data['title'];
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
        $data->save();
        return $data->id;
    }

    public function deletePost($id=0) {
        $data = Post::find($id);
        return $data->delete();
    }
}