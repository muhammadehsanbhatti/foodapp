<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;
    
    public static function getUserReview($posted_data = array())
    {
        $query = UserReview::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('user_reviews.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_reviews.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['restaurnat_menu_id'])) {
            $query = $query->where('user_reviews.restaurnat_menu_id', $posted_data['restaurnat_menu_id']);
        }
        if (isset($posted_data['stars'])) {
            $query = $query->where('user_reviews.stars', $posted_data['stars']);
        }
        if (isset($posted_data['message'])) {
            $query = $query->where('user_reviews.message', $posted_data['message']);
        }
        
        
        $query->select('user_reviews.*');
        
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



    public function saveUpdateUserReview($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserReview::find($posted_data['update_id']);
        } else {
            $data = new UserReview;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['restaurnat_menu_id'])) {
            $data->restaurnat_menu_id = $posted_data['restaurnat_menu_id'];
        }
        if (isset($posted_data['stars'])) {
            $data->stars = $posted_data['stars'];
        }
        if (isset($posted_data['message'])) {
            $data->message = $posted_data['message'];
        }
        
        $data->save();
        
        $data = UserReview::getUserReview([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteUserReview($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserReview::find($id);
        }else{
            $data = UserReview::latest();
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
