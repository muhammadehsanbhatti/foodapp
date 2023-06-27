@section('title', 'Categories List')
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Filter Categories</h4>
                        </div>
                        
                        <div class="card-body">
                            <form method="GET" id="filterForm" action="{{ url('/category') }}">
                                @csrf
                                <input name="page" id="filterPage" value="1" type="hidden">
                                <div class="row">
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label" for="select2-basic">Category</label>
                                        <select class="formFilter select2 form-select" name="title" id="select2-cat">
                                            <option value=""> ---- Choose Category ---- </option>
                                            @foreach ($data['categories'] as $key => $cat_obj)
                                                <option value="{{$cat_obj['title']}}">{{$cat_obj['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label" for="select2-basic">Status</label>
                                        <select class="formFilter select2 form-select" name="status" id="select2-account-cat-status">
                                            <option value=""> ---- Choose Status ---- </option>
                                            @foreach ($data['statuses'] as $key => $status_obj) 
                                                <option value="{{$status_obj}}">{{$status_obj}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-1">    
                                        <label class="form-label" for="orderBy_name">Sort By Name</label>
                                        <select class="formFilter select2 form-select" name="orderBy_name" id="orderBy_name">
                                            <option value=""> ---- Choose an option ---- </option>
                                            <option value="categories.id">ID</option>
                                            <option value="categories.title">Title</option>
                                            <option value="categories.sort_order">Sort Order</option>
                                            <option value="categories.created_at">Created At</option>
                                            <option value="categories.updated_at">Updated At</option>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Select2 End -->
        
        @if (Session::has('message'))
        <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error_message'))
        <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
        @endif

        <div id="table_data">
            {{ $data['html'] }}
        </div>

    </div>
</div>

@endsection