<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
    
    public static function getBusiness($posted_data = array())
    {
        $query = Business::latest()
        // ->with('user')
        ;
        if (isset($posted_data['id'])) {
            $query = $query->where('businesses.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('businesses.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['business_name'])) {
            $query = $query->where('businesses.business_name', 'like', '%' . $posted_data['business_name'] . '%');
        }
        if (isset($posted_data['business_type'])) {
            $query = $query->where('businesses.business_type', $posted_data['business_type']);
        }
        if (isset($posted_data['restaurant_address'])) {
            $query = $query->where('businesses.restaurant_address', $posted_data['restaurant_address']);
        }
        if (isset($posted_data['cuisine_type'])) {
            $query = $query->where('businesses.cuisine_type', $posted_data['cuisine_type']);
        }

        $query->select('businesses.*');
        
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


    public function saveUpdateBusiness($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Business::find($posted_data['update_id']);
        } else {
            $data = new Business;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['business_name'])) {
            $data->business_name = $posted_data['business_name'];
        }
        if (isset($posted_data['business_type'])) {
            $data->business_type = $posted_data['business_type'];
        }
        if (isset($posted_data['restaurant_address'])) {
            $data->restaurant_address = $posted_data['restaurant_address'];
        }
        if (isset($posted_data['cuisine_type'])) {
            $data->cuisine_type = $posted_data['cuisine_type'];
        }

        $data->save();
        
        $data = Business::getBusiness([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }


    public function deleteBusiness($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Business::find($id);
        }else{
            $data = Business::latest();
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
