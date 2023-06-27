@if (isset($data->id))
    @section('title', 'Update General Title')
@else
    @section('title', 'Add General Title')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} General Title Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('general_title.update', $data->id) }}" method="post">
                                @method('PUT')
                                
                            @else
                                <form class="form" action="{{ route('general_title.store') }}" method="POST">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input value="{{old('title', isset($data->title)? $data->title: '')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Title" name="title">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="title_status">Title Status</label>

                                            <select  class="form-control @error('title_status') is-invalid @enderror" name="title_status" id="title_status">
                                                <option value=""> ---- Choose an option ---- </option>
                                                <option value="1" {{ isset($data->title_status) && $data->title_status == 1 ? 'selected':'' }}>Career Status Position</option>
                                                <option value="2" {{ isset($data->title_status) && $data->title_status == 2 ? 'selected':'' }}>Professional Role</option>
                                                <option value="3" {{ isset($data->title_status) && $data->title_status == 3 ? 'selected':'' }}>Educational Information</option>
                                                <option value="4" {{ isset($data->title_status) && $data->title_status == 4 ? 'selected':'' }}>Specialty Skills</option>
                                                <option value="5" {{ isset($data->title_status) && $data->title_status == 5 ? 'selected':'' }}>Industry Vertical</option>
                                                <option value="6" {{ isset($data->title_status) && $data->title_status == 6 ? 'selected':'' }}>User Report</option>
                                            </select>
                                            @error('title_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="title">Title Status</label>
                                            <select  class="form-control @error('title_status') is-invalid @enderror" name="title_status" id="title_status">
                                                <option value=""> ---- Choose an option ---- </option>
                                                <option value="Published">Published</option>
                                                <option value="Draft">Draft</option>
                                            </select>
                                            <input value="{{old('title', isset($data->title)? $data->title: '')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Title" name="title">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}
                                   
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
