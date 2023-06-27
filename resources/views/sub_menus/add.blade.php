@if (isset($data->id))
    @section('title', 'Update Sub-Menu')
@else
    @section('title', 'Add Sub-Menu')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Sub-Menu</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('sub_menu.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="update_id" value="{{$data->id}}">
                            @else
                                <form class="form" action="{{ route('sub_menu.store') }}" method="POST" enctype="multipart/form-data">
                            @endif
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="permission">Permission</label>
                                            <select class="form-control @error('permission') is-invalid @enderror" name="permission" id="permission">
                                                <option value="">Choose an option</option>
                                                @if (isset($data['all_permissions']) && count($data['all_permissions'])>0)
                                                    @foreach ($data['all_permissions'] as $key => $slug_obj)
                                                        <option {{ old('permission') == $slug_obj || (isset($data->slug) && $data->slug == $slug_obj)? 'selected': '' }} value="{{ $slug_obj }}">{{ $slug_obj }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('permission')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="menu">Menu</label>
                                            <select class="form-control @error('menu') is-invalid @enderror" name="menu" id="menu">
                                                <option value="">Choose an option</option>
                                                @if (isset($data['menus']) && count($data['menus'])>0)
                                                    @foreach ($data['menus'] as $item)
                                                        <option {{ old('menu') == $item->id || (isset($data->menu_id) && $data->menu_id == $item->id)? 'selected': '' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('menu')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="first_name">Sub-Menu Title</label>
                                            <input value="{{old('title', isset($data->title)? $data->title: '')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Sub Menu Title" name="title">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="first_name">Sub-Menu URL</label>
                                            <input value="{{old('url', isset($data->url)? $data->url: '')}}" type="text" id="url" class="form-control @error('url') is-invalid @enderror" placeholder="Sub Menu URL" name="url">
                                            @error('url')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label for="image">Image</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text basic-addon">
                                                    <div class="display_images">
                                                        @if (isset($data->asset_value) && !empty($data->asset_value))
                                                        <a data-fancybox="demo" data-src="{{ is_image_exist($data->asset_value) }}"><img title="{{ $data->name }}" src="{{ is_image_exist($data->asset_value) }}" height="100"></a>
                                                        @endif
                                                    </div>
                                                </span>
                                            </div>
                                            <input type="file" id="image" data-img-val="" class="form-control @error('image') is-invalid @enderror" name="image">
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    @if (isset($data->id))
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="ordering">Sort Order</label>
                                                <select class="form-control @error('ordering') is-invalid @enderror" name="ordering" id="ordering">
                                                    <option value="">Choose an option</option>
                                                    @if (isset($data['sort_order']) && count($data['all_opts'])>0)
                                                        @foreach ($data['all_opts'] as $item)
                                                            <option {{ old('all_opts') == $item || (isset($data->sort_order) && $data->sort_order == $item)? 'selected': '' }} value="{{ $item }}">{{ $item }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('ordering')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                                    <option value="">Choose an option</option>
                                                    @if ( isset($data['status']) && count($data['statuses']) > 0 )
                                                        @foreach ($data['statuses'] as $key => $item)
                                                            <option {{ old('status') == $item || (isset($data->status) && $data->status == $item )? 'selected': '' }} value="{{ $item }}">{{ $item }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('status')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
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
