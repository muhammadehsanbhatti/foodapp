@section('title', 'Goal List')
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
                            <h4 class="card-title">Filter Goal</h4>
                        </div>
                        
                        <div class="card-body">
                            <form method="GET" id="filterForm" action="{{ url('/goal') }}">
                                @csrf
                                <input name="page" id="filterPage" value="1" type="hidden">
                                <div class="row">
                                    {{-- <div class="col-md-6 mb-1">    
                                        <label class="form-label" for="select2-title">Title</label>
                                        <select class="formFilter change_title select2 form-select" name="title" id="select2-title">
                                            <option value=""> ---- Choose an option ---- </option>
                                            @if (isset($data['get_goals']) && count($data['get_goals']) > 0 )
                                                @foreach ($data['get_goals'] as $key => $title_obj)
                                                    <option value="{{$title_obj['title']}}">{{$title_obj['title']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
    
                                    </div> --}}
                                    {{-- <div class="col-md-6 mb-1">    
                                        <label class="form-label" for="title_status">Title status</label>
                                        <select class="formFilter select2 form-select" name="title_status" id="title_status">
                                            <option value=""> ---- Choose an option ---- </option>
                                                <option value="1">Career Status Position</option>
                                                <option value="2">Professional Role</option>
                                                <option value="3">Educational Information</option>
                                                <option value="4">Specialty Skills</option>
                                                <option value="5">Industry Vertical</option>
                                                <option value="6">User Report</option>
                                        </select>
                                    </div> --}}
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
