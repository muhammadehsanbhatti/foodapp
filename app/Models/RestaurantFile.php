<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantFile extends Model
{
    use HasFactory;
      public static function getRestaurantFile($posted_data = array())
    {
        $query = RestaurantFile::latest()
        // ->with('user')
        ;
        if (isset($posted_data['id'])) {
            $query = $query->where('restaurant_files.id', $posted_data['id']);
        }
        if (isset($posted_data['restaurnat_menu_id'])) {
            $query = $query->where('restaurant_files.restaurnat_menu_id', $posted_data['restaurnat_menu_id']);
        }
        if (isset($posted_data['restaurant_file'])) {
            $query = $query->where('restaurant_files.restaurant_file', $posted_data['restaurant_file']);
        }
        
        $query->select('restaurant_files.*');
        
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


    public function saveUpdateRestaurantFile($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = RestaurantFile::find($posted_data['update_id']);
        } else {
            $data = new RestaurantFile;
        }

        if (isset($posted_data['restaurnat_menu_id'])) {
            $data->restaurnat_menu_id = $posted_data['restaurnat_menu_id'];
        }
        if (isset($posted_data['restaurant_file'])) {
            $data->restaurant_file = $posted_data['restaurant_file'];
        }

        $data->save();
        
        $data = RestaurantFile::getRestaurantFile([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }


    public function deleteRestaurantFile($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = RestaurantFile::find($id);
        }else{
            $data = RestaurantFile::latest();
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
