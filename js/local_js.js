jQuery(document).ready(function($) {
    var data = {
        'action': 'spri_ajax',
        'whatever': ajax_object.we_value      // We pass php values differently!
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post(ajax_object.ajax_url, data, function(response) {
        alert('Got this from the server: ' + response);
    });
});

jQuery(document).ready(function($) {
    jQuery('#show_add').click(
        function () {
            jQuery(".add_new_chart").toggleClass('display-none');
            console.log("clicked!");
        });
});

jQuery("#")