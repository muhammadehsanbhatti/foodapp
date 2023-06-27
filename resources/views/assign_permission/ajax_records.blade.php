<div class="table-responsive">
    @if (isset($data['records']) && count($data['records'])>0)
        <table class="table">
            <thead>
                <tr>
                    <th>Sr #</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data['records']) && count($data['records'])>0)

                    @foreach ($data['records'] as $key=>$item)
                        @php
                            $sr_no = $key + 1;
                            if ($data['records']->currentPage()>1) {
                                $sr_no = ($data['records']->currentPage()-1)*$data['records']->perPage();
                                $sr_no = $sr_no + $key + 1;
                            }
                        @endphp
                        <tr>
                            <td>{{ $sr_no }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['created_at'] }}</td>
                            <td>{{ $item['updated_at'] }}</td>
                            <td>
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="12" cy="5" r="1"></circle>
                                        <circle cx="12" cy="19" r="1"></circle>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('assign_permission')}}/{{$item['id']}}/edit" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ url('assign_permission/'.$item['id']) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button class="dropdown-item" id="delButton" type="submit" style="width: 100%">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            <span>Delete</span>

                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                @endif
            </tbody>
        </table>
        
        <div class="assign_permission_links">
            {{-- {!! $data['records']->links() !!} --}}
            {{ $data['records']->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif

</div>