jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Update and Store Varients
    $('.varient_submit').on('submit',function(event){
        event.preventDefault();
        var form = $(this).closest("form")[0];
        
        var action = $(this).attr("action");
        var method = $(this).attr("method");
        let formData = new FormData(form);
        $.ajax({
           
            type: method,
            url: action,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            context: this,
            dataType: 'json',
            success: function (data) {
                // alert("fsafsa");
                console.log(data);
                var update_id = $('#update_id').val();
                if (data.success) {
                    swal({text: update_id ? "Varient Updated Successfully!" :"Varient Added Successfully!", type: 
                        "success"})
                        .then(function(){ 
                        location.reload();
                        }
                    );
                    // $(".varient_submit").trigger("reset");
                    // $(".alert-success").prop("hidden", false);
                    // $(".alert-success").html(data.message);
                    $('#varient_id').val(data.records.id);
                    $('.varient_option').html(data.records.html);
                }
                else{
                    printErrorMsg(data.message.error);
                    // $('.alert-danger').removeClass("d-none");
                    // $('.alert-danger').append(data.message.error);
                }
            },
            error: function (e) { }
        });
    });

    // Delete varient options
    $(document).on('click', '#delete_goal_item', function(event){
        event.preventDefault();
        var id = $(this).attr("id-attr");
        var url = $(this).attr("action-attr");
        swal({
            title: "Are you sure to Delete!",
            icon: "warning",
            buttons: [
                'cancel',
                'yes'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {id:id},
                    processData: false,
                    contentType: false,
                    cache: false,
                    context: this,
                    dataType: 'json',
                    success: function (data) {
                        swal("Goal Item deleted Successfully", {
                            icon: "success",
                        });
                        $("#"+id+"").remove();
                    },
                    error: function (e) { }
                });
            }
        });
       
    });

    var count = 1;
    var goal_count = 1;
    var goal_item_count = 1;
    var update_id = $('#update_id').val();
    
    if (!update_id) {
        dynamic_field_goal(goal_count);
        // dynamic_field(count);
    }else{
        count = 2;
    }

        function dynamic_field_goal(goal_number)
        {
            html="";

            html='<div class="mt-2 delete_goal">';
            html += '<div class="row">';

            html += '<div class="col-md-6 col-12">';
            html += '<div class="form-group">';
            html += ' <label for="title">Goal Icon</label>';
            html += '<input class="form-control @error("icon") is-invalid @enderror" type="file" name="icon['+goal_number+']" id="icon" required>';
        
            html += '</div></div>';
            html += '<div class="col-md-6 col-12">';
            html += '<div class="form-group">';
            if(goal_number > 1)
            {
                html += '<button type="button" class="btn btn-danger mr-1 waves-effect waves-float waves-light remove mt-2 delete_goal_btn">Delete Goal</button>';
            }
            html += '</div></div>';
            html += '</div>';


            
            html += '<div class="add_goal_item_title">';
            html += '<div class="row">';
            html += '<div class="col-md-5 col-12">';
            html += '<div class="form-group">';
            html += '<label for="title">Goal items</label>';
            html += '<input class="form-control @error("title") is-invalid @enderror" type="text" name="title['+goal_number+'][1]" placeholder="Goal item" required>';
           
            html += '</div></div>';
            html += '<div class="col-md-2 col-12">';
            html += '<div class="form-group">';
            html += '<button type="button" class="btn btn-primary mr-1 waves-effect waves-float waves-light mt-2 add_item_btn">Add item</button>';
           
            html += '</div></div>';

            html += '<div class="col-md-2 col-12">';
            html += ' <div class="form-group mt-2">';
            html += '</div></div>';
            html += '</div>';
            html += '</div>';


            html += '<div class="col-md-2 col-12">';
            html += ' <div class="form-group mt-2">';
           
        
            html += '</div></div>';
            html += '</div>';
        
            $('.add_goal').append(html);  
                    
            goal_count  = goal_number;
        }


    $(document).on('click', '.add_item_btn', function(){
        goal_item_count++;
        html="";

        html='<div class="row" id="delete_item">';

        html += '<div class="col-md-5 col-12">';
        html += '<div class="form-group">';
        html += '<input class="form-control @error("title") is-invalid @enderror" type="text" name="title['+goal_count+']['+goal_item_count+']" id="icon" placeholder="Goal item" required>';
    
        html += '</div></div>';
        html += '<div class="col-md-2 col-12">';
        html += ' <div class="form-group">';
        html += '<button type="button" id="delete-btn" class="btn btn-danger mr-1 waves-effect waves-float waves-light remove">Delete</button>';
        html += '</div></div>';
        html += '</div>';
        $(this).closest('.row').after(html);
        
    });

    $(document).on('click', '.add_goal_btn', function(){
        count++;
        dynamic_field_goal(count);
    });

    $(document).on('click', '#delete-btn', function(){
        count--;
        $(this).parents("#delete_item").remove();
    });

    $(document).on('click', '.delete_goal_btn', function(){
        count--;
        $(this).parents(".delete_goal").remove();
    });

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }

    // Varient Filters
    var path = $(location).attr("pathname");
    if (path === '/varient'){
    }

    $(document).on('click', '.var_links .pagination a', function(event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        $('#varientFltrPage').val(page);
        getVarientAjaxData();
    });

    $(document).on('change', '.varient_fltr', function(event) {
        $('#varientFltrPage').val(1);
        getVarientAjaxData();
    });

    function getVarientAjaxData() {
        $('.loaderOverlay').fadeIn();
    
        jQuery.ajax({
            url: "/get_varient",
            data: $("#varientFilterform").serializeArray(),
            method: 'POST',
            dataType: 'html',
            success: function(response) {
                $('.loaderOverlay').fadeOut();
                $("#all_varients").html(response);
    
                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                }
            }
        });
    }

});

