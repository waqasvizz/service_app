<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use DB;
use Exception;
use Illuminate\Support\Facades\Crypt;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('settings')->count() == 0){
                DB::table('settings')->insert([
                    [
                        'stripe_mode' => 'test',
                        'stpk' => Crypt::encryptString('pk_test_51J0hUGEKvnwXN2Rmr5PEgnZXrrhHyO2FAEWOftouQ78Gmm7Brx3DyAPkYagm6mrjZK7ns2kQVJRyRHxxOnZJrOAN00ELzKl4YB'),
                        'stsk' => Crypt::encryptString('sk_test_51J0hUGEKvnwXN2RmM5vdXJbs1nglysZinMvsF4R7jVGkZsQydbj7tyjoHO58mj73uCBhlXsCI7KpPUmifUvCX6dF00rZocq0Ee'),
                        'slpk' => '',
                        'slsk' => '',
                        'paypal_mode' => 'test',
                        'pl_username' => '',
                        'pl_password' => '',
                        'pl_client_id' => '',
                        'pl_app_id' => '',
                        'pl_client_secret' => '',
                        'pt_username' => Crypt::encryptString('sb-bghzg14098532_api1.business.example.com'),
                        'pt_password' => Crypt::encryptString('4T5TV2T2ZMRGHN4R'),
                        'pt_client_id' => Crypt::encryptString('AaFNe1Lr4ITR16GEOhJ-WioO_x7rJcPyYq1p82vxWal21diksaAftNno2FSWNc3KaYmRgwkyTPjlbBrv'),
                        'pt_app_id' => Crypt::encryptString('APP-80W284485P519543T'),
                        'pt_client_secret' => Crypt::encryptString('AUpkxjVY1uHxAtj6MRweKuqkxhm-AFDh9vLyZP5eb.h1hS83FGDWw.z3'),
                    ],
                    // [
                    //     'meta_key' => '_stripe_mode',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_stripe_test_publishable_key',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_stripe_test_secret_key',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_stripe_live_secret_key',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_stripe_live_secret_key',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_mode',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_live_username',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_live_password',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_live_client_id',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_live_app_id',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_live_client_secret',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_test_username',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_test_password',
                    //     'meta_value' => '',
                    // ],
                    // [
                    //     'meta_key' => '_paypal_test_client_id',
                    //     'meta_value' => '',
                    // ],

                    // [
                    //     'meta_key' => '_paypal_test_app_id',
                    //     'meta_value' => '',
                    // ],

                    // [
                    //     'meta_key' => '_paypal_test_client_secret',
                    //     'meta_value' => '',
                    // ],
                ]);
            } else { echo "<br>[Setting Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}