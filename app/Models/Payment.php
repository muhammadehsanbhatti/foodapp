<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;

class Payment extends Model
{
    use HasFactory;
 


    public static function getPayment($posted_data = array())
    {
        $query = Payment::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('payments.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('payments.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['paid_ammount'])) {
            $query = $query->where('payments.paid_ammount', $posted_data['paid_ammount']);
        }
        
        $query->select('payments.*');
        
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



    public function saveUpdatePayment($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Payment::find($posted_data['update_id']);
        } else {
            $data = new Payment;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['paid_ammount'])) {
            $data->paid_ammount = $posted_data['paid_ammount'];
        }
       
        $data->save();
        
        $data = Payment::getPayment([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deletePayment($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Payment::find($id);
        }else{
            $data = Payment::latest();
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
