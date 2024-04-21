jQuery(document).ready(function() {

    jQuery('#gform_submit_button_16').click(function() {

        var data = {
            action: 'search_hotels',
            s: jQuery('.hotels-search-form input#input_16_1').val(),
            start_date: jQuery('.hotels-search-form input#input_16_5').val(),
            end_date: jQuery('.hotels-search-form input#input_16_6').val(),
        };

        //jQuery('.hotel-search-result').html( '<p class="hotels-preloader">Searching for hotels..</p>' );
        jQuery('.hotels-preloader').show();

        jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
            jQuery('.hotels-preloader').hide();
            jQuery('.hotel-search-result').html( response )
        });

        return false;

    });

    // Autocomplete
    var input = document.getElementById('input_16_1');
    new google.maps.places.Autocomplete(input);

});


// Setup phone code into the hidden field
jQuery(document).on('submit', '#gform_18', function() {

    var code = jQuery(this).find('.iti__active .iti__dial-code').text();
    jQuery('#input_18_15').val( code );

});


jQuery(document).on('click', '#request-quote', function() {

    jQuery('.open-window').click();

    var start_date = jQuery('.hotels-search-form input#input_16_5').val();
    var end_date = jQuery('.hotels-search-form input#input_16_6').val();
    var hotel_name = jQuery(this).data('name');
    var hotel_url = jQuery(this).data('url');

    jQuery('.modal #input_18_11').val( start_date );
    jQuery('.modal #input_18_12').val( end_date );
    jQuery('.modal #input_18_13').val( hotel_name );
    jQuery('.modal #input_18_14').val( hotel_url );

    return false;

});


gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {

    if ( formId == 16 && fieldId == 5 ) {
        optionsObj.minDate = 0;
        optionsObj.onClose = function (dateText, inst) {
             jQuery('#input_16_6').datepicker('option', 'minDate', dateText).datepicker('setDate', dateText);
        };
    }
    return optionsObj;

});