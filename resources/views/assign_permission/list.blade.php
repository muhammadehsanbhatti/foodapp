@section('title', 'Assign Permission List')
@extends('layouts.master_dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">

    </div>
    <div class="content-body">

        <!-- Select2 Start  -->
        <section class="basic-select2">
            <div class="row">
                <div class="col-12">

                    @if (Session::has('message'))
                    <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                    @endif
                    @if (Session::has('error_message'))
                    <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                    @endif
    
                    <div class="alert alert-success alert-success-ajax" style="display: none;"></div>
                    <div class="alert alert-danger alert-danger-ajax" style="display: none;"></div>
                    
                    <div class="card">
                        
                        {{-- <form method="POST" id="assignPermissionFilterform"> --}}
                        {{-- <form class="form" id="assignPermissionFilterform" action="{{ route('assign_permission.store') }}" method="post" enctype="multipart/form-data"> --}}
                        <form method="GET" id="filterForm" action="{{ url('/assign_permission') }}">
                        @csrf
                            <input name="page" id="filterPage" value="1" type="hidden">
                            <div class="card-header">
                                <h4 class="card-title">Filter Assign Permission</h4>
                            </div>
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-1">
        
                                        <label class="form-label" for="select2-basic">Select Role</label>
                                        <select class="formFilter role_fltr select2 form-select" name="role" id="select2-user">
                                            <option value=""> ---- Choose Role ---- </option>
                                            @if (isset($data['roles']) && count($data['roles']) > 0 )
                                                @foreach ($data['roles'] as $key => $role_obj)
                                                    <option value="{{$role_obj['name']}}">{{$role_obj['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-1">    
                                        <label class="form-label" for="orderBy_name">Sort By Name</label>
                                        <select class="formFilter select2 form-select" name="orderBy_name" id="orderBy_name">
                                            <option value=""> ---- Choose an option ---- </option>
                                            <option value="permissions.id">ID</option>
                                            <option value="permissions.name">Name</option>
                                            <option value="permissions.created_at">Created At</option>
                                            <option value="permissions.updated_at">Updated At</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-1">    
                                        <label class="form-label" for="orderBy_value">Sort By Value</label>
                                        <select class="formFilter select2 form-select" name="orderBy_value" id="orderBy_value">
                                            <option value=""> ---- Choose an option ---- </option>
                                            <option value="ASC">ASC</option>
                                            <option value="DESC">DESC</option>
                                        </select>
                                    </div>
                                </div>

                                {{--
                                <div class="row">
                                    <div class="col-md-6 mb-1">
    
                                        <label class="form-label" for="select2-basic">Assign Permission</label>
                                        <select class="assign_permission_fltr select2 form-select" name="name" id="select2-assign_permission">
                                            <option value=""> ---- Choose Assign Permission ---- </option>
                                            @if (isset($data['assign_permissions']) && count($data['assign_permissions']) > 0 )
                                                @foreach ($data['assign_permissions'] as $key => $perm_obj)
                                                    <option value="{{$perm_obj['name']}}">{{$perm_obj['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
    
                                    </div>
                                </div>
                                --}}                            
                            </div>

                            {{--
                            <div class="card-header">
                                <h4 class="card-title">Permissions Lists</h4>
                            </div>
                            --}}
                            
                            <div id="table_data" class="card-body assign_permissions_list" style="display: block; padding-top: 0px">
                                {{ $data['html'] }}
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </section>
        <!-- Select2 End -->

        <div id="all_assign_permissions"></div>
        
    </div>
</div>

@endsection