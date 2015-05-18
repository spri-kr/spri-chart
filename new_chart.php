<?php

if ( ! isset ( $_GET ) ) {
    exit();
}
if ( ! isset ( $_GET['plugin_www_url'] ) ) {
    exit();
}

$bootstrap_path = $_GET['plugin_www_url'] . "bootstrap-3.3.2-dist/";
$plugin_www_url = $_GET['plugin_www_url'];

$current_path = getcwd();

$wp_path = str_replace( "wp-content/plugins/spri-stat", "", $current_path );

global $wpdb;
if ( ! isset( $wpdb ) ) {
    include_once( $wp_path . 'wp-config.php' );
    include_once( $wp_path . 'wp-load.php' );
    include_once( $wp_path . 'wp-includes/wp-db.php' );
}

//$chart_list = $wpdb->get_results (
//    "select idx, chart_title ,  file_name, csv_content, csv_saved_path, xls_saved_path, chart_type, view_content, credate, state
//		from wp_spri_chart_list where state='A' "
//);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPRI Chart Management</title>

    <!-- Bootstrap -->
    <link href="<?php echo $bootstrap_path; ?>css/bootstrap.min.css"
          rel="stylesheet">
    <link href="<?php echo $plugin_www_url; ?>css/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script
        src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8">
            <h1> Chart Builder</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8">
            <form enctype="multipart/form-data" method="POST"
                  action="new_chart2.php">
                <div class="form-group">
                    <label for="csv_file">CSV File </label>
                    <input type="file" name="csv_file" id="csv_file">

                    <p class="help-block"> for data processing </p>
                </div>
                <div class="form-group">
                    <label for="excel_file">EXCEL File </label>
                    <input type="file" name="excel_file" id="excel_file">

                    <p class="help-block">for public download</p>
                </div>

                <div class="form-group">
                    <label for="chart_type">Chart Type </label>

                    <div class="radio">
                        <label>
                            <input class="radio" type="radio"
                                   name="chart_type" checked
                                   value="BarChart"/>
                            BarChart
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="radio" type="radio"
                                   name="chart_type"
                                   value="ColumnChart"/>
                            ColumnChart
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="radio" type="radio"
                                   name="chart_type"
                                   value="PieChart"/>
                            PieChart
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="radio" type="radio"
                                   name="chart_type"
                                   value="ComboChart"/>
                            ComboChart
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="radio" type="radio"
                                   name="chart_type"
                                   value="LineChart"/>
                            LineChart
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>User Chart Option</label>
				<textarea name="chart_detailed_option" cols="30"
                          id="chart_option"
                          rows="10">{
				}</textarea>

                    <div class="form-group"
                         id="chart_option_editor"></div>
                </div>

                <div class="form-group">
                    <label for="chart_title">Chart Title </label>
                    <input type="text" class="form-control" name="chart_title"
                           id="chart_title" placeholder="차트 제목">
                </div>

                <div class="form-group">
                    <label for="chart_unit">Unit (차트 상단) </label>
                    <input type="text" class="form-control" name="chart_unit"
                           id="chart_unit" placeholder="Unit">
                </div>
                <div class="form-group">
                    <label for="chart_caption">Caption (차트 하단) </label>
					<textarea class="form-control" rows="3" name="chart_caption"
                              id="chart_caption"></textarea>
                </div>
                <input type="hidden" name="plugin_www_url" id="plugin_www_url"
                       value="<?php echo $plugin_www_url; ?>">

                <div class="form-group">
                    <button type="submit" class="btn btn-success pull-left">
                        Build
                    </button>

                    <button type="button" class="btn btn-danger pull-right">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo $bootstrap_path; ?>js/bootstrap.min.js"></script>
<script src="ace/src-noconflict/ace.js" type="text/javascript"
        charset="utf-8"></script>

<script>
    $(document).ready(function () {
        document.getElementById('csv_file').onchange = uploadOnChange;
        function uploadOnChange() {
            var fpath = this.value;
            fpath = fpath.replace(/\\/g, '/');
            var fname = fpath.substring(fpath.lastIndexOf('/') + 1, fpath.lastIndexOf('.'));
            document.getElementById('chart_title').value = fname;
        }

        $('.option_chart_type').on('click', function () {
            var chart_type = $(this).attr('data-chart_type');
            $('#chart_type').val(chart_type);
            $('.option_chart_type').each(function (index) {
                $(this).removeClass("chart_type_selected");
            });
            $(this).addClass("chart_type_selected");
        });

        var editor = ace.edit("chart_option_editor");
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/javascript");
        var textarea = $('#chart_option').hide();

        textarea.closest('form').submit(function () {
            textarea.val(editor.getSession().getValue());
        })

        editor.setValue($('#chart_option').val());

    });
</script>

</body>
</html>