<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    public function getSetting()
    {
        $result = Setting::first(); 
        return $result;
    }

    public function updateSetting($posted_data = array())
    {
        $setting = Setting::find($posted_data['update_id']);

        if (isset($posted_data['stripe_mode'])) {
            $setting->stripe_mode = $posted_data['stripe_mode'];
        }
        if (isset($posted_data['stpk'])) {
            $setting->stpk = $posted_data['stpk'];
        }
        if (isset($posted_data['stsk'])) {
            $setting->stsk = $posted_data['stsk'];
        }
        if (isset($posted_data['slpk'])) {
            $setting->slpk = $posted_data['slpk'];
        }
        if (isset($posted_data['slsk'])) {
            $setting->slsk = $posted_data['slsk'];
        }
        if (isset($posted_data['paypal_mode'])) {
            $setting->paypal_mode = $posted_data['paypal_mode'];
        }
        if (isset($posted_data['pl_username'])) {
            $setting->pl_username = $posted_data['pl_username'];
        }
        if (isset($posted_data['pl_password'])) {
            $setting->pl_password = $posted_data['pl_password'];
        }
        if (isset($posted_data['pl_client_id'])) {
            $setting->pl_client_id = $posted_data['pl_client_id'];
        }
        if (isset($posted_data['pl_app_id'])) {
            $setting->pl_app_id = $posted_data['pl_app_id'];
        }
        if (isset($posted_data['pl_client_secret'])) {
            $setting->pl_client_secret = $posted_data['pl_client_secret'];
        }
        if (isset($posted_data['pt_username'])) {
            $setting->pt_username = $posted_data['pt_username'];
        }
        if (isset($posted_data['pt_password'])) {
            $setting->pt_password = $posted_data['pt_password'];
        }
        if (isset($posted_data['pt_client_id'])) {
            $setting->pt_client_id = $posted_data['pt_client_id'];
        }
        if (isset($posted_data['pt_app_id'])) {
            $setting->pt_app_id = $posted_data['pt_app_id'];
        }
        if (isset($posted_data['pt_client_secret'])) {
            $setting->pt_client_secret = $posted_data['pt_client_secret'];
        }

        return $setting->save();
    }

    public function detailSetting($id = 0)
    {
        $setting = setting::find($id);
        return $setting;
    }
    
    public function saveUpdateSetting($posted_data = array())
    {
        $result = Setting::first(); 
        if($result){
            $data = Setting::find($result->id);
        }else{
            $data = new Setting;
        }
        
        if (isset($posted_data['payment_commission'])) {
            $data->payment_commission = $posted_data['payment_commission'];
        }
        
        $data->save();
        return $data;
    }
}
