<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:role-list|role-edit|role-delete', ['only' => ['index']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $roles = Role::all();
        // $data = Role::Paginate(10);
        $posted_data = array();
        $posted_data['paginate'] = 10;
        $posted_data['roles_not_in'] = [1];
        $data = $this->RoleObj->getRoles($posted_data);
    
        return view('role.list', compact('data'));
    }

    public function create()
    {
        return view('role.add');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules = array(
            'name' => 'required'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
                return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {
                $posted_data = $request->all();

                $this->RoleObj->saveUpdateRole($posted_data);

                \Session::flash('message', 'Role created successfully!');
                return redirect('/role');
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
        $posted_data = array();
        $posted_data['id'] = $id;
        $posted_data['detail'] = true;

        $data = $this->RoleObj->getRoles($posted_data);

        return view('role.add',compact('data'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = 0)
    {
        $input = $request->all();
   
        $validator = \Validator::make($input, [
            'name' => 'required'
        ]);
   
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();   
        }

        if ($id == 0) {
            \Session::flash('error_message', 'Something went wrong. Please post correct role id.');
            return redirect('/role');
        }
        
        $data = array();
        $data['update_id'] = $id;
        $data['name'] = $input['name'];

        $this->RoleObj->saveUpdateRole($data);

        \Session::flash('message', 'Role updated successfully!');
        return redirect('/role');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        \Session::flash('message', 'Role deleted successfully!');
        return redirect('/role');
    }
}