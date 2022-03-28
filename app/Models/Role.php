<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    // protected $table = 'roles';


    public function getRoles($posted_data = array())
    {
        $query = Role::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('roles.id', $posted_data['id']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('roles.name', 'like', '%' . $posted_data['name'] . '%');
        }

        $query->select('roles.*');
        
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
        return $result;
    }



    public function saveUpdateRole($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Role::find($posted_data['update_id']);
        } else {
            $data = new Role;
        }

        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }

        $data->save();
        return $data->id;
    }
}