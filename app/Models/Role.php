<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $table = 'roles';


    public static function getRoles($posted_data = array())
    {
        $query = Role::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('roles.id', $posted_data['id']);
        }
        if (isset($posted_data['roles_in'])) {
            $query = $query->whereIn('roles.id', $posted_data['roles_in']);
        }
        if (isset($posted_data['roles_not_in'])) {
            $query = $query->whereNotIn('roles.id', $posted_data['roles_not_in']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('roles.name', 'like', '%' . $posted_data['name'] . '%');
        }

        $query->select('roles.*');
        
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



    public function saveUpdateRole($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Role::find($posted_data['update_id']);
        } else {
            $data = new Role;
            $data->guard_name = 'web';
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['name'])) {
                $is_updated = true;
                $data = $data->where('name', $where_posted_data['name']);
            }

            if($is_updated){
                return $data->update($posted_data);
            }else{
                return false;
            }
        }

        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }

        $data->save();
        
        $data = Role::getRoles([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteRole($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Role::find($id);
        }else{
            $data = Role::latest();
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