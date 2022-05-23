<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function getServiceImageAttribute($value)
    {
        return $value;
        // return url('/')."/".$value;
        // return public_path()."/".$value;
    }

    public function getServices($posted_data = array())
    {
        $query = Service::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('services.id', $posted_data['id']);
        }
        if (isset($posted_data['service_name'])) {
            $query = $query->where('services.service_name', 'like', '%' . $posted_data['service_name'] . '%');
        }

        if(isset($posted_data['columns'])){
            $query->select($posted_data['columns']);
        }
        else {
            $query->select('services.*');
        }
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
        }
        
        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        }
        else {
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

    public function saveUpdateService($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Service::find($posted_data['update_id']);
        } else {
            $data = new Service;
        }

        if (isset($posted_data['service_name'])) {
            $data->service_name = $posted_data['service_name'];
        }

        if (isset($posted_data['service_image'])) {
            $data->service_image = $posted_data['service_image'];
        }

        if (isset($posted_data['service_description'])) {
            $data->service_description = $posted_data['service_description'];
        }

        $data->save();
        return $data->toArray();
        // return $data->id;
    }

    public function deleteService($id=0)
    {
        $data = Service::find($id);
        return $data->delete();
    }
}