<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Sr #</th>
                {{-- <th>Status</th> --}}
                <th>Profile Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $any_permission_found = false; @endphp
            @foreach ($data['users'] as $key => $item)
                @if ( $item->hasRole('Super Admin') )
                    @continue
                @endif
                @php
                    $sr_no = $key + 1;
                    if ($data['users']->currentPage()>1) {
                        $sr_no = ($data['users']->currentPage()-1)*$data['users']->perPage();
                        $sr_no = $sr_no + $key + 1;
                    }
                @endphp

                <tr>
                    <td>{{ $sr_no }}</td>
                    <td>
                        <div class="display_images_list">

                            <span class="avatar-color">
                                <a data-fancybox="demo" data-src="{{ is_image_exist($item->profile_image) }}">
                                    <img title="{{ $item->name }}" src="{{ is_image_exist($item->profile_image) }}" height="100">
                                    
                                    @if(Cache::has('user-is-online' . $item->id))
                                        <span class="avatar-status-online"></span>
                                    @else
                                        <span class="avatar-status-offline"></span>
                                    @endif
                                </a>
                            </span>
                        
                        </div>
                    </td>

                    <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        @foreach ($item->getRoleNames() as $role_key => $role_name)
                            <span class="role-badge" style="background-color: {{ $item->hasRole('Admin')? '#7367F0':'#6e6b7b' }}">{{ $role_name }} </span>
                        @endforeach
                    </td>

                    <td>{{ $item->user_status }}</td>
                    <td>{{ date('M d, Y H:i A', strtotime($item->created_at)) }}</td>
                    
                    <td>
                        @canany(['user-edit', 'user-delete', 'user-status'])
                        <div class="dropdown">
                            {{-- @if ( $item->hasRole('Admin') ) --}}
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </button>
                                @php $any_permission_found = true; @endphp
                                <div class="dropdown-menu">
                                    @can('user-status')
                                    <form action="{{ url('blockUnblockUser')}}" method="Post" enctype="multipart/form-data">
                                    @method('POST')
                                    @csrf
                                        @if ( $item->user_status == 'Active' )
                                        
                                            <input type="hidden" name="update_id" value="{{$item->id}}">
                                            <input type="hidden" name="user_status" value="2">
                                            
                                            <button type="submit" class="dropdown-item"  style="width:100%" id="block_user">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x mr-50"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                                                <span>Block</span>
                                            </button>
                                                                                        
                                        @else
                                        
                                            <input type="hidden" name="update_id" value="{{$item->id}}">
                                            <input type="hidden" name="user_status" value="1">
                                            
                                            <button type="submit" class="dropdown-item"  style="width:100%" id="block_user">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check mr-50"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                <span>Unblock</span>
                                            </button>
                                        
                                        @endif
                                    </form>
                                    @endcan

                                    @can('user-edit')
                                    <a class="dropdown-item" href="{{ url('user')}}/{{$item->id}}/edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    @endcan
                                    
                                    @can('user-delete')
                                    <form action="{{ url('user/'.$item['id']) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="dropdown-item" id="delButton" style="width:100%">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            <span>Delete</span>

                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            {{-- @endif --}}
                        </div>
                        @endcanany
                        @if (!$any_permission_found)
                            {{ 'Not Available' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination_links">
        {{-- {!! $data['users']->links() !!} --}}
        @if (isset($data['users']) && count($data['users'])>0)
            {{ $data['users']->links('vendor.pagination.bootstrap-4') }}
        @else
            <div class="alert alert-primary">Don't have records!</div>
        @endif

    </div>

</div>