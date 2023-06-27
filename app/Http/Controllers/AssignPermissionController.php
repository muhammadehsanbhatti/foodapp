<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:assign-permission', ['only' => ['index', 'store']]);
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
        // $posted_data['paginate'] = 10;

        // $data['records'] = $this->AssignPermissionObj->getAssignPermissions($posted_data);

        // unset($posted_data['paginate']);
        // $data['permissions'] = $this->AssignPermissionObj->all();

        // $data['statuses'] = $this->AssignPermissionObj::AssignPermission_Status_Constants;

        // $data['permissions'] = $this->AssignPermissionObj->getAssignPermissions();

        $data['roles'] = $this->RoleObj->getRoles();

        if( isset($posted_data['role']) && $posted_data['role'] != '') {
            $role = Role::where('name', $posted_data['role'])->first();
            $data['role'] = $role;
        }

        $data['permissions'] = $this->PermissionObj->getPermissions($posted_data);

        $data['html'] = view('assign_permission.ajax_permissions', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }
        
        return view('assign_permission.list', compact('data'));
    }

    public function create()
    {
        // $data['asset_types'] = $this->AssignPermissionObj::AssignPermission_Asset_Type_Constants;
        // return view('assign_permission.add', compact('data'));

        $data['users'] = $this->UserObj->getUser();
        $data['records'] = $this->AssignPermissionObj->getAssignPermissions([
            'paginate' => 10,
        ]);

        return view('assign_permission.add', compact('data'));
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
   
        $validator = \Validator::make($posted_data, [
            'role' => 'required',
        ]);

        if($validator->fails()){
            if ($request->ajax()) {
                return response()->json([
                    'status'            => 400,
                    'message'           => $validator->errors()->first(),
                    'data'              => [],
                ], 200);
            }
            else {
                return redirect()->back()->withErrors($validator)->withInput();   
            }
        }

        $posted_data['perms'] = isset($posted_data['perms'])? $posted_data['perms'] : array();

        $role = Role::where('name',$posted_data['role'])->first();
        $permissions = Permission::whereIn('id',$posted_data['perms'])->pluck('id','id')->all();
        $role->syncPermissions($permissions);

        if ($request->ajax()) {
            return response()->json([
                'status'            => 200,
                'message'           => 'Permissions updated successfully',
                'data'              => [],
            ], 200);
        }
        else {
            return redirect('/assign_permission');
        }

        // if( !isset($posted_data['is_crud']) && $already_exist )
        //     \Session::flash('message', 'Permission is already assigned!');
        // else
        //     \Session::flash('message', 'Permission is assigned successfully!');

        // return redirect('/permission');
        // }
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
        // $data['records'] = AssignPermission::where('id', $id)->first();

        // $data['records'] = AssignPermission::paginate(10);
        
        // $posted_data = array();
        // $posted_data['count'] = true;
        // $data['tot_permissions'] = $this->AssignPermissionObj->getAssignPermissions($posted_data);
        
        // $arr = array();
        // for ($i=1; $i <= $data['tot_permissions'] ; $i++) { 
        //     $arr[] = $i;
        // }

        // $data['all_opts'] = $arr;
        // $data['statuses'] = $this->AssignPermissionObj::AssignPermission_Status_Constants;
        // $data['asset_types'] = $this->AssignPermissionObj::AssignPermission_Asset_Type_Constants;

        $data = $this->AssignPermissionObj->getAssignPermissions([
            'id' => $id,
            'detail' => true,
        ]);

        return view('assign_permission.add',compact('data'));
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

        $update_rec = $this->AssignPermissionObj->getAssignPermissions([
            'id' => $requested_data['update_id'],
            'detail' => true,
        ]);

        if ($update_rec) {

            $permission_detail = $this->AssignPermissionObj->getAssignPermissions([
                'name' => $permission_name,
                'detail' => true,
            ]);
    
            if($permission_detail && $permission_detail->id != $update_rec->id) {
                \Session::flash('error_message', 'Permission is already assigned!');
                return redirect('/permission');
            }
            else {
                $update_rec->name = $permission_name;
                $update_rec->save();
            }
            
            \Session::flash('message', 'Permission is updated successfully!');
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
        $response = $this->AssignPermissionObj->deleteAssignPermission($id);
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
                    $permission_obj = AssignPermission::find($value['id']);
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