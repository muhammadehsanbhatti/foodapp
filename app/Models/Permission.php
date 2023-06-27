<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Permission extends Model
{
    use HasFactory;
    public function getPermissions($posted_data = array())
    {
        $query = Permission::latest();

        $columns = ['permissions.*'];
        $select_columns = array_merge($columns, []);

        // if (isset($posted_data['without_with']) && $posted_data['without_with']) {}
        // else {
            // $query = $query
            //     ->with('category_details');
        // }

        if (isset($posted_data['id'])) {
            $query = $query->where('permissions.id', $posted_data['id']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('permissions.name', $posted_data['name']);
        }

        $query->select('permissions.*');
        
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



    public function saveUpdatePermission($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Permission::find($posted_data['update_id']);
        } else {
            $data = new Permission;
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['status'])) {
                $is_updated = true;
                $data = $data->where('status', $where_posted_data['status']);
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
        if (isset($posted_data['url'])) {
            $data->url = $posted_data['url'];
        }
        if (isset($posted_data['slug'])) {
            $data->slug = $posted_data['slug'];
        }
        if (isset($posted_data['sort_order'])) {
            $data->sort_order = $posted_data['sort_order'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        if (isset($posted_data['asset_type'])) {
            $data->asset_type = $posted_data['asset_type'];
        }
        if (isset($posted_data['asset_value'])) {
            $data->asset_value = $posted_data['asset_value'];
        }

        $data->save();
        
        $data = Permission::getPermissions([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deletePermission($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Permission::find($id);
        }else{
            $data = Permission::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['status'])) {
                $is_deleted = true;
                $data = $data->where('status', $where_posted_data['status']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}