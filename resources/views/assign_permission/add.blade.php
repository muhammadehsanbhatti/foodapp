

@if (isset($data->id))
    @section('title', 'Update Assign Permission')
@else
    @section('title', 'Add Assign Permission')
@endif
@extends('layouts.master_dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Assign Permission</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('assign_permission.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="update_id" value="{{$data->id}}">
                            @else
                                <form class="form" action="{{ route('assign_permission.store') }}" method="POST" enctype="multipart/form-data">
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-1">
        
                                        <label class="form-label" for="select2-basic">Select User</label>
                                        <select class="user_fltr select2 form-select" name="user" id="select2-user">
                                            <option value=""> ---- Choose User ---- </option>
                                            @if (isset($data['users']) && count($data['users']) > 0 )
                                                @foreach ($data['users'] as $key => $user_obj)
                                                    <option value="{{$user_obj['id']}}">{{$user_obj['first_name']}} {{$user_obj['last_name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    @if (!isset($data->id))
                                        <div class="col-md-6 col-12" id="all_assign_permissions">
                                            <div class="form-group">
                                                <label for="first_name">Is CRUD?</label>
                                                <div class="demo-inline-spacing">
                                                    <div class="custom-control custom-control-primary custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="is_crud_opt" name="is_crud">
                                                        <label class="custom-control-label" for="is_crud_opt">Yes</label>
                                                    </div>
                                                </div>                                            
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
