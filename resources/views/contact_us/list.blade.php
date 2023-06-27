@section('title', 'contacts List')
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
                            <h4 class="card-title">Filter contacts</h4>
                        </div>
                        
                        <div class="card-body">
                            <form method="GET" id="filterForm" action="{{ url('/contact-us-list') }}">
                                @csrf
                                <input name="page" id="filterPage" value="1" type="hidden">
                                <div class="row">
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label" for="select2-basic">Name</label>
                                        <input name="name" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label" for="select2-basic">Email</label>
                                        <input name="email" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label" for="select2-basic">Phone</label>
                                        <input name="phone" class="form-control">
                                    </div>

                                    <div class="col-md-3 mb-1">    
                                        <label class="form-label" for="orderBy_name">Sort By Name</label>
                                        <select class="formFilter select2 form-select" name="orderBy_name" id="orderBy_name">
                                            <option value=""> ---- Choose an option ---- </option>
                                            <option value="contacts.id">ID</option>
                                            <option value="contacts.title">Title</option>
                                            <option value="contacts.sort_order">Sort Order</option>
                                            <option value="contacts.created_at">Created At</option>
                                            <option value="contacts.updated_at">Updated At</option>
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