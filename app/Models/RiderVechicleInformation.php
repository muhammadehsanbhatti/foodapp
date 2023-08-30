<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderVechicleInformation extends Model
{
    use HasFactory;

        
    public static function getRiderVechicleInformation($posted_data = array())
    {
        $query = RiderVechicleInformation::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('rider_vechicle_information.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('rider_vechicle_information.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['company'])) {
            $query = $query->where('rider_vechicle_information.company', $posted_data['company']);
        }
        if (isset($posted_data['color'])) {
            $query = $query->where('rider_vechicle_information.color', $posted_data['color']);
        }
        if (isset($posted_data['model'])) {
            $query = $query->where('rider_vechicle_information.model', $posted_data['model']);
        }
        if (isset($posted_data['vechicle_number'])) {
            $query = $query->where('rider_vechicle_information.vechicle_number', $posted_data['vechicle_number']);
        }
        if (isset($posted_data['vechicle_condition'])) {
            $query = $query->where('rider_vechicle_information.vechicle_condition', $posted_data['vechicle_condition']);
        }
        if (isset($posted_data['vechicle_type'])) {
            $query = $query->where('rider_vechicle_information.vechicle_type', $posted_data['vechicle_type']);
        }

        $query->select('rider_vechicle_information.*');
        
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
    
    public function saveUpdateRiderVechicleInformation($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = RiderVechicleInformation::find($posted_data['update_id']);
        } else {
            $data = new RiderVechicleInformation;
        }


        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['company'])) {
            $data->company = $posted_data['company'];
        }
        if (isset($posted_data['color'])) {
            $data->color = $posted_data['color'];
        }
        if (isset($posted_data['model'])) {
            $data->model = $posted_data['model'];
        }
        if (isset($posted_data['vechicle_number'])) {
            $data->vechicle_number = $posted_data['vechicle_number'];
        }
        if (isset($posted_data['vechicle_condition'])) {
            $data->vechicle_condition = $posted_data['vechicle_condition'];
        }
        if (isset($posted_data['vechicle_type'])) {
            $data->vechicle_type = $posted_data['vechicle_type'];
        }

        $data->save();
        
        $data = RiderVechicleInformation::getRiderVechicleInformation([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteRiderVechicleInformation($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = RiderVechicleInformation::find($id);
        }else{
            $data = RiderVechicleInformation::latest();
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
