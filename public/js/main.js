$(window).on('load', function () {
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
});

jQuery(document).ready(function () {
    setTimeout(function () {
        $('.alert-success').hide();
    }, 4000);

    $("#theme_layout").click(function (event) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "post",
            url: base_url+"/theme_mode",
            success: function (data) {

                if (data.record.theme_mode == 'Light') {
                    $("html").removeClass("dark-layout");
                    $("html").addClass("light-layout");
                    $("i").removeClass("sun");
                    $("i").addClass("moon");
                    $("div").removeClass("menu-dark");
                    $("div").addClass("menu-light");
                    $("nav").removeClass("navbar-dark");
                    $("nav").addClass("navbar-light");

                } else {
                    $("html").addClass("dark-layout");
                    $("html").removeClass("light-layout");
                    $("i").addClass("sun");
                    $("i").removeClass("moon");
                    $("div").addClass("menu-dark");
                    $("div").removeClass("menu-light");
                    $("nav").addClass("navbar-dark");
                    $("nav").removeClass("navbar-light");
                }
            },
            error: function (e) { }
        });

    });

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

    $(document).on('click', '.pagination_links .pagination a', function (event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        $('#filterPage').val(page);
        getAjaxData();
    });

    $(document).on('change', '.formFilter', function (event) {
        getAjaxData();
    });
});



function getAjaxData(data) {
    $('.loaderOverlay').fadeIn();
    jQuery.ajax({
        url: $("#filterForm").attr('action'),
        data: $("#filterForm").serializeArray(),
        method: $("#filterForm").attr('method'),
        dataType: 'html',
        success: function (response) {
            $('.loaderOverlay').fadeOut();
            $("#table_data").html(response);

            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        }
    });
}