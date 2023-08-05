<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;

class PaymentHistroy extends Model
{
    use HasFactory;
    use HasSku;

    
    public function skuOptions() : SkuOptions
    {
        return SkuOptions::make()
            ->from(['label', 'customer_name'])
            ->target('payment_sku')
            ->using('_')
            ->forceUnique(false)
            ->generateOnCreate(true)
            ->refreshOnUpdate(false);
    }

    public static function getPaymentHistroy($posted_data = array())
    {
        $query = PaymentHistroy::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('payment_histroys.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('payment_histroys.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['restaurant_id'])) {
            $query = $query->where('payment_histroys.restaurant_id', $posted_data['restaurant_id']);
        }
        if (isset($posted_data['user_address_id'])) {
            $query = $query->where('payment_histroys.user_address_id', $posted_data['user_address_id']);
        }
        if (isset($posted_data['customer_name'])) {
            $query = $query->where('payment_histroys.customer_name', $posted_data['customer_name']);
        }
        if (isset($posted_data['amount_captured'])) {
            $query = $query->where('payment_histroys.amount_captured', $posted_data['amount_captured']);
        }
        if (isset($posted_data['currency'])) {
            $query = $query->where('payment_histroys.currency', $posted_data['currency']);
        }
        if (isset($posted_data['item_delivered_quantity'])) {
            $query = $query->where('payment_histroys.item_delivered_quantity', $posted_data['item_delivered_quantity']);
        }
        if (isset($posted_data['payment_status'])) {
            $query = $query->where('payment_histroys.payment_status', $posted_data['payment_status']);
        }
        if (isset($posted_data['payment_sku'])) {
            $query = $query->where('payment_histroys.payment_sku', $posted_data['payment_sku']);
        }
        
        $query->select('payment_histroys.*');
        
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



    public function saveUpdatePaymentHistroy($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = PaymentHistroy::find($posted_data['update_id']);
        } else {
            $data = new PaymentHistroy;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['user_address_id'])) {
            $data->user_address_id = $posted_data['user_address_id'];
        }
        if (isset($posted_data['restaurant_id'])) {
            $data->restaurant_id = $posted_data['restaurant_id'];
        }
        if (isset($posted_data['customer_name'])) {
            $data->customer_name = $posted_data['customer_name'];
        }
        if (isset($posted_data['currency'])) {
            $data->currency = $posted_data['currency'];
        }
        if (isset($posted_data['amount_captured'])) {
            $data->amount_captured = $posted_data['amount_captured'];
        }
        if (isset($posted_data['item_delivered_quantity'])) {
            $data->item_delivered_quantity = $posted_data['item_delivered_quantity'];
        }
        if (isset($posted_data['payment_status'])) {
            $data->payment_status = $posted_data['payment_status'];
        }
        if (isset($posted_data['payment_sku'])) {
            $data->payment_sku = $posted_data['payment_sku'];
        }

        $data->save();
        
        $data = PaymentHistroy::getPaymentHistroy([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deletePaymentHistroy($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = PaymentHistroy::find($id);
        }else{
            $data = PaymentHistroy::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['user_id'])) {
                $is_deleted = true;
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
