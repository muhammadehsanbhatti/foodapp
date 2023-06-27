<thead>
    <tr>
        @foreach ( $data['csv_data'] as $key => $value) 
            <td>
                <select class="form-control" name="dbfield[]">
                    <option value="">Choose option</option>
                    @foreach ($data['column_name'] as $key => $colum_name) 
                        <option value="{{$colum_name}}" >{{$colum_name}}</option>
                    @endforeach
                </select>
            </td>
        @endforeach
    </tr>

    <tr>
        @foreach ($data['headings'][0] as $csv_file_header) 
            <th>
                <span >{{$csv_file_header}}</span>
            </th>
        @endforeach
    </tr>
</thead>
<tbody>
    @foreach ($data['csv_data'] as $key => $row) 
        <tr>
            @foreach($row as $value)
                <td>{{$value}} </td>
            @endforeach
        </tr>
    @endforeach
</tbody>