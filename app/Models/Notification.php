<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Notification extends Model
{
    use HasFactory;

    public function senderDetails()
    {
        return $this->belongsTo('App\Models\User', 'sender')
            ->with('role')
            ->select(['id', 'role', 'name', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    public function receiverDetails()
    {
        return $this->belongsTo('App\Models\User', 'receiver')
            ->with('role')
            ->select(['id', 'role', 'name', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    public function getNotifications($posted_data = array())
    {
        $query = Notification::latest();
       
        $query = $query->with('senderDetails')
                    ->with('receiverDetails');

        if (isset($posted_data['notification_id'])) {
            $query = $query->where('notifications.id', $posted_data['notification_id']);
        }
        if (isset($posted_data['sender'])) {
            $query = $query->where('notifications.sender', $posted_data['sender']);
        }
        if (isset($posted_data['receiver'])) {
            $query = $query->where('notifications.receiver', $posted_data['receiver']);
        }

        if (isset($posted_data['slugs'])) {
            $query = $query->where('notifications.slugs', $posted_data['slugs']);
        } else {
            $filter = array('new-chat');
            // $filter = array('assign-job', 'new-bid');
            $query = $query->whereNotIn('notifications.slugs', $filter);
        }

        if (isset($posted_data['notification_text'])) {
            $query = $query->where('notifications.notification_text', $posted_data['notification_text']);
        }
        if (isset($posted_data['seen_by'])) {
            $query = $query->where('notifications.seen_by', $posted_data['seen_by']);
        }
        if (isset($posted_data['metadata'])) {
            $query = $query->where('notifications.metadata', $posted_data['metadata']);
        }
        if (isset($posted_data['filter'])) {
            if ($posted_data['filter'] == 'today') {
                $query = $query->where('created_at', '>=', $posted_data['one_day_time']);
            }
            else if ($posted_data['filter'] == 'last-day') {
                $query = $query->where('created_at', '>=', $posted_data['last_day_time']);
            }
            else if ($posted_data['filter'] == 'seven-day') {
                $query = $query->where('created_at', '>=', $posted_data['last_seven_day_time']);
            }
            else {
                unset($posted_data['filter']);
            }
        }

        if (isset($posted_data['last_notification'])) {
            $posted_data['orderBy_name'] = 'id';
            $posted_data['orderBy_value'] = 'DESC';

            if(isset($posted_data['paginate'])) {
                unset($posted_data['paginate']);
            }

            $posted_data['detail'] = true;
        }

        $query->select('notifications.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'DESC');
        }

        if (isset($posted_data['groupBy_value'])) {
            // $query->groupBy($posted_data['groupBy_name'], $posted_data['groupBy_value']);
            $query->groupBy($posted_data['groupBy_value']);
        }

        if (isset($posted_data['print_query'])) {
            $result = $query->toSql();
            echo '<pre>';print_r($posted_data);'</pre>';
            echo '<pre>';print_r($result);'</pre>';exit;
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

    public function saveUpdateNotification($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Notification::find($posted_data['update_id']);
        } else {
            $data = new Notification;
        }

        if (isset($posted_data['sender'])) {
            $data->sender = $posted_data['sender'];
        }
        if (isset($posted_data['receiver'])) {
            $data->receiver = $posted_data['receiver'];
        }
        if (isset($posted_data['slugs'])) {
            $data->slugs = $posted_data['slugs'];
        }
        if (isset($posted_data['notification_text'])) {
            $data->notification_text = $posted_data['notification_text'];
        }
        if (isset($posted_data['seen_by'])) {
            $data->seen_by = $posted_data['seen_by'];
        }
        if (isset($posted_data['metadata'])) {
            $data->metadata = $posted_data['metadata'];
        }

        $data->save();
        return $data;
    }

    public function deleteNotification($id=0)
    {
        $data = Notification::find($id);
        return $data->delete();
    }
}