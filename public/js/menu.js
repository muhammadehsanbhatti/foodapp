jQuery(document).ready(function () {
    $(document).on('change', '.asset_type', function (event) {

        $('.image_div').hide();
        $('.icon_div').hide();

        if ($(this).val() == 'Icon')
            $('.icon_div').show();
        else
            $('.image_div').show();
    });


});
