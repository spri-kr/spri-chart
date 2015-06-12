/**
 * Created by user on 15-6-12.
 */
jQuery(document).ready(function($) {
    jQuery('#show_add').click(
        function () {
            jQuery(".add_new_chart").toggleClass('display-none');
            console.log("clicked!");
        });
});