<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    use HasFactory;
    public static function getNotificationMessage($posted_data = array())
    {
        $query = NotificationMessage::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('notification_messages.id', $posted_data['id']);
        }
        $query->select('notification_messages.*');

        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_email'])) {
            $query->orderBy($posted_data['orderBy_email'], $posted_data['orderBy_value']);
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
        
        if(isset($posted_data['printsql'])){
            $result = $query->toSql();
            echo '<pre>';
            print_r($result);
            print_r($posted_data);
            exit;
        }
        return $result;
    }

    public static function saveUpdateNotificationMessage($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = NotificationMessage::find($posted_data['update_id']);
        } else {
            $data = new NotificationMessage;
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['title'])) {
                $is_updated = true;
                $data = $data->where('title', $where_posted_data['title']);
            }

            if($is_updated){
                return $data->update($posted_data);
            }else{
                return false;
            }
        }

        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }

        if (isset($posted_data['message'])) {
            $data->message = $posted_data['message'];
        }

        $data->save();
        $data = NotificationMessage::getNotificationMessage([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }


    public function deleteNotificationMessage($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = NotificationMessage::find($id);
        }else{
            $data = NotificationMessage::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['title'])) {
                $is_deleted = true;
                $data = $data->where('title', $where_posted_data['title']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}