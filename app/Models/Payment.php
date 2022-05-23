<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function getPayment($posted_data = array())
    {
        $query = Payment::latest();
        if(isset($posted_data) && count($posted_data)>0){
            if(isset($posted_data['id'])){
                $query = $query->where('payments.id', $posted_data['id']);
            }
            if(isset($posted_data['user_id'])){
                $query = $query->where('payments.user_id', $posted_data['user_id']);
            }
            if(isset($posted_data['amount_captured'])){
                $query = $query->where('payments.amount_captured', $posted_data['amount_captured']);
            }
            if(isset($posted_data['currency'])){
                $query = $query->where('payments.currency', $posted_data['currency']);
            }
            if(isset($posted_data['response_object'])){
                $query = $query->where('payments.response_object', $posted_data['response_object']);
            }
            if(isset($posted_data['payment_expiry'])){
                $query = $query->where('payments.payment_expiry', $posted_data['payment_expiry']);
            }
        }

        $query->join('users', 'users.id', '=', 'payments.user_id');
        // $query->join('pricing_plans', 'pricing_plans.id', '=', 'payments.membership_id');
        // $query->select('payments.*', 'pricing_plans.title as membership_name', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'users.email as user_email', 'users.expiration_date');
        $query->select('payments.*', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'users.email as user_email', 'users.account_expiry');
        
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
            }else if(isset($posted_data['count'])){
                $result = $query->count();
            }else{
                $result = $query->get();
            }
            // $result = $query->toSql();
        }
        return $result;
    }

    public function saveUpdatePayment($posted_data = array())
    {
        if(isset($posted_data['update_id'])){
            $data = Payment::find($posted_data['update_id']);
        }else{
            $data = new Payment;
        }

        if(isset($posted_data['user_id'])){
            $data->user_id = $posted_data['user_id'];
        }
        if(isset($posted_data['amount_captured'])){
            $data->amount_captured = $posted_data['amount_captured'];
        }
        if(isset($posted_data['currency'])){
            $data->currency = $posted_data['currency'];
        }
        if(isset($posted_data['response_object'])){
            $data->response_object = $posted_data['response_object'];
        }
        if(isset($posted_data['payment_expiry'])){
            $data->payment_expiry = $posted_data['payment_expiry'];
        }
        
        $data->save();
        return $data;
    }

    public function deletePayment($id=0)
    {
        $data = Payment::find($id);
        return $data->delete();
    }
}
