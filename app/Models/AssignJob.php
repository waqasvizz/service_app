<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignJob extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo('App\Models\User')->with('role', 'role');
    }

    public function post(){
        return $this->belongsTo('App\Models\Post')->with('service')->with('customer');
    }

    public function getAssignJobs($posted_data = array())
    {
        $query = AssignJob::latest();
        $query = $query->with('user')->with('post');

        if (isset($posted_data['id'])) {
            $query = $query->where('assign_jobs.id', $posted_data['id']);
        }

        if (isset($posted_data['user_id'])) {
            $query = $query->where('assign_jobs.user_id', $posted_data['user_id']);
        }

        if (isset($posted_data['post_id'])) {
            $query = $query->where('assign_jobs.post_id', $posted_data['post_id']);
        }


        $query->select('assign_jobs.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            } else {
                $result = $query->get();
            }
        }
        
        // $newResult = AssignJob::associateRecords($result, $posted_data);
        // // if($newResult){
        //     $result = $result->toArray();
        //     if (isset($posted_data['paginate'])) {
        //         $result['data'] = $newResult;
        //     }else{
        //         $result = $newResult;
        //     }
        // // }
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
    //                 $AssignJob = AssignJob::find($record['id']);
    //                 // $user = User::where('id',$record['user_id'])->first();
    //                 // $role = Role::where('id',$user['role'])->first();
    //                 // $user = $user->role()->associate($role);

    //                 $user = User::getUser([
    //                     'id'=> $record['user_id'],
    //                     'detail'=> true,
    //                 ]);

    //                 $post = Post::getPost([
    //                     'id'=> $record['post_id'],
    //                     'detail'=> true,
    //                 ]);

    //                 // echo '<pre>';
    //                 // print_r($post);
    //                 // exit;
                    
    //                 // $post = Post::where('id',$record['post_id'])->first();
    //                 // $service = Service::where('id',$post['service_id'])->first();
    //                 // $post = $post->service()->associate($service);


    //                 // $customer_user = User::where('id',$post['customer_id'])->first();
    //                 // $post = $post->user()->associate($customer_user);


    //                 // $customer_role = Role::where('id',$customer_user['role'])->first();
    //                 // $customer_user = $customer_user->role()->associate($customer_role);
                    
                    
    //                 $AssignJob->user()->associate($user);
    //                 $AssignJob->post()->associate($post);
                    
    //                 $AssignJob['user'] = $AssignJob['user_id'];
    //                 $AssignJob['post'] = $AssignJob['post_id'];
    //                 $AssignJob['user_id'] = $AssignJob['user']['id'];
    //                 $AssignJob['post_id'] = $AssignJob['post']['id'];
    //                 if (isset($posted_data['detail'])) {
    //                     $res = $AssignJob->toArray();
    //                 }else{
    //                     $res[] = $AssignJob->toArray();
    //                 }
    //             }
    //         }
    //     }
    //     $response = $res;
    //     return $response; 
    // }



    public function saveUpdateAssignJob($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = AssignJob::find($posted_data['update_id']);
        } else {
            $data = new AssignJob;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }

        if (isset($posted_data['post_id'])) {
            $data->post_id = $posted_data['post_id'];
        }

        $data->save();
        return $data->id;
    }
}