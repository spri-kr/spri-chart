/**
 * Created by user on 15-6-12.
 */
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