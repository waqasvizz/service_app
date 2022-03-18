<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'zip_code', 'phone_number', 'website_link'
    ];


    public function getMosque($posted_data = array())
    {
        $query = Mosque::latest();

        if (isset($posted_data['name'])) {
            $query = $query->where('mosques.name', 'like', '%' . $posted_data['name'] . '%');
        }
        if (isset($posted_data['address'])) {
            $query = $query->where('mosques.address', $posted_data['address']);
        }
        if (isset($posted_data['zip_code'])) {
            $query = $query->where('mosques.zip_code', $posted_data['zip_code']);
        }
        if (isset($posted_data['phone_number'])) {
            $query = $query->where('mosques.phone_number', $posted_data['phone_number']);
        }
        if (isset($posted_data['website_link'])) {
            $query = $query->where('mosques.website_link', $posted_data['website_link']);
        }

        $query->select('mosques.*');
        
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



    public function saveUpdateMosque($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Mosque::find($posted_data['update_id']);
        } else {
            $data = new Mosque;
        }

        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }
        if (isset($posted_data['address'])) {
            $data->address = $posted_data['address'];
        }
        if (isset($posted_data['zip_code'])) {
            $data->zip_code = $posted_data['zip_code'];
        }
        if (isset($posted_data['phone_number'])) {
            $data->phone_number = $posted_data['phone_number'];
        }
        if (isset($posted_data['website_link'])) {
            $data->website_link = $posted_data['website_link'];
        }

        $data->save();
        return $data->id;
    }
}