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

    $("#plane-code-selector").change(function () {
        var selected = $("#plane-code-selector option:selected").text();
        var postData = {'airplaneCode': selected};
        $.post( "/fleet/getPlane", postData, function( data ) {
            var infoRows = '';
            $(".plane-info-row").remove();
            $.each(data, function(k, v) {
                if (k == 'id') {
                    return true;
                }
                var key = k.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                infoRows += '<tr class="plane-info-row"><td>' + key + ':</td><td>' + v + '</td></tr>';
            });
            $('#plane-info-body').after(infoRows);
        });
    });

    $("#booking-search-form").submit(function (event) {
        if ($("#booking-search-form")[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else if ($("#flying-from-selector option:selected").val() === $("#flying-to-selector option:selected").val()) {
            event.preventDefault();
            event.stopPropagation();
            $("#destination-invalid-feedback").text("Please select a destination different from origin.");
            $("#flying-to-selector")[0].setCustomValidity('destination same as origin');
        }

        $("#booking-search-form").addClass("was-validated");
    })
});
