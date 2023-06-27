<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [ 
        'seen_at',
    ];

    public function senderDetails()
    {
        return $this->belongsTo(User::class, 'sender_id')
            ->with('role')
            ->select(['id', 'role', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    public function receiverDetails()
    {
        return $this->belongsTo(User::class, 'receiver_id')
            ->with('role')
            ->select(['id', 'role', 'first_name', 'last_name', 'email', 'profile_image']);
    }

    public static function getNotifications($posted_data = array())
    {
        $query = Notification::latest();
       
        $query = $query->with('senderDetails')
                    ->with('receiverDetails');

        if (isset($posted_data['id'])) {
            $query = $query->where('notifications.id', $posted_data['id']);
        }
        if (isset($posted_data['notification_message_id'])) {
            $query = $query->where('notifications.notification_message_id', $posted_data['notification_message_id']);
        }
        if (isset($posted_data['sender_id'])) {
            $query = $query->where('notifications.sender_id', $posted_data['sender_id']);
        }
        if (isset($posted_data['receiver_id'])) {
            $query = $query->where('notifications.receiver_id', $posted_data['receiver_id']);
        }

        if (isset($posted_data['notification_text'])) {
            $query = $query->where('notifications.notification_text', $posted_data['notification_text']);
        }
        if (isset($posted_data['seen_at'])) {
            $query = $query->where('notifications.seen_at', $posted_data['seen_at']);
        }
        if (isset($posted_data['meta_data'])) {
            $query = $query->where('notifications.meta_data', $posted_data['meta_data']);
        }
        if (isset($posted_data['unread'])) {
            $query = $query->whereNull('notifications.seen_at');
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
        if (isset($posted_data['orderBy_name']) && isset($posted_data['orderBy_value'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'DESC');
        }

        if (isset($posted_data['groupBy_value'])) {
            // $query->groupBy($posted_data['groupBy_name'], $posted_data['groupBy_value']);
            $query->groupBy($posted_data['groupBy_value']);
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
        
        if(isset($posted_data['printsql'])){
            $result = $query->toSql();
            echo '<pre>';
            print_r($result);
            print_r($posted_data);
            exit;
        }


        return $result;
    }
 

    public static function saveUpdateNotification($posted_data = array())
    {
        $update_data = array();
        if (isset($posted_data['update_id'])) {
            $data = Notification::find($posted_data['update_id']);
        }else if (isset($posted_data['whereUpdate'])) {
            $data = Notification::latest();
        } else {
            $data = new Notification;
        }

        if (isset($posted_data['whereUpdate'])) {
            if (isset($posted_data['where_meta_data'])) { 
                $data = $data->where('meta_data', 'like', '%' . $posted_data['where_meta_data'] . '%');
            }
            if (isset($posted_data['where_seen_at_null'])) {
                $data = $data->whereNull('seen_at');
            }
            if (isset($posted_data['receiver_id'])) {
                $data = $data->where('receiver_id', $posted_data['receiver_id']);
            }
        }

        if (isset($posted_data['sender_id'])) {
            $update_data['sender_id'] = $posted_data['sender_id'];
            
            if (!isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
                $data->sender_id = $posted_data['sender_id'];
            }
        }
        if (isset($posted_data['receiver_id'])) {
            $update_data['receiver_id'] = $posted_data['receiver_id'];
            
            if (!isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
                $data->receiver_id = $posted_data['receiver_id'];
            }
        }
        if (isset($posted_data['notification_message_id'])) {
            $update_data['notification_message_id'] = $posted_data['notification_message_id'];
            
            if (!isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
                $data->notification_message_id = $posted_data['notification_message_id'];
            }
        }
        if (isset($posted_data['notification_text'])) {
            $update_data['notification_text'] = $posted_data['notification_text'];
            
            if (!isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
                $data->notification_text = $posted_data['notification_text'];
            }
        }
        if (isset($posted_data['seen_at'])) {
            $update_data['seen_at'] = $posted_data['seen_at'];
            
            if (!isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
                $data->seen_at = $posted_data['seen_at'];
            }
        }
        if (isset($posted_data['meta_data'])) {
            $update_data['meta_data'] = $posted_data['meta_data'];
            
            if (!isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
                $data->meta_data = $posted_data['meta_data'];
            }
        }

        // if (isset($posted_data['print_query'])) {
            // $result = $data->toSql();
            // echo '<pre>';print_r($posted_data);'</pre>';
            // echo '<pre>';print_r($result);'</pre>';exit;
        // }

        
        if (isset($posted_data['update_id']) || !isset($posted_data['whereUpdate'])) {
            $data->save();
        
            $data = Notification::getNotifications([
                'detail' => true,
                'id' => $data->id
            ]);
            return $data;

        }else{
            $data->update($update_data);
        }
        
        // $data->save($update_data);
        return $data;
    }

    public static function deleteNotification($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Notification::find($id);
        }else{
            $data = Notification::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['sender_id'])) {
                $is_deleted = true;
                $data = $data->where('sender_id', $where_posted_data['sender_id']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }

    public static function sendNotification($posted_data = array())
    {
        
        $response = array();
        if(isset($posted_data['notification_message_id'])){
           
            $not_msg_detail = NotificationMessage::getNotificationMessage([
                'id' => $posted_data['notification_message_id'],
                'detail' => true
            ]);
            // echo '<pre>';print_r($not_msg_detail);echo '</pre>';exit;
            if($not_msg_detail){

                $response = Notification::saveUpdateNotification([
                    'sender_id' => $posted_data['sender_id'],
                    'receiver_id' => $posted_data['receiver_id'],
                    'notification_message_id' => $not_msg_detail->id,
                    'notification_text' => $not_msg_detail->message,
                    'meta_data' => $posted_data['meta_data']
                ]);
                // echo '<pre>';print_r($response);echo '</pre>';exit;

                $firebase_devices = FcmToken::getFcmTokens(['user_id' => $posted_data['receiver_id']])->toArray();
                $posted_data['registration_ids'] = array_column($firebase_devices, 'device_token');
                $posted_data['device_ids'] = array_column($firebase_devices, 'device_id');
                
                $notification = false;
                if ($response) {
                    $notification = FcmToken::sendFcmNotification([
                        'title' => $not_msg_detail->title,
                        'body' => $not_msg_detail->message,
                        'metadata' => $posted_data['meta_data'],
                        'registration_ids' => $posted_data['registration_ids'],
                        'device_ids' => $posted_data['device_ids'],
                        'details' => $response
                    ]);
                }

            }

        }
        return $response;
    }
}