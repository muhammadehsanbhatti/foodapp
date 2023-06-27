

@if (isset($data->id))
    @section('title', 'Update Permission')
@else
    @section('title', 'Add Permission')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Permission</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('permission.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="update_id" value="{{$data->id}}">
                            @else
                                <form class="form" action="{{ route('permission.store') }}" method="POST" enctype="multipart/form-data">
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <p class="mb-0"><span style="color: #5e5873; font-size: 0.9rem;">Permission Title</span>

                                                 @if (!isset($data->id))
                                                    <span class="float-right">
                                                        <input type="checkbox" id="is_crud_opt" name="is_crud">
                                                        <label for="is_crud_opt">Is CRUD?</label>
                                                    </span>
                                                @endif
                                            </p>
                                            <input value="{{old('title', isset($data->name)? $data->name: '')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Permission Title" name="name">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- @if (!isset($data->id))
                                    <div class="col-md-8 col-12">
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
                                    @endif --}}
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
