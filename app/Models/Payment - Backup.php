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
            if(isset($posted_data['membership_id'])){
                $query = $query->where('payments.membership_id', $posted_data['membership_id']);
            }
            if(isset($posted_data['subscription_status'])){
                $query = $query->where('payments.subscription_status', $posted_data['subscription_status']);
            }
            if(isset($posted_data['response'])){
                $query = $query->where('payments.response', $posted_data['response']);
            }
            if(isset($posted_data['object'])){
                $query = $query->where('payments.object', $posted_data['object']);
            }
            if(isset($posted_data['amount_captured'])){
                $query = $query->where('payments.amount_captured', $posted_data['amount_captured']);
            }
            if(isset($posted_data['balance_transaction'])){
                $query = $query->where('payments.balance_transaction', $posted_data['balance_transaction']);
            }
            if(isset($posted_data['payment_intent'])){
                $query = $query->where('payments.payment_intent', $posted_data['payment_intent']);
            }
            if(isset($posted_data['payment_method'])){
                $query = $query->where('payments.payment_method', $posted_data['payment_method']);
            }
            if(isset($posted_data['customer'])){
                $query = $query->where('payments.customer', $posted_data['customer']);
            }
            if(isset($posted_data['currency'])){
                $query = $query->where('payments.currency', $posted_data['currency']);
            }
            if(isset($posted_data['stripe_plan_id'])){
                $query = $query->where('payments.stripe_plan_id', $posted_data['stripe_plan_id']);
            }
            if(isset($posted_data['stripe_sub_id'])){
                $query = $query->where('payments.stripe_sub_id', $posted_data['stripe_sub_id']);
            }
            if(isset($posted_data['stripe_prod_id'])){
                $query = $query->where('payments.stripe_prod_id', $posted_data['stripe_prod_id']);
            }
            if(isset($posted_data['created'])){
                $query = $query->where('payments.created', $posted_data['created']);
            }
            if(isset($posted_data['stripe_sub_cycle'])){
                $query = $query->where('payments.stripe_sub_cycle', $posted_data['stripe_sub_cycle']);
            }
        }

        $query->join('users', 'users.id', '=', 'payments.user_id');
        $query->join('pricing_plans', 'pricing_plans.id', '=', 'payments.membership_id');
        $query->select('payments.*', 'pricing_plans.title as membership_name', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'users.email as user_email', 'users.expiration_date');
        
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
