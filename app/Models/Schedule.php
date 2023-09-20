<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    public static function getSchedule($posted_data = array())
    {
        $query = Schedule::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('schedules.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('schedules.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['start_date'])) {
            $query = $query->where('schedules.start_date', $posted_data['start_date']);
        }
        if (isset($posted_data['end_date'])) {
            $query = $query->where('schedules.end_date', $posted_data['end_date']);
        }

        $query->select('schedules.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name']) && isset($posted_data['orderBy_value'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'DESC');
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
    
    public function saveUpdateSchedule($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Schedule::find($posted_data['update_id']);
        } else {
            $data = new Schedule;
        }


        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['start_date'])) {
            $data->start_date = $posted_data['start_date'];
        }
        if (isset($posted_data['end_date'])) {
            $data->end_date = $posted_data['end_date'];
        }

        $data->save();
        
        $data = Schedule::getSchedule([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteSchedule($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Schedule::find($id);
        }else{
            $data = Schedule::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['id'])) {
                $is_deleted = true;
                $data = $data->where('id', $where_posted_data['id']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
