
@if (!isset($data->user_role) && isset($data->id))
    @section('title', 'Update Profile')
@elseif (isset($data->id))
    @section('title', 'Update User')
@else
    @section('title', 'Add User')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} {{ !isset($data->user_role) && isset($data->id)? 'Profile':'User Detail' }}</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('user.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                
                            @else
                                <form class="form" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                                
                            @endif
                                @csrf
                                <div class="row">
                                        
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input value="{{old('first_name', isset($data->first_name)? $data->first_name: '')}}" type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name" name="first_name">
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input value="{{old('last_name', isset($data->last_name)? $data->last_name: '')}}" type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name" name="last_name">
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input value="{{old('email', isset($data->email)? $data->email: '')}}" type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="phone_number">Confirm password</label>
                                            <input type="password" id="confirm_password" value="{{old('confirm_password')}}" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm password" name="confirm_password">
                                            @error('confirm_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label for="profile_image">Profile Image</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text basic-addon">
                                                    <div class="display_images preview_profile_image">
                                                        @if (isset($data->profile_image) && !empty($data->profile_image))
                                                            <a data-fancybox="demo" data-src="{{ is_image_exist($data->profile_image) }}"><img title="{{ $data->name }}" src="{{ is_image_exist($data->profile_image) }}" height="100"></a>
                                                        @endif
                                                    </div>
                                                </span>
                                                </div>
                                            <input type="file" id="profile_image" data-img-val="preview_profile_image" class="form-control @error('profile_image') is-invalid @enderror" placeholder="Profile Image" name="profile_image">
                                            @error('profile_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    @if (!isset($data->user_role) && isset($data->id))
                                    @else
                                    {{-- @if (Auth::user()->hasRole('Super Admin') && isset($data->id)) --}}
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="user_role">User Role</label>
                                            <select class="form-control @error('user_role') is-invalid @enderror" name="user_role" id="user_role">
                                                <option value="">Choose an option </option>
                                                @if (isset($data['roles']) && count($data['roles'])>0)
                                                    @foreach ($data['roles'] as $item)

                                                        @if ( $item['name'] == 'Super Admin' )
                                                            @continue
                                                        @endif
                                                        <option {{ old('user_role') == $item['name'] || (isset($data->user_role) && $data->user_role==$item['name'])? 'selected': '' }} value="{{ $item['name'] }}">{{ $item['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('user_role')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                        
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
                                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
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
