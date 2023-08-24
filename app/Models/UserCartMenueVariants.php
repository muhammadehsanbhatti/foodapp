<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCartMenueVariants extends Model
{
    use HasFactory;
    // public function userVariants()
    // {
    //     return $this->belongsTo(AddToCart::class, 'add_to_cart_id');
    // }
    public static function getUserCartMenueVariants($posted_data = array())
    {
        $query = UserCartMenueVariants::latest()
                                    // ->with('userVariants')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_cart_menue_variants.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_cart_menue_variants.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['add_to_cart_id'])) {
            $query = $query->where('user_cart_menue_variants.add_to_cart_id', $posted_data['add_to_cart_id']);
        }
        
        if (isset($posted_data['menue_variant_id_in'])) {
            $query = $query->whereIn('user_cart_menue_variants.menue_variant_id', $posted_data['menue_variant_id_in']);
        }
        if (isset($posted_data['menue_variant_id'])) {
            $query = $query->where('user_cart_menue_variants.menue_variant_id', $posted_data['menue_variant_id']);
        }
        
        $query->select('user_cart_menue_variants.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name']) && isset($posted_data['orderBy_value'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
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



    public function saveUpdateUserCartMenueVariants($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserCartMenueVariants::find($posted_data['update_id']);
        } else {
            $data = new UserCartMenueVariants;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['add_to_cart_id'])) {
            $data->add_to_cart_id = $posted_data['add_to_cart_id'];
        }
        if (isset($posted_data['menue_variant_id'])) {
            $data->menue_variant_id = $posted_data['menue_variant_id'];
        }
        
        $data->save();
        
        $data = UserCartMenueVariants::getUserCartMenueVariants([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteUserCartMenueVariants($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserCartMenueVariants::find($id);
        }else{
            $data = UserCartMenueVariants::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['menue_variant_id'])) {
                $is_deleted = true;
                $data = $data->where('menue_variant_id', $where_posted_data['menue_variant_id']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}