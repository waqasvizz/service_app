<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo('App\Models\User', 'provider_id')->with('role');
    }

    public function post(){
        return $this->belongsTo('App\Models\Post')->with('service')->with('customer');
    }

    // public function post(){
    //     return $this->belongsTo('App\Models\Post');
    // }

    public function getBids($posted_data = array())
    {
        // $query = Bid::latest();
        $query = Bid::with('user')->with('post');

        if (isset($posted_data['bid_id'])) {
            $query = $query->where('bids.id', $posted_data['bid_id']);
        }
        if (isset($posted_data['provider_id'])) {
            $query = $query->where('bids.provider_id', $posted_data['provider_id']);
        }
        if (isset($posted_data['post_id'])) {
            $query = $query->where('bids.post_id', $posted_data['post_id']);
        }
        if (isset($posted_data['price'])) {
            $query = $query->where('bids.price', $posted_data['price']);
        }
        if (isset($posted_data['service_id'])) {
            $query = $query->where('posts.service_id', $posted_data['service_id']);
        }
        
        $query->leftjoin('posts', 'posts.id', '=', 'bids.post_id');

        $query->select('bids.*', 'posts.id', 'posts.service_id', 'posts.customer_id', );
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('bids.id', 'ASC');
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
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

        // $result = $query->toSql();
        
        // echo "Line no @"."<br>";
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";
        // exit("@@@@");
            
        // $newResult = Bid::associateRecords($result, $posted_data);
        // $result = $result->toArray();
        // if (isset($posted_data['paginate'])) {
        //     $result['data'] = $newResult;
        // }else{
        //     $result = $newResult;
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
    //                 $Bid = Bid::find($record['id']);

    //                 $user = User::getUser([
    //                     'id'=> $record['provider_id'],
    //                     'detail'=> true,
    //                 ]);

    //                 $post = Post::getPost([
    //                     'id'=> $record['post_id'],
    //                     'detail'=> true,
    //                 ]);
                    
    //                 $Bid->user()->associate($user);
    //                 $Bid->post()->associate($post);
                    
    //                 $Bid['provider'] = $Bid['user_id'];
    //                 $Bid['post'] = $Bid['post_id'];
    //                 $Bid['post_id'] = $Bid['post']['id'];
    //                 unset($Bid['user_id']);
    //                 if (isset($posted_data['detail'])) {
    //                     $res = $Bid->toArray();
    //                 }else{
    //                     $res[] = $Bid->toArray();
    //                 }
    //             }
    //         }
    //     }
    //     $response = $res;
    //     return $response; 
    // }



    public function saveUpdateBid($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Bid::find($posted_data['update_id']);
        } else {
            $data = new Bid;
        }

        if (isset($posted_data['provider_id'])) {
            $data->provider_id = $posted_data['provider_id'];
        }
        if (isset($posted_data['post_id'])) {
            $data->post_id = $posted_data['post_id'];
        }
        if (isset($posted_data['price'])) {
            $data->price = $posted_data['price'];
        }
        if (isset($posted_data['description'])) {
            $data->detail = $posted_data['description'];
        }
        $data->save();
        return $data;
    }

    public function deleteBid($id=0)
    {
        $data = Bid::find($id);
        return $data->delete();
    }
}