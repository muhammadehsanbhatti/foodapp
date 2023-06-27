<div class="table-responsive"> 
    <table class="table">
        <thead>
            <tr>
                <th>Sr #</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Contact At</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($data['records']) && count($data['records'])>0)

                @php $any_permission_found = false; @endphp
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
                        <td>{{ $item['email'] }}</td>
                        <td>{{ $item['phone'] }}</td>
                        <td>{{ $item['subject'] }}</td>
                        <td>{{ $item['message'] }}</td>
                        <td>{{ $item['created_at'] }}</td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>
    
    <div class="pagination_links">
        @if (isset($data['records']) && count($data['records'])>0)
            {{ $data['records']->links('vendor.pagination.bootstrap-4') }}
        @else
            <div class="alert alert-primary">Don't have records!</div>
        @endif
    </div> 

</div>