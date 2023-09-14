<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavMenu extends Model
{
    use HasFactory;
    public function restaurantMenue()
    {
        return $this->belongsTo(RestaurantMenue::class, 'restaurant_menue_id');
    }

    public static function getFavMenu($posted_data = array())
    {
        $query = FavMenu::latest()
                    ->with('restaurantMenue')
        ;
        if (isset($posted_data['id'])) {
            $query = $query->where('fav_menus.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('fav_menus.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['restaurant_menue_id'])) {
            $query = $query->where('fav_menus.restaurant_menue_id', $posted_data['restaurant_menue_id']);
        }
        if (isset($posted_data['ip_address'])) {
            $query = $query->where('fav_menus.ip_address', $posted_data['ip_address']);
        }
        
        $query->select('fav_menus.*');
        
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


    public function saveUpdateFavMenu($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = FavMenu::find($posted_data['update_id']);
        } else {
            $data = new FavMenu;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['restaurant_menue_id'])) {
            $data->restaurant_menue_id = $posted_data['restaurant_menue_id'];
        }
        if (isset($posted_data['ip_address'])) {
            $data->ip_address = $posted_data['ip_address'];
        }

        $data->save();
        
        $data = FavMenu::getFavMenu([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }


    public function deleteFavMenu($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = FavMenu::find($id);
        }else{
            $data = FavMenu::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['id'])) {
                $is_deleted = true;
                $data = $data->where('id', $where_posted_data['id']);
                $data = $data->where('user_id', $where_posted_data['user_id']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
