jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.add_excel_file', function(event) {
        event.preventDefault();
        var form = $(this).closest("form")[0];
        let formData = new FormData(form);
        $.ajax({
           
            type: "POST",
            url: base_url+"/import_excel",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            context: this,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#csv_import_data_id').val(data.data.csv_data_file.id);
                    $('.form_text').html(data.data.html);
                    $("#import_btn").removeClass("hidden");
                    $('.alert-danger').hide();
                }
                else{
                    $('.alert-danger').show();
                    $('.error-message').html(data.records.error);
                }
            },
            error: function (e) { }
        });

    });

    $(document).on('click', '.import_data', function(event) {
        event.preventDefault();
        var form = $(this).closest("form")[0];
        let formData = new FormData(form);
        $.ajax({
           
            type: "POST",
            url: base_url+"/import_data_submit",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            context: this,
            dataType: 'json',
            success: function (data) {
                swal({
                    title: `CSV file Import Successfully`,
                    icon: "success",
                    buttons:"OK",
                    dangerMode: false,
                })
                .then(function(){ 
                    location.reload();
                });
            },
            error: function (e) { }
        });

    });

});

