jQuery(document).ready(function ($) {
    jQuery('#display_toggle').click(
        function () {
            jQuery(".add_new_chart").toggle();
        });

    jQuery('.chart_edit_btn').click(
        function () {
            var id = jQuery(this).attr("chart_id");
            jQuery("#chart_edit_area_" + id).toggle();


            window['data_editor_' + id] = ace.edit("chart_" + id + "_data_editor");
            //window['data_editor_' + id].$blockScrolling = Infinity;
            window['data_editor_' + id].setTheme("ace/theme/monokai");
            window['data_editor_' + id].getSession().setMode("ace/mode/javascript");
            window['data_editor_' + id].setValue(JSON.stringify(window['data_' + id], null, '\t'));
            window['data_editor_' + id].clearSelection();


            window['option_editor_' + id] = ace.edit("chart_" + id + "_option_editor");
            //window['option_editor_' + id].$blockScrolling = Infinity;
            window['option_editor_' + id].setTheme("ace/theme/monokai");
            window['option_editor_' + id].getSession().setMode("ace/mode/javascript");
            window['option_editor_' + id].setValue(JSON.stringify(window['option_' + id], null, '\t'));
            window['option_editor_' + id].clearSelection();

            var title = jQuery("#chart_" + id + "_title").text();
            jQuery("#chart_" + id + "_title_editor").val(title);

            jQuery("#chart_" + id + "_type_selector_editor input[value=" + window['type_' + id]).prop("checked", true)

        });

    jQuery(".chart_edit_draw_btn").click(function () {
        var id = jQuery(this).attr("chart_id");
        var data_srt = window['data_editor_' + id].getValue();
        var option_srt = window['option_editor_' + id].getValue();
        var chart_type = jQuery("#chart_" + id + "_type_selector_editor input[name=chart_" + id + "_type]:checked").val()

        var data = google.visualization.arrayToDataTable(JSON.parse(data_srt));
        var option = JSON.parse(option_srt);

        var chart = new google.visualization[chart_type](document.getElementById('chart_canvas_' + id));

        chart.draw(data, option);

    });

    jQuery(".chart_update_btn").click(function () {
        var id = jQuery(this).attr("chart_id");
        var data_str = window['data_editor_' + id].getValue();
        var option_srt = window['option_editor_' + id].getValue();
        var chart_type = jQuery("#chart_" + id + "_type_selector_editor input[name=chart_" + id + "_type]:checked").val()
        var title = jQuery("#chart_" + id + "_title_editor").val();

        var sending_pkg = {
            data: data_str,
            option: option_srt,
            title: title,
            type: chart_type,
            chart_id: id
        }

        var ajax_option = {
            method: "POST",
            data: {
                action: 'spri_ajax_chart_update',
                pkg: sending_pkg

            },
            success: function (data) {
                location.reload();
            }
        };
        jQuery.ajax("/wp-admin/admin-ajax.php", ajax_option);
    });

    jQuery(".chart_delete_btn").click(function () {
        var r = confirm("정말 삭제하시겠습니까?");

        if (r === true) {
            var row_id = jQuery(this).attr("row_id");
            var sending_pkg = {
                row_id: row_id
            }

            var ajax_option = {
                method: "POST",
                data: {
                    action: 'spri_ajax_chart_delete',
                    pkg: sending_pkg

                },
                success: function (data) {
                    location.reload();
                }
            };
            jQuery.ajax("/wp-admin/admin-ajax.php", ajax_option);
        }
    });


    var options = {
        data: {
            action: 'spri_ajax_csv_upload'
        },
        success: showRes
    }
    jQuery("#csv_file_upload").ajaxForm(options);


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

    jQuery('#chart_redraw').click(function (event) {
        event.preventDefault();
        var options_str = option_editor.getValue();
        options_json = JSON.parse(options_str);
        var data_str = data_editor.getValue();
        data_table = google.visualization.arrayToDataTable(JSON.parse(data_str));

        var selected_type = jQuery('#chart_type_selector input[name=chart_type]:checked').val();

        new_chart_draw_area = new google.visualization[selected_type](document.getElementById('new_chart_draw_area'));

        new_chart_draw_area.draw(data_table, options_json);
    });

    jQuery(window).resize(function () {
        jQuery('#chart_redraw').click();
    });

    jQuery('#chart_type_selector input[name=chart_type]').change(function () {
        jQuery('#chart_redraw').click();

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

        //console.log(sending_pkg);
        //console.log(ajax_option);
        jQuery.ajax("/wp-admin/admin-ajax.php", ajax_option);
    });

    jQuery(".chart_edit_btn").click(function () {
        var c_id = jQuery(this).attr("chart_id");
        jQuery(this).parents(".chart_control_buttons").after(

        )

    });
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

function transpose(a)
{
    return a[0].map(function (_, c) { return a.map(function (r) { return r[c]; }); });
}