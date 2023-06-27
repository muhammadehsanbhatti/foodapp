
jQuery(document).ready(function () {

    var path = $(location).attr("pathname");
    if (path === '/user') {
        // getUserAjaxData();
    }


    $(document).on('click', '#delButton, #block_user', function (event) {
        var btn_txt = $(this).text();
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
            title: `Are you sure you want to delete this record?`,
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });

        if (btn_txt == 'Block' || btn_txt == 'Unblock') {
            swal({
                title: `Are you sure you want to update this record?`,
                icon: "warning",
                buttons: ["No", "Yes"],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        }

    });

    $(document).on('click', '#send_login_button', function (event) {
        var btn_txt = $(this).text();
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        var link = $(this).attr('href');
        // alert(link);

        swal({
            title: `Are you sure you want send login credentials`,
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        })
            .then(function (value) {
                if (value) {
                    window.location.href = link;
                }
            });
    });

});