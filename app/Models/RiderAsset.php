<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderAsset extends Model
{
    use HasFactory;
        
    public static function getRiderAsset($posted_data = array())
    {
        $query = RiderAsset::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('rider_assets.id', $posted_data['id']);
        }
        if (isset($posted_data['vechicle_id'])) {
            $query = $query->where('rider_assets.vechicle_id', $posted_data['vechicle_id']);
        }
        if (isset($posted_data['vechicle_image'])) {
            $query = $query->where('rider_assets.vechicle_image', $posted_data['vechicle_image']);
        }
        if (isset($posted_data['asset_type'])) {
            $query = $query->where('rider_assets.asset_type', $posted_data['asset_type']);
        }

        $query->select('rider_assets.*');
        
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



    public function saveUpdateRiderAsset($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = RiderAsset::find($posted_data['update_id']);
        } else {
            $data = new RiderAsset;
        }


        if (isset($posted_data['vechicle_id'])) {
            $data->vechicle_id = $posted_data['vechicle_id'];
        }
        if (isset($posted_data['vechicle_image'])) {
            $data->vechicle_image = $posted_data['vechicle_image'];
        }
        if (isset($posted_data['asset_type'])) {
            $data->asset_type = $posted_data['asset_type'];
        }

        $data->save();
        
        $data = RiderAsset::getRiderAsset([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteRiderAsset($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = RiderAsset::find($id);
        }else{
            $data = RiderAsset::latest();
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
