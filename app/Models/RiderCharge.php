<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderCharge extends Model
{
    use HasFactory;

    public static function getRiderCharge($posted_data = array())
    {
        $query = RiderCharge::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('rider_charges.id', $posted_data['id']);
        }
        if (isset($posted_data['per_killometer_price'])) {
            $query = $query->where('rider_charges.per_killometer_price', $posted_data['per_killometer_price']);
        }

        $query->select('rider_charges.*');
        
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



    public function saveUpdateRiderCharge($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = RiderCharge::find($posted_data['update_id']);
        } else {
            $data = new RiderCharge;
        }

        if (isset($posted_data['per_killometer_price'])) {
            $data->per_killometer_price = $posted_data['per_killometer_price'];
        }


        $data->save();
        
        $data = RiderCharge::getRiderCharge([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteRiderCharge($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = RiderCharge::find($id);
        }else{
            $data = RiderCharge::latest();
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
