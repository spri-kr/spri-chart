jQuery(document).ready(function ($) {
    jQuery('#display_toggle').click(
        function () {
            jQuery(".add_new_chart").toggleClass('display-none');
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
        width: "100%",
        height: "400",
        title: "",
        titlePostition: 'none',
        legend: {
            position: "top",
            alignment: "center"
        },
        chartArea: {
            width: "85%",
            height: "80%"
        }
    };
});

function showRes(respond, status, xhr, $form) {

    parsed_respond = JSON.parse(respond);

    chart_data = parsed_respond.data;
    chart_title = parsed_respond.title;

    jQuery("#new_chart_title").val(chart_title);

    data_editor = ace.edit("data_editor");
    data_editor.$blockScrolling = Infinity;
    data_editor.setTheme("ace/theme/monokai");
    data_editor.getSession().setMode("ace/mode/javascript");
    data_editor.setValue(JSON.stringify(chart_data, null, '\t'));

    option_editor = ace.edit("option_editor");
    option_editor.$blockScrolling = Infinity;
    option_editor.setTheme("ace/theme/monokai");
    option_editor.getSession().setMode("ace/mode/javascript");
    option_editor.setValue(JSON.stringify(default_option, null, '\t'));
    jQuery('#chart_redraw').click();
}

jQuery('#chart_redraw').click(function ($) {
    event.preventDefault();
    var options_str = option_editor.getValue();
    options_json = JSON.parse(options_str);
    var data_str = data_editor.getValue();
    var data_json = JSON.parse(data_str);

    data_table = google.visualization.arrayToDataTable(data_json);

    var window_google = window['google']['visualization'];
    var selected_type = jQuery('#chart_type_selector input[name=chart_type]:checked').val();

    new_chart_draw_area = new window_google[selected_type](document.getElementById('new_chart_draw_area'));


    new_chart_draw_area.draw(data_table, options_json);
});

jQuery('#new_chart_upload').click(function ($) {
    event.preventDefault();
    var upload_options_str = option_editor.getValue();
    var upload_data_str = data_editor.getValue();
    var upload_chart_title = jQuery('#new_chart_title').val();
    var upload_chart_type = jQuery('#chart_type_selector input[name=chart_type]:checked').val();
    var sending_pkg = {
        option: upload_options_str,
        data: upload_data_str,
        title: upload_chart_title,
        type: upload_chart_type
    }

    var ajax_option = {
        method: "POST",
        data: {
            action: 'spri_ajax_db_insert',
            pkg: sending_pkg

        },
        //dataType:"json",
        //processData: false,
        success: function (data) {
            //alert(data)
            location.reload();
        }
    };

    console.log(sending_pkg);
    console.log(ajax_option);
    jQuery.ajax("/wp-admin/admin-ajax.php", ajax_option);
});

jQuery(window).resize(function () {
    jQuery('#chart_redraw').click();
});

jQuery('#chart_type_selector input[name=chart_type]').change(function () {
    jQuery('#chart_redraw').click();

});
