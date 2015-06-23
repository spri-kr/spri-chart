jQuery(document).ready(function($) {
    jQuery('#display_toggle').click(
        function () {
            jQuery(".add_new_chart").toggleClass('display-none');
            console.log("clicked!");
        });
    jQuery('#display_toggle').click();
});

jQuery(document).ready(function ($) {
    var options = {
        data: {
            action: 'spri_ajax_csv_upload'
        },
        success: showRes
    }
    jQuery("#csv_file_upload").ajaxForm(options);
});

jQuery(document).ready(function ($) {
    // chart default option
    default_option = {
        width:"100%",
        height:"400",
        title: "",
        titlePostition: 'none',
        legend:{
            position:"top",
            alignment:"center"
        },
        chartArea: {
            width: "85%",
            height: "80%"
        }
    };
});

function showRes(respond, status, xhr, $form){

    chart_data = JSON.parse(respond);

    data_editor = ace.edit("data_editor");
    data_editor.$blockScrolling = Infinity;
    data_editor.setTheme("ace/theme/monokai");
    data_editor.getSession().setMode("ace/mode/javascript");
    data_editor.setValue(JSON.stringify(chart_data, null, '\t'));

    option_editor= ace.edit("option_editor");
    option_editor.$blockScrolling = Infinity;
    option_editor.setTheme("ace/theme/monokai");
    option_editor.getSession().setMode("ace/mode/javascript");
    option_editor.setValue(JSON.stringify(default_option, null, '\t'));
}

jQuery('#chart_redraw').click(function ($) {
    event.preventDefault();
    var options_str = option_editor.getValue();
    var options_json = JSON.parse(options_str);
    var data_str = data_editor.getValue();
    var data_json = JSON.parse(data_str);

    var data_table = google.visualization.arrayToDataTable(data_json);

    var window_google = window['google']['visualization'];
    var selected_type = jQuery('#chart_type_selector input[name=chart_type]:checked').val();

    new_chart_draw_area = new window_google[selected_type](document.getElementById('new_chart_draw_area'));


    new_chart_draw_area.draw(data_table, options_json);
});