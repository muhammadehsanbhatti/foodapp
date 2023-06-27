
@section('title', 'Dashboard')
@extends('layouts.master_dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">
        <!-- Dashboard Analytics Start -->
        <section id="dashboard-analytics">
            <div class="row match-height">
                <!-- Subscribers Chart Card starts -->

                    @can('role-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="lock" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['roles'])? $data['counts']['roles']:0 }}</h2>
                                    <p class="card-text mb-1">Roles</p>
                                </div>
                            </div>
                        </div>
                    @endcan
                    
                    @if (Auth::user()->hasRole('Super Admin'))
                        @if (isset($data['roles']) && count($data['roles'])>0)
                            @foreach ($data['roles'] as $key => $item)


                                @if (Auth::user()->hasRole($item->name) || Auth::user()->hasRole('Super Admin'))
                                    @if ( $item['name'] == 'Super Admin' )
                                        @continue
                                    @endif
                                    @php
                                        $user_count = App\Models\User::whereHas(
                                                            'roles', function($q) use($item){
                                                                $q->where('name', $item->name);
                                                            }
                                                        )->count();
                                    @endphp
                                        
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="card">
                                            <div class="card-header flex-column align-items-start pb-0">
                                                <div class="avatar bg-light-primary p-50 m-0">
                                                    <a href="javascript:void()" style="color: #7367F0 !important;">
                                                        <div class="avatar-content">
                                                            <i data-feather="award" class="font-medium-5"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                <h2 class="font-weight-bolder mt-1">{{ $user_count }}</h2>
                                                <p class="card-text mb-1">{{ $item->name }}</p>
                                            </div>
                                            {{-- <div id="gained-chart"></div> --}}
                                        </div>
                                    </div>
                                @endif
                                
                            @endforeach
                        @endif
                    @endif


                    @can('permission-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="lock" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['permissions'])? $data['counts']['permissions']:0 }}</h2>
                                    <p class="card-text mb-1">Permission</p>
                                </div>
                            </div>
                        </div>
                    @endcan


                    @can('user-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['users'])? $data['counts']['users']:0 }}</h2>
                                    <p class="card-text mb-1">Users</p>
                                </div>
                            </div>
                        </div>
                    @endcan


                    @can('menu-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="menu" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['menus'])? $data['counts']['menus']:0 }}</h2>
                                    <p class="card-text mb-1">Menu</p>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('sub-menu-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="menu" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['sub_menus'])? $data['counts']['sub_menus']:0 }}</h2>
                                    <p class="card-text mb-1">Sub Menu</p>
                                </div>
                            </div>
                        </div>
                    @endcan


                    @can('email-template-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="mail" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['email_messages'])? $data['counts']['email_messages']:0 }}</h2>
                                    <p class="card-text mb-1">Email Templates</p>
                                </div>
                            </div>
                        </div>
                    @endcan


                    @can('shortcode-list')
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <a href="javascript:void()" style="color: #7367F0 !important;">
                                            <div class="avatar-content">
                                                <i data-feather="code" class="font-medium-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <h2 class="font-weight-bolder mt-1">{{ isset($data['counts']['short_codes'])? $data['counts']['short_codes']:0 }}</h2>
                                    <p class="card-text mb-1">Short Code</p>
                                </div>
                            </div>
                        </div>
                    @endcan
            </div>

        </section>
        <!-- Dashboard Analytics end -->

    </div>
    <div class="content-body">
       
            <form method="POST" id="taskForm">
                @csrf
                <input name="task" id="taskFltrPage" value="1" type="hidden">
                <input id="taskList" name="taskList"  type="hidden" value="{{@$data['taskList']}}">
                <div class="row" id="filter_input_fields" style="display: none">
                    {{-- @if (Auth::user()->role == 1) --}}
                    {{--<div class="col-md-6 mb-1">
                            <label class="form-label" for="select2-basic">Task assign </label>
                            <select class="taskfltr select2 form-select" name="assign_user_id" id="select2-roles">
                                <option value=""> ---- Choose Staff ---- </option>
                                @foreach($data['tasks_data'] as $key => $item)
                                    <option value="{{ $item['assign_user_id'] }}">{{ $item->user->first_name }} {{ $item->user->last_name }}</option>
                                @endforeach
                            
                            </select>
                        </div>--}}
                    {{-- @endif --}}
                </div>
            </form>

    </div>
</div>
@endsection