<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    public static function getUserAddress($posted_data = array())
    {
        $query = UserAddress::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('user_addresses.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_addresses.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['country'])) {
            $query = $query->where('user_addresses.country', $posted_data['country']);
        }
        if (isset($posted_data['city'])) {
            $query = $query->where('user_addresses.city', $posted_data['city']);
        }
        if (isset($posted_data['address_type'])) {
            $query = $query->where('user_addresses.address_type', $posted_data['address_type']);
        }
        if (isset($posted_data['address'])) {
            $query = $query->where('user_addresses.address', $posted_data['address']);
        }
        
        $query->select('user_addresses.*');
        
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



    public function saveUpdateUserAddress($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserAddress::find($posted_data['update_id']);
        } else {
            $data = new UserAddress;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['country'])) {
            $data->country = $posted_data['country'];
        }
        if (isset($posted_data['city'])) {
            $data->city = $posted_data['city'];
        }
        if (isset($posted_data['address_type'])) {
            $data->address_type = $posted_data['address_type'];
        }
        if (isset($posted_data['address'])) {
            $data->address = $posted_data['address'];
        }
        
        $data->save();
        
        $data = UserAddress::getUserAddress([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteUserAddress($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserAddress::find($id);
        }else{
            $data = UserAddress::latest();
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
