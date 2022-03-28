<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    public function getBids($posted_data = array())
    {
        $query = Bid::latest();

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
        $query->select('bids.*');
        
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
        return $result;
    }



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
            $data->description = $posted_data['description'];
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