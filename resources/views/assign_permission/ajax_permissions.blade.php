<div class="row">
    {{--<div class="demo-inline-spacing">--}}
    @foreach( $data['permissions'] as $key => $perm_obj)

        @php
            $is_checked = '';
            if(isset($data['role']) && $data['role']->hasPermissionTo($perm_obj->name)){
                $is_checked = 'checked';
            }
        @endphp

        <div class="col-md-3 col-12 mb-1">
            <div class="custom-control custom-control-primary custom-checkbox">
                <input type="checkbox" value="{{$perm_obj->id}}" class="custom-control-input" name="perms[]" id="perm_id_{{$perm_obj->id}}" {{$is_checked}}>
                <label class="custom-control-label" for="perm_id_{{$perm_obj->id}}">{{$perm_obj->name}}</label>
            </div>
        </div>
    @endforeach

    <div class="col-12 mt-1">
        <button type="submit" id="sync_permissions" class="btn btn-primary mr-1 waves-effect waves-float waves-light">Save</button>
    </div>

</div>