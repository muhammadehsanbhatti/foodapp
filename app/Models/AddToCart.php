<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;
    public function restaurantMenue()
    {
        return $this->belongsTo(RestaurantMenue::class, 'restaurant_menue_id')
                            ->with('restaurantFile')
                            ->with('restaurantMenueVariant');
    }
    public static function getAddToCart($posted_data = array())
    {
        $query = AddToCart::latest()
                        ->with('restaurantMenue')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('add_to_carts.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('add_to_carts.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['restaurant_menue_id'])) {
            $query = $query->where('add_to_carts.restaurant_menue_id', $posted_data['restaurant_menue_id']);
        }
        if (isset($posted_data['session_id'])) {
            $query = $query->where('add_to_carts.session_id', $posted_data['session_id']);
        }
        if (isset($posted_data['quantity'])) {
            $query = $query->where('add_to_carts.quantity', $posted_data['quantity']);
        }
        if (isset($posted_data['user_checkout_id'])) {
            $query = $query->where('add_to_carts.user_checkout_id', $posted_data['user_checkout_id']);
        }
        $query->select('add_to_carts.*');
        
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



    public function saveUpdateAddToCart($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = AddToCart::find($posted_data['update_id']);
        } else {
            $data = new AddToCart;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['restaurant_menue_id'])) {
            $data->restaurant_menue_id = $posted_data['restaurant_menue_id'];
        }
        if (isset($posted_data['session_id'])) {
            $data->session_id = $posted_data['session_id'];
        }
        if (isset($posted_data['quantity'])) {
            $data->quantity = $posted_data['quantity'];
        }
        if (isset($posted_data['user_checkout_id'])) {
            $data->user_checkout_id = $posted_data['user_checkout_id'];
        }
        $data->save();
        
        $data = AddToCart::getAddToCart([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteAddToCart($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = AddToCart::find($id);
        }else{
            $data = AddToCart::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['restaurant_menue_id'])) {
                $is_deleted = true;
                $data = $data->where('restaurant_menue_id', $where_posted_data['restaurant_menue_id']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
