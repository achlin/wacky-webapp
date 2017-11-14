$(function() {
    //----- OPEN
    $('[data-popup-open]').on('click', function(e)  {
        var targeted_popup_class = jQuery(this).attr('data-popup-open');
        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

        e.preventDefault();
    });

    //----- CLOSE
    $('[data-popup-close]').on('click', function(e)  {
        var targeted_popup_class = jQuery(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

        e.preventDefault();
    });

    $("#planeCodeSelect" ).change(function () {
        var selected = $("#planeCodeSelect option:selected").text();
        var postData = {'airplaneCode': selected};
        $.post( "/fleet/getPlane", postData, function( data ) {
            var infoRows = '';
            $(".ajaxRow").remove();
            $.each(data, function(k, v) {
                if (k == 'id') {
                    return true;
                }
                var key = k.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                infoRows += '<tr class="ajaxRow"><td>' + key + ':</td><td>' + v + '</td></tr>';
            });
            $('#planeInfoBody').after(infoRows);
        });
    })
});
