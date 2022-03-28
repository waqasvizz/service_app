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
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

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


    public function AssignService()
    {
        return $this->hasMany(AssignService::class);
    }

    public function AssignServiceHasOne()
    {
        return $this->hasOne(AssignService::class);
    }


    public function getUser($posted_data = array())
    {
        $query = User::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('users.id', $posted_data['id']);
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

        $query->join('roles', 'roles.id', '=', 'users.role');
        // $query->leftJoin('payments', function ($join) {
        //     $join->on('payments.user_id', '=', 'users.id');
        //     $join->on('payments.id', DB::raw('(SELECT MAX(payments.id) FROM payments WHERE `payments`.`user_id` = `users`.`id`)'));
        // });


        
        if ( isset($posted_data['latitude']) && isset($posted_data['longitude']) ) {
            $query = $query->select('users.*', 'users.id as user_id', 'roles.name as role_name', DB::raw("(6373 * acos( 
                cos( radians(users.latitude) ) 
              * cos( radians( ".$posted_data['latitude']." ) ) 
              * cos( radians( ".$posted_data['longitude']." ) - radians(users.longitude) ) 
              + sin( radians(users.latitude) ) 
              * sin( radians( ".$posted_data['latitude']." ) )
                ) ) as distance"));
        }else{
            $query->select('users.*', 'users.id as user_id', 'roles.name as role_name');
        }
        // $query->select('users.*', 'users.id as user_id', 'payments.*', 'payments.id as payment_id', 'roles.name as role_name');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('users.id', 'ASC');
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
        return $result;
    }



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
        if (isset($posted_data['email'])) {
            $data->email = $posted_data['email'];
        }
        if (isset($posted_data['password'])) {
            $data->password = Hash::make($posted_data['password']);
        }
        if (isset($posted_data['role'])) {
            $data->role = $posted_data['role'];
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
        $data->save();
        return $data;
    }

    public function deleteUser($id=0)
    {
        $data = User::find($id);
        return $data->delete();
    }
}