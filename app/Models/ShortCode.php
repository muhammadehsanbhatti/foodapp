<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortCode extends Model
{
    use HasFactory;
    public static function getEmailShortCode($posted_data = array())
    {
        $query = ShortCode::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('short_codes.id', $posted_data['id']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('short_codes.title', $posted_data['title']);
        }
 
        $query->select('short_codes.*');
        
        $query->getQuery()->short_codes = null;
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

    public function saveUpdateEmailShortCode($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ShortCode::find($posted_data['update_id']);
        } else {
            $data = new ShortCode;
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['title'])) {
                $is_updated = true;
                $data = $data->where('title', $where_posted_data['title']);
            }

            if($is_updated){
                return $data->update($posted_data);
            }else{
                return false;
            }
        }

        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }

        $data->save();
        
        $data = ShortCode::getEmailShortCode([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteEmailShortCode($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = ShortCode::find($id);
        }else{
            $data = ShortCode::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['title'])) {
                $is_deleted = true;
                $data = $data->where('title', $where_posted_data['title']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}