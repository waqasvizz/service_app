<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Constants;

class FCM_Token extends Model
{
    use HasFactory;

    protected $table = 'fcm_tokens';

    public function userDetails()
    {
        return $this->belongsTo('App\Models\User', 'user_id')
            ->with('role')
            ->select(['id', 'role', 'name', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    // public function receiverDetails()
    // {
    //     return $this->belongsTo('App\Models\User', 'receiver_id')
    //         ->with('role')
    //         ->select(['id', 'role', 'name', 'first_name', 'last_name', 'email', 'profile_image']);
    // }

    public function getFCM_Tokens($posted_data = array())
    {
        $query = FCM_Token::latest();
       
        $query = $query->with('userDetails');
        //             ->with('receiverDetails');

        if (isset($posted_data['user_id'])) {
            $query = $query->where('fcm_tokens.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['device_id'])) {
            $query = $query->where('fcm_tokens.device_id', $posted_data['device_id']);
        }
        if (isset($posted_data['device_token'])) {
            $query = $query->where('fcm_tokens.device_token', $posted_data['device_token']);
        }
        if (isset($posted_data['device_type'])) {
            $query = $query->where('fcm_tokens.device_type', $posted_data['device_type']);
        }

        if (isset($posted_data['last_chat'])) {
            $posted_data['orderBy_name'] = 'id';
            $posted_data['orderBy_value'] = 'DESC';

            if(isset($posted_data['paginate'])) {
                unset($posted_data['paginate']);
            }

            $posted_data['detail'] = true;
        }

        $query->select('fcm_tokens.*');
        
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

    public function sendFCM_Notification($posted_data = array())
    {
        if ( !empty($posted_data) ) {
           
            if ( !isset($posted_data['title']) )
                $posted_data['title'] = 'Notification';
                
            //     $title = 'New Text';
            // if ( isset($posted_data['title']) && $posted_data['title'] == 'assign-job' )
            //     $title = 'Job Offer Accepted';
            // if ( isset($posted_data['title']) && $posted_data['title'] == 'new-bid' )
            //     $title = 'New Bid Posted';

            $message = array(
                "title"         => $posted_data['title'],
                "body"          => $posted_data['body'],
                "metadata"      => $posted_data['metadata'],
                'detail'        => $posted_data['details']
            );
            
            $fields	= array(
                'registration_ids'      => $posted_data['registration_ids'],
                'priority' 		        => 'high',
                'data' 			        => $message,
                'notification'          => $message
            );

            $headers	= array(
                'Authorization: key='.Constants::FIREBASE_SERVER_API_KEY,
                'Content-Type: application/json',
            );

            #Send Reponse To FireBase Server
            $ch	= curl_init();

            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

            $response = curl_exec($ch);
            curl_close($ch);

            // ob_flush();

            if($response === false) {
                $fcm_response['status'] = false;
                // die('Curl failed ' . curl_error());
            }
            else {
                $fcm_response['status'] = true;
            }

            $fcm_response['response'] = $response;
            return $fcm_response;

            /*
                firebase sample response_object
                <pre>Array
                (
                    [status] => 1
                    [response] => {
                        "multicast_id":3249491517268760704,
                        "success":0,
                        "failure":1,
                        "canonical_ids":0,
                        "results":[
                            {
                                "error":"MessageTooBig"
                            }
                        ]
                    }
                )
                </pre>
            */
        }
    }

    public function saveUpdateFCM_Token($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = FCM_Token::find($posted_data['update_id']);
        } else {
            $data = new FCM_Token;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['device_id'])) {
            $data->device_id = $posted_data['device_id'];
        }
        if (isset($posted_data['device_token'])) {
            $data->device_token = $posted_data['device_token'];
        }
        if (isset($posted_data['device_type'])) {
            $data->device_type = $posted_data['device_type'];
        }

        $data->save();
        return $data;
    }

    public function deleteFCM_Token($id=0)
    {
        $data = FCM_Token::find($id);
        return $data->delete();
    }
}