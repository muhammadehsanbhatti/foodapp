<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCartInformation extends Model
{
    use HasFactory;

    public static function getPaymentCartInformation($posted_data = array())
    {
        $query = PaymentCartInformation::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('payment_cart_information.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('payment_cart_information.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['card_number'])) {
            $query = $query->where('payment_cart_information.card_number', $posted_data['card_number']);
        }
        if (isset($posted_data['exp_month'])) {
            $query = $query->where('payment_cart_information.exp_month', $posted_data['exp_month']);
        }
        if (isset($posted_data['exp_year'])) {
            $query = $query->where('payment_cart_information.exp_year', $posted_data['exp_year']);
        }
        if (isset($posted_data['payment_status'])) {
            $query = $query->where('payment_cart_information.payment_status', $posted_data['payment_status']);
        }

        $query->select('payment_cart_information.*');
        
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



    public function saveUpdatePaymentCartInformation($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = PaymentCartInformation::find($posted_data['update_id']);
        } else {
            $data = new PaymentCartInformation;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['card_number'])) {
            $data->card_number = $posted_data['card_number'];
        }
        if (isset($posted_data['exp_month'])) {
            $data->exp_month = $posted_data['exp_month'];
        }
        if (isset($posted_data['exp_year'])) {
            $data->exp_year = $posted_data['exp_year'];
        }
        if (isset($posted_data['payment_status'])) {
            $data->payment_status = $posted_data['payment_status'];
        }

        $data->save();
        
        $data = PaymentCartInformation::getPaymentCartInformation([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deletePaymentCartInformation($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = PaymentCartInformation::find($id);
        }else{
            $data = PaymentCartInformation::latest();
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
