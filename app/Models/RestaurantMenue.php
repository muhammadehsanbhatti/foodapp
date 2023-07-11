<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;

class RestaurantMenue extends Model
{
    use HasFactory;
  	use HasSku;
    public function restaurantFile()
    {
        return $this->hasMany(RestaurantFile::class,'restaurnat_menu_id');
    }

    public function skuOptions() : SkuOptions
    {
        return SkuOptions::make()
            ->from(['label', 'item_name'])
            ->target('sku')
            ->using('_')
            ->forceUnique(false)
            ->generateOnCreate(true)
            ->refreshOnUpdate(false);
    }
    public static function getRestaurantMenue($posted_data = array())
    {
        $query = RestaurantMenue::latest()
            ->with('restaurantFile')
        ;
        
        if (isset($posted_data['id'])) {
            $query = $query->where('restaurant_menues.id', $posted_data['id']);
        }
        if (isset($posted_data['restaurant_id'])) {
            $query = $query->where('restaurant_menues.restaurant_id', $posted_data['restaurant_id']);
        }
        if (isset($posted_data['item_name'])) {
            $query = $query->where('restaurant_menues.item_name', 'like', '%' . $posted_data['item_name'] . '%');
        }
        if (isset($posted_data['description'])) {
            $query = $query->where('restaurant_menues.description', $posted_data['description']);
        }
        if (isset($posted_data['regular_price'])) {
            $query = $query->where('restaurant_menues.regular_price', $posted_data['regular_price']);
        }
        if (isset($posted_data['sale_price'])) {
            $query = $query->where('restaurant_menues.sale_price', $posted_data['sale_price']);
        }
        if (isset($posted_data['stock'])) {
            $query = $query->where('restaurant_menues.stock', $posted_data['stock']);
        }
        if (isset($posted_data['sku'])) {
            $query = $query->where('restaurant_menues.sku', $posted_data['sku']);
        }
        if (isset($posted_data['category'])) {
            $query = $query->where('restaurant_menues.category', $posted_data['category']);
        }
        if (isset($posted_data['category_type'])) {
            $query = $query->where('restaurant_menues.category_type', $posted_data['category_type']);
        }

        $query->select('restaurant_menues.*');
        
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


    public function saveUpdateRestaurantMenue($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = RestaurantMenue::find($posted_data['update_id']);
        } else {
            $data = new RestaurantMenue;
        }

        if (isset($posted_data['restaurant_id'])) {
            $data->restaurant_id = $posted_data['restaurant_id'];
        }
        if (isset($posted_data['item_name'])) {
            $data->item_name = $posted_data['item_name'];
        }
        if (isset($posted_data['description'])) {
            $data->description = $posted_data['description'];
        }
        if (isset($posted_data['regular_price'])) {
            $data->regular_price = $posted_data['regular_price'];
        }
        if (isset($posted_data['sale_price'])) {
            $data->sale_price = $posted_data['sale_price'];
        }
        if (isset($posted_data['stock'])) {
            $data->stock = $posted_data['stock'];
        }
        if (isset($posted_data['category'])) {
            $data->category = $posted_data['category'];
        }
        if (isset($posted_data['category_type'])) {
            $data->category_type = $posted_data['category_type'];
        }

        $data->save();
        
        $data = RestaurantMenue::getRestaurantMenue([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }


    public function deleteRestaurantMenue($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = RestaurantMenue::find($id);
        }else{
            $data = RestaurantMenue::latest();
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
