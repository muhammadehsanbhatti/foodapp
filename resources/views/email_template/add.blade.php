

@if (isset($data->id))
    @section('title', 'Update Email Template')
@else
    @section('title', 'Add Email Template')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Message Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" id="email_msg_form" action="{{ route('email_template.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                
                            @else
                                <form class="form" id="email_msg_form" action="{{ route('email_template.store') }}" method="POST" enctype="multipart/form-data">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-8 col-12">
                                                <div class="form-group">
                                                    <label for="first_name">Email Subject</label>
                                                    <input value="{{old('subject', isset($data->subject)? $data->subject: '')}}" type="text" id="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Email subject" name="subject">
                                                    @error('subject')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="send_on">Email Send On</label>
                                                    <select class="form-control @error('send_on') is-invalid @enderror" id="send_on"  name="send_on">
                                                        <option value="">Choose email send on</option>
                                                        @foreach (Config::get('constants.sendOn') as $key => $item)
                                                            <option {{ old('send_on') == $item || (isset($data->send_on) && $data->send_on == $item )? 'selected': '' }} value="{{ $item }}">{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('send_on')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="last_name">Email Template</label>
                                                    <select class="form-control @error('emaiil_short_codes') is-invalid @enderror" id="emaiil_short_codes" >
                                                        <option value="">Choose short code</option>
                                                        @if (isset($data['short_codes']) && count($data['short_codes'])>0)
                                                            @foreach ($data['short_codes'] as $item)
                                                                <option value="{{ $item['title'] }}">{{ $item['title'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <textarea name="message" style="display:none" id="editorClone"></textarea>
                                                    <div id="editor-container">{!! old('message', isset($data->message)? $data->message: '') !!}</div>
                                                    <div id="output-html" style="display:none">{!! old('message', isset($data->message)? $data->message: '') !!}</div>
                                                    @error('message')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{--
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="emaiil_short_codes">Email short Codes</label>
                                                    <select class="form-control @error('emaiil_short_codes') is-invalid @enderror" id="emaiil_short_codes" >
                                                        <option value="">Choose an option</option>
                                                        @if (isset($data['short_codes']) && count($data['short_codes'])>0)
                                                            @foreach ($data['short_codes'] as $item)
                                                                <option {{ old('short_codes') == $item['id'] || (isset($data->title) && $data->title == $item['id'])? 'selected': '' }} value="{{ $item['title'] }}">{{ $item['title'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('emaiil_short_codes')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            --}}
                                        </div>
                                    </div>
                                     
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