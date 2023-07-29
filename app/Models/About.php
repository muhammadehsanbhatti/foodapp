<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    
    public static function getAbout($posted_data = array())
    {
        $query = About::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('abouts.id', $posted_data['id']);
        }
        if (isset($posted_data['message'])) {
            $query = $query->where('abouts.message', $posted_data['message']);
        }

        $query->select('abouts.*');
        
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



    public function saveUpdateAbout($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = About::find($posted_data['update_id']);
        } else {
            $data = new About;
        }


        if (isset($posted_data['message'])) {
            $data->message = $posted_data['message'];
        }

        $data->save();
        
        $data = About::getAbout([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteAbout($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = About::find($id);
        }else{
            $data = About::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['message'])) {
                $is_deleted = true;
                $data = $data->where('message', $where_posted_data['message']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
