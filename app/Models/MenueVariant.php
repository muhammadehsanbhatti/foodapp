<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenueVariant extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public function restaurantVariants()
    // {
    //     return $this->belongsTo(RestaurantMenue::class, 'restaurant_menue_id');
    // }
    public static function getMenueVariant($posted_data = array())
    {
        $query = MenueVariant::latest()
                        // ->with('restaurantVariants')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('menue_variants.id', $posted_data['id']);
        }
        if (isset($posted_data['restaurant_menue_id'])) {
            $query = $query->where('menue_variants.restaurant_menue_id', $posted_data['restaurant_menue_id']);
        }
        if (isset($posted_data['variant_name'])) {
            $query = $query->where('menue_variants.variant_name', $posted_data['variant_name']);
        }
        if (isset($posted_data['variant_price'])) {
            $query = $query->where('menue_variants.variant_price', $posted_data['variant_price']);
        }
        if (isset($posted_data['variant_image'])) {
            $query = $query->where('menue_variants.variant_image', $posted_data['variant_image']);
        }
        if (isset($posted_data['menue_type'])) {
            $query = $query->where('menue_variants.menue_type', $posted_data['menue_type']);
        }
        if (isset($posted_data['variant_type'])) {
            $query = $query->where('menue_variants.variant_type', $posted_data['variant_type']);
        }

        $query->select('menue_variants.*');
        
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



    public function saveUpdateMenueVariant($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = MenueVariant::find($posted_data['update_id']);
        } else {
            $data = new MenueVariant;
        }

        if (isset($posted_data['restaurant_menue_id'])) {
            $data->restaurant_menue_id = $posted_data['restaurant_menue_id'];
        }
        if (isset($posted_data['variant_name'])) {
            $data->variant_name = $posted_data['variant_name'];
        }
        if (isset($posted_data['variant_price'])) {
            $data->variant_price = $posted_data['variant_price'];
        }
        if (isset($posted_data['variant_image'])) {
            $data->variant_image = $posted_data['variant_image'];
        }
        if (isset($posted_data['menue_type'])) {
            $data->menue_type = $posted_data['menue_type'];
        }
        if (isset($posted_data['variant_type'])) {
            $data->variant_type = $posted_data['variant_type'];
        }

        $data->save();
        
        $data = MenueVariant::getMenueVariant([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteMenueVariant($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = MenueVariant::find($id);
        }else{
            $data = MenueVariant::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['name'])) {
                $is_deleted = true;
                $data = $data->where('name', $where_posted_data['name']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
