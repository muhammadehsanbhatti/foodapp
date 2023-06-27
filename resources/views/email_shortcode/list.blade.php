@section('title', 'Email Short Code List')
@extends('layouts.master_dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">

    </div>
    <div class="content-body">
        @if (Session::has('message'))
        <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error_message'))
        <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
        @endif


        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sr #</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @if (isset($data) && count($data)>0)

                        @php $any_permission_found = false; @endphp
                        @foreach ($data as $key=>$item)
                            @php
                                $sr_no = $key + 1;
                                if ($data->currentPage()>1) {
                                    $sr_no = ($data->currentPage()-1)*$data->perPage();
                                    $sr_no = $sr_no + $key + 1;
                                }
                            @endphp
                            <tr>
                                <td>{{ $sr_no }}</td>
                                <td>{{ $item['title'] }}</td>
                               
                                <td>
                                    @canany(['shortcode-edit', 'shortcode-delete'])
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="12" cy="5" r="1"></circle>
                                                <circle cx="12" cy="19" r="1"></circle>
                                            </svg>
                                        </button>
                                        @php $any_permission_found = true; @endphp
                                        <div class="dropdown-menu">
                                            @can('shortcode-edit')
                                            <a class="dropdown-item" href="{{ url('short_codes')}}/{{$item['id']}}/edit" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                            @endcan

                                            @can('shortcode-delete')
                                            <form action="{{ url('short_codes/'.$item['id']) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <!-- onclick="return confirm(' are you sure you want to delete the record?');" -->
                                                <button class="dropdown-item" id="delButton" type="submit" style="width: 100%">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span>Delete</span>

                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                    @endcanany
                                    @if (!$any_permission_found)
                                        {{ 'Not Available' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    @endif
                </tbody>
            </table>
            @if (isset($data) && count($data)>0)
                {{ $data->links('vendor.pagination.bootstrap-4') }}
            @else
                <div class="alert alert-primary">Don't have records!</div>
            @endif

        </div>
    </div>
</div>



@endsection
