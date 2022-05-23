<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /*
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function Role()
    {
        return $this->belongsTo('App\Models\Role', 'role')
            ->select(['id', 'name']);
    }
    
    public function AssignService()
    {
        return $this->hasMany(AssignService::class);
    }

    public function AssignServiceHasOne()
    {
        return $this->hasOne(AssignService::class);
    }

    public function AssignJob()
    {
        return $this->hasMany('App\Models\AssignJob');
        // return $this->belongsToMany('App\Models\AssignJob');
    }

    public function fcm_tokens()
    {
        return $this->hasMany('App\Models\FCM_Token');
        // return $this->belongsToMany('App\Models\AssignJob');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }


    public function getUser($posted_data = array())
    {
        $query = User::latest();
        $query = $query->with('Role')->with('fcm_tokens');

        if (isset($posted_data['id'])) {
            $query = $query->where('users.id', $posted_data['id']);
        }
        if (isset($posted_data['email'])) {
            $query = $query->where('users.email', $posted_data['email']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('users.name', 'like', '%' . $posted_data['name'] . '%');
        }
        if (isset($posted_data['role'])) {
            $query = $query->where('users.role', $posted_data['role']);
        }
	    if (isset($posted_data['phone_number'])) {
            $query = $query->where('users.phone_number', $posted_data['phone_number']);
        }
        if (isset($posted_data['active_status'])) {
            $query = $query->where('users.active_status', $posted_data['active_status']);
        }

        // $query->join('roles', 'roles.id', '=', 'users.role');
        // $query->leftJoin('payments', function ($join) {
        //     $join->on('payments.user_id', '=', 'users.id');
        //     $join->on('payments.id', DB::raw('(SELECT MAX(payments.id) FROM payments WHERE `payments`.`user_id` = `users`.`id`)'));
        // });


        
        if ( isset($posted_data['latitude']) && isset($posted_data['longitude']) ) {
            // $query = $query->select('users.*', 'users.id as user_id', 'roles.name as role_name', DB::raw("(6373 * acos( 
            $query = $query->select('users.*', DB::raw("(6373 * acos( 
                cos( radians(users.latitude) ) 
              * cos( radians( ".$posted_data['latitude']." ) ) 
              * cos( radians( ".$posted_data['longitude']." ) - radians(users.longitude) ) 
              + sin( radians(users.latitude) ) 
              * sin( radians( ".$posted_data['latitude']." ) )
                ) ) as distance"));
        }else{
            $query->select('users.*');
            // $query->select('users.*', 'users.id as user_id', 'roles.name as role_name');
        }
        // $query->select('users.*', 'users.id as user_id', 'payments.*', 'payments.id as payment_id', 'roles.name as role_name');
        
        // $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('users.id', 'ASC');
        }
        
        $query =  $query->orderByDesc('created_at');
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

        // if(isset($posted_data['web_paginate'])){
        //     $return_ary = array();
        //     $return_ary['web_pagination'] = $result;
        // }
        // $newResult = User::associateRecords($result, $posted_data);
        // $result = $result->toArray();
        // if (isset($posted_data['paginate'])) {
        //     $result['data'] = $newResult;
        // }else{
        //     $result = $newResult;
        // }
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
                    
    //                 $user = User::find($record['id']);
    //                 $role = Role::where('id',$user['role'])->first();
    //                 $user = $user->role()->associate($role);

                    
    //                 $role = Role::where('id',$user['role'])->first();
                    
    //                 if (isset($posted_data['detail'])) {
    //                     $res = $user->toArray();
    //                 }else{
    //                     $res[] = $user->toArray();
    //                 }
    //                 $res['avg_rating'] = 5;
    //                 // echo "Line no @"."<br>";
    //                 // echo "<pre>";
    //                 // print_r($res['avg_rating']);
    //                 // echo "</pre>";
    //                 // exit("@@@@");
    //             }
    //         }
    //     }
    //     $response = $res;
    //     return $response; 
    // }



    public function saveUpdateUser($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = User::find($posted_data['update_id']);
        } else {
            $data = new User;
        }

        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }
        if (isset($posted_data['first_name'])) {
            $data->first_name = $posted_data['first_name'];
        }
        if (isset($posted_data['last_name'])) {
            $data->last_name = $posted_data['last_name'];
        }
        if (isset($posted_data['email'])) {
            $data->email = $posted_data['email'];
        }
        if (isset($posted_data['password'])) {
            $data->password = Hash::make($posted_data['password']);
        }
        if (isset($posted_data['role'])) {
            $data->role = $posted_data['role'];
        }
        if (isset($posted_data['user_type'])) {
            $data->user_type = $posted_data['user_type'];
        }
        if (isset($posted_data['account_expiry'])) {
            $data->account_expiry = $posted_data['account_expiry'];
        }
        if (isset($posted_data['address'])) {
            $data->address = $posted_data['address'];
        }
        if (isset($posted_data['latitude'])) {
            $data->latitude = $posted_data['latitude'];
        }
        if (isset($posted_data['longitude'])) {
            $data->longitude = $posted_data['longitude'];
        }
        if (isset($posted_data['phone_number'])) {
            $data->phone_number = $posted_data['phone_number'];
        }
        if (isset($posted_data['profile_image'])) {
            $data->profile_image = $posted_data['profile_image'];
        }
        if (isset($posted_data['license_document'])) {
            $data->license_document = $posted_data['license_document'];
        }
        if (isset($posted_data['insurance_document'])) {
            $data->insurance_document = $posted_data['insurance_document'];
        }
        if (isset($posted_data['account_status'])) {
            $data->account_status = $posted_data['account_status'];
        }
        if (isset($posted_data['active_status'])) {
            $data->active_status = $posted_data['active_status'];
        }
        if (isset($posted_data['avg_rating'])) {
            $data->avg_rating = $posted_data['avg_rating'];
        }
        if (isset($posted_data['experience'])) {
            $data->experience = $posted_data['experience'];
        }
        if (isset($posted_data['no_of_jobs_completed'])) {
            $data->no_of_jobs_completed = $posted_data['no_of_jobs_completed'];
        }
        $data->save();
        return $data;
    }

    public function deleteUser($id=0)
    {
        $data = User::find($id);
        return $data->delete();
    }
}