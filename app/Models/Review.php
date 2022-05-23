<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function senderDetails()
    {
        return $this->belongsTo('App\Models\User', 'sender_id')
            ->with('role')
            ->select(['id', 'role', 'name', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    public function receiverDetails()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id')
            ->with('role')
            ->select(['id', 'role', 'name', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    public function getReviews($posted_data = array())
    {
        $query = Review::latest();
        
        $query = $query->with('senderDetails')
                    ->with('receiverDetails');

        if (isset($posted_data['review_id'])) {
            $query = $query->where('reviews.id', $posted_data['review_id']);
        }

        if (isset($posted_data['sender_id'])) {
            $query = $query->where('reviews.sender_id', $posted_data['sender_id']);
        }

        if (isset($posted_data['receiver_id'])) {
            $query = $query->where('reviews.receiver_id', $posted_data['receiver_id']);
        }

        $query->select('reviews.*');
        
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

    public function saveUpdateReview($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Review::find($posted_data['update_id']);
        } else {
            $data = new Review;
        }

        if (isset($posted_data['sender_id'])) {
            $data->sender_id = $posted_data['sender_id'];
        }

        if (isset($posted_data['receiver_id'])) {
            $data->receiver_id = $posted_data['receiver_id'];
        }

        if (isset($posted_data['stars'])) {
            $data->stars = $posted_data['stars'];
        }

        if (isset($posted_data['description'])) {
            $data->description = $posted_data['description'];
        }

        $data->save();
        return $data;
        // return $data->id;
    }

    public function deleteReview($id=0)
    {
        $data = Review::find($id);
        return $data->delete();
    }
}