<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:permission-list|permission-edit|permission-delete', ['only' => ['index']]);
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $posted_data = $request->all();
        // $posted_data = array();
        // $posted_data['orderBy_name'] = 'sort_order';
        // $posted_data['orderBy_value'] = 'ASC';
        $posted_data['paginate'] = 10;

        // $data['records'] = $this->PermissionObj->getPermissions($posted_data);

        // unset($posted_data['paginate']);
        // $data['permissions'] = $this->PermissionObj->all();

        // $data['statuses'] = $this->PermissionObj::Permission_Status_Constants;

        $data['permissions'] = $this->PermissionObj->getPermissions();
        $data['records'] = $this->PermissionObj->getPermissions($posted_data);

        $data['html'] = view('permission.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('permission.list', compact('data'));
    }

    public function create()
    {
        // $data['asset_types'] = $this->PermissionObj::Permission_Asset_Type_Constants;
        // return view('permission.add', compact('data'));
        return view('permission.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $posted_data = $request->all();

        $rules = array(
            'name' => 'required',
        );

        $validator = \Validator::make($posted_data, $rules);

        if ($validator->fails()) {

            // echo "<pre>";
            // echo "0000"."<br>";
            // print_r($validator->errors());
            // echo "</pre>";
            // exit("@@@@");

            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {

            $permissions_list = array();

            $slug = strtolower($posted_data['name']);
            $slug = preg_replace('/\s+/', '-', $slug);

            if( isset($posted_data['is_crud']) ) {
                $permissions_list[] = $slug."-list";
                $permissions_list[] = $slug."-view";
                $permissions_list[] = $slug."-create";
                $permissions_list[] = $slug."-update";
                $permissions_list[] = $slug."-delete";
            }
            else {
                $permissions_list[] = $slug;
            }

            $already_exist = false;

            foreach ($permissions_list as $permission) {

                $permission_detail = Permission::where('name', $permission)->first();
                if (!$permission_detail) {
                    Permission::create(['name' => $permission]);
                }
                else {
                    $already_exist = true;
                }
            }

            if( !isset($posted_data['is_crud']) && $already_exist )
                \Session::flash('message', 'Permission already exist!');
            else
                \Session::flash('message', 'Permission created successfully!');

            return redirect('/permission');
        }
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $data['records'] = Permission::where('id', $id)->first();

        // $data['records'] = Permission::paginate(10);
        
        // $posted_data = array();
        // $posted_data['count'] = true;
        // $data['tot_permissions'] = $this->PermissionObj->getPermissions($posted_data);
        
        // $arr = array();
        // for ($i=1; $i <= $data['tot_permissions'] ; $i++) { 
        //     $arr[] = $i;
        // }

        // $data['all_opts'] = $arr;
        // $data['statuses'] = $this->PermissionObj::Permission_Status_Constants;
        // $data['asset_types'] = $this->PermissionObj::Permission_Asset_Type_Constants;

        $data = $this->PermissionObj->getPermissions([
            'id' => $id,
            'detail' => true,
        ]);

        return view('permission.add',compact('data'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $requested_data = $request->all();
   
        $validator = \Validator::make($requested_data, [
            'update_id' => 'required',
            'name' => 'required',
        ]);
   
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();   
        }

        $permission_name = strtolower($requested_data['name']);
        $permission_name = preg_replace('/\s+/', '-', $permission_name);

        $update_rec = $this->PermissionObj->getPermissions([
            'id' => $requested_data['update_id'],
            'detail' => true,
        ]);

        if ($update_rec) {

            $permission_detail = $this->PermissionObj->getPermissions([
                'name' => $permission_name,
                'detail' => true,
            ]);
    
            if($permission_detail && $permission_detail->id != $update_rec->id) {
                \Session::flash('error_message', 'Permission is already exist!');
                return redirect('/permission');
            }
            else {
                $update_rec->name = $permission_name;
                $update_rec->save();
            }
            
            \Session::flash('message', 'Permission updated successfully!');
            return redirect('/permission');
        }
        else {
            \Session::flash('error_message', 'Something went wrong during update!');
            return redirect('/permission');
        }
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->PermissionObj->deletePermission($id);
        if($response) {
            \Session::flash('message', 'Permission deleted successfully!');
            return redirect('/permission');
        }
    }

    public function update_sorting($posted_data = array())
    {
        if ( isset($posted_data['status']) && $posted_data['status'] ) {
            foreach ($posted_data as $key => $value) {
                if ($key === 'status') {}
                else {
                    $permission_obj = [];
                    $permission_obj = Permission::find($value['id']);
                    $permission_obj->sort_order = $value['sort_order'];
                    $permission_obj->save();
                }
            }
            return true;
        }
        else {
            return false;
        }
    }
}