@if (isset($data->id))
@section('title', 'Update Goal')
@else
@section('title', 'Add Goal')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Goal Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                            <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                            <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                            <form class="form" action="{{ route('goal.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" id="update_id" name="update_id" value="{{$data->id}}">
                                @else
                                <form class="form" action="{{ route('goal.store') }}" method="POST"
                                    enctype="multipart/form-data">

                                    @endif
                                    @csrf
                                    <div class="add_goal">

                                        @if (isset($data))
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">

                                                    {{-- <div class="input-group-prepend">
                                                        <span class="input-group-text basic-addon">
                                                            <div class="display_images goal_icon">
                                                                @if (isset($data->icon) && !empty($data->icon))
                                                                <a data-fancybox="demo"
                                                                    data-src="{{ is_image_exist($data->icon) }}"><img
                                                                        title="{{ $data->name }}"
                                                                        src="{{is_image_exist($data->icon)}}"
                                                                        height="100"></a>
                                                                @endif
                                                            </div>
                                                        </span>
                                                    </div> --}}
                                                    <input class="form-control @error("icon") is-invalid @enderror" type="file" name="icon" required>
                                                   
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="btn btn-primary mr-1 waves-effect waves-float waves-light mt-2 add_item_btn">Add item</button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="ml-1" for="title">Goal Item title</label>
                                            </div>
                                            
                                        </div>

                                        <div class="add_goal_item_title">
                                            @if (isset($data['goal_items']))
                                            
                                                @foreach ($data['goal_items'] as $key=>$goal_item)

                                                    <div class="row" id="{{$goal_item->id}}">
                                                        <div class="col-md-5 col-12">
                                                            <div class="form-group">
                                                             
                                                                <input type="hidden" name="goal_items_id[]" value="{{$goal_item->id}}">
                                                                <input name="goal_item_title[]" type="text"
                                                                    value="{{$goal_item->title}}"
                                                                    class="form-control @error(" options") is-invalid @enderror" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                            <div class="form-group sa">
                                                                <button type="button" id-attr="{{$goal_item->id}}"
                                                                    action-attr="{{ url('delete_goal_item/'.$goal_item->id) }}"
                                                                    id="delete_goal_item"
                                                                    class="btn btn-danger mr-1 waves-effect waves-float waves-light">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-12">

                                            <button type="button"
                                                class="btn btn-primary mr-1 waves-effect waves-float waves-light mt-2 add_goal_btn float-right">Add
                                                goal</button>
                                            <button type="submit"
                                                class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{
                                                isset($data->id)? 'Update':'Add' }}</button>
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