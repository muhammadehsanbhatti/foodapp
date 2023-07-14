<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCuisine extends Model
{
    use HasFactory;

    public static function getBusinessCuisine($posted_data = array())
    {
        $query = BusinessCuisine::latest()
        // ->with('restaurantMenueFile')
        ;
        if (isset($posted_data['id'])) {
            $query = $query->where('business_cuisines.id', $posted_data['id']);
        }
        if (isset($posted_data['cuisine_name'])) {
            $query = $query->where('business_cuisines.cuisine_name', 'like', '%' . $posted_data['cuisine_name'] . '%');
        }
        if (isset($posted_data['cuisine_image'])) {
            $query = $query->where('business_cuisines.cuisine_image', $posted_data['cuisine_image']);
        }
        
        $query->select('business_cuisines.*');
        
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


    public function saveUpdateBusinessCuisine($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = BusinessCuisine::find($posted_data['update_id']);
        } else {
            $data = new BusinessCuisine;
        }

        if (isset($posted_data['cuisine_name'])) {
            $data->cuisine_name = $posted_data['cuisine_name'];
        }
        if (isset($posted_data['cuisine_image'])) {
            $data->cuisine_image = $posted_data['cuisine_image'];
        }

        $data->save();
        
        $data = BusinessCuisine::getBusinessCuisine([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }


    public function deleteBusinessCuisine($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = BusinessCuisine::find($id);
        }else{
            $data = BusinessCuisine::latest();
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
