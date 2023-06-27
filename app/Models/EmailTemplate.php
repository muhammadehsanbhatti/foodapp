<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function getMessageAttribute($value){
        return decrypt($value);
    }
    
    public function setMessageAttribute($value){
        $this->attributes['message'] = encrypt($value);
    }

    public static function getEmailTemplates($posted_data = array())
    {
        $query = EmailTemplate::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('email_templates.id', $posted_data['id']);
        }
        if (isset($posted_data['subject'])) {
            $query = $query->where('email_templates.subject', $posted_data['subject']);
        }
        if (isset($posted_data['message'])) {
            $query = $query->where('email_templates.message', $posted_data['message']);
        }
        if (isset($posted_data['send_on'])) {
            $query = $query->where('email_templates.send_on', $posted_data['send_on']);
        }
 
        $query->select('email_templates.*');
        
        $query->getQuery()->email_messages = null;
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

    public function saveUpdateEmailTemplates($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = EmailTemplate::find($posted_data['update_id']);
        } else {
            $data = new EmailTemplate;
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['subject'])) {
                $is_updated = true;
                $data = $data->where('subject', $where_posted_data['subject']);
            }

            if($is_updated){
                return $data->update($posted_data);
            }else{
                return false;
            }
        }

        if (isset($posted_data['subject'])) {
            $data->subject = $posted_data['subject'];
        }
        if (isset($posted_data['message'])) {
            $data->message = encrypt($posted_data['message']);
        }
        if (isset($posted_data['send_on'])) {
            $data->send_on = $posted_data['send_on'];
        }

        $data->save();
        
        $data = EmailTemplate::getEmailTemplates([
            'detail' => true,
            'user_id' => $data->id
        ]);
        return $data;
    }

    public function deleteEmailTemplates($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = EmailTemplate::find($id);
        }else{
            $data = EmailTemplate::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['subject'])) {
                $is_deleted = true;
                $data = $data->where('subject', $where_posted_data['subject']);
            }
        }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}