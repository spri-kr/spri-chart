<?php
if ( ! isset ( $_POST ) ) {
	exit();
}
if ( ! isset ( $_POST['plugin_www_url'] ) ) {
	exit();
}
$bootstrap_path = $_POST['plugin_www_url'] . "bootstrap-3.3.2-dist/";
$plugin_www_url = $_POST['plugin_www_url'];


global $wpdb;
if ( ! isset( $wpdb ) ) {
	$wp_path = dirname( dirname( dirname( getcwd() ) ) );
	include_once( $wp_path . '/wp-config.php' );
	include_once( $wp_path . '/wp-load.php' );
	include_once( $wp_path . '/wp-includes/wp-db.php' );

}


/***
 *     /$$$$$$$$ /$$ /$$
 *    | $$_____/|__/| $$
 *    | $$       /$$| $$  /$$$$$$
 *    | $$$$$   | $$| $$ /$$__  $$
 *    | $$__/   | $$| $$| $$$$$$$$
 *    | $$      | $$| $$| $$_____/
 *    | $$      | $$| $$|  $$$$$$$
 *    |__/      |__/|__/ \_______/
 *
 *
 *
 *                                                                          /$$
 *                                                                         |__/
 *      /$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$$  /$$$$$$   /$$$$$$$ /$$$$$$$ /$$ /$$$$$$$   /$$$$$$
 *     /$$__  $$ /$$__  $$ /$$__  $$ /$$_____/ /$$__  $$ /$$_____//$$_____/| $$| $$__  $$ /$$__  $$
 *    | $$  \ $$| $$  \__/| $$  \ $$| $$      | $$$$$$$$|  $$$$$$|  $$$$$$ | $$| $$  \ $$| $$  \ $$
 *    | $$  | $$| $$      | $$  | $$| $$      | $$_____/ \____  $$\____  $$| $$| $$  | $$| $$  | $$
 *    | $$$$$$$/| $$      |  $$$$$$/|  $$$$$$$|  $$$$$$$ /$$$$$$$//$$$$$$$/| $$| $$  | $$|  $$$$$$$
 *    | $$____/ |__/       \______/  \_______/ \_______/|_______/|_______/ |__/|__/  |__/ \____  $$
 *    | $$                                                                                /$$  \ $$
 *    | $$                                                                               |  $$$$$$/
 *    |__/                                                                                \______/
 */


$vtime = date( "YmdHis" );

$uploaddir          = getcwd() . "/../../uploads/spri-stat/";
$uploaddir_relative = "/../../uploads/spri-stat/";

if ( ! file_exists( $uploaddir ) ) {
	if ( ! mkdir( $uploaddir, 0777, true ) ) {
		die( 'Failed to upload file ' );
	}
}
$json_dir = getcwd() . "/json/";
if ( ! file_exists( $json_dir ) ) {
	if ( ! mkdir( $json_dir, 0777, true ) ) {
		die( 'Failed to json file ' );
	}
}


$path_csv = $_FILES['csv_file']['name'];
$ext_cvs  = pathinfo( $path_csv, PATHINFO_EXTENSION );


$path_excel = $_FILES['excel_file']['name'];
$ext_excel  = pathinfo( $path_excel, PATHINFO_EXTENSION );


$csvTitle = str_replace( "." . $ext_cvs, "", $path_csv );

$csvfile = $uploaddir . $vtime . "." . $ext_cvs;

$csvfile_relative = $uploaddir_relative . $vtime . "." . $ext_cvs;


//echo '<pre>';
if ( move_uploaded_file( $_FILES['csv_file']['tmp_name'], $csvfile ) ) {
//    echo "csv_file 파일이 유효하고, 성공적으로 업로드 되었습니다.\n";
}

$excelfile          = $uploaddir . basename( $_FILES['excel_file']['name'] );
$excelfile          = $uploaddir . $vtime . "." . $ext_excel;
$excelfile_relative = $uploaddir_relative . $vtime . "." . $ext_excel;
if ( move_uploaded_file( $_FILES['excel_file']['tmp_name'], $excelfile ) ) {

} else {
	$excelfile          = "";
	$excelfile_relative = "";
}


$row = 1;

setlocale( LC_CTYPE, 'ko_KR.eucKR' );
$table = "";

$sData = array();

if ( ( $handle = fopen( $csvfile, "r" ) ) !== false ) {
	$table .= "<table border='1' >\n";
	while ( ( $data = fgetcsv( $handle, 0, "," ) ) !== false ) {
		$tmpArray = array();
		$table .= "<tr>\n";
		for ( $i = 0; $i < count( $data ); $i ++ ) {
			$data[ $i ] = iconv( "euc-kr", "utf-8", $data[ $i ] );
			array_push( $tmpArray, $data[ $i ] );
			$table .= "<td>" . $data[ $i ] . "</td>\n";
		}

		array_push( $sData, $tmpArray );
		$table .= "</tr>\n";
	}


	$newData = array();
	for ( $ii = 0; $ii < count( $sData ); $ii ++ ) {
		$vv = $sData[ $ii ];
		for ( $jj = 0; $jj < count( $sData[ $ii ] ); $jj ++ ) {
			$tmpData = $sData[ $ii ][ $jj ];
			if ( $ii > 0 && $jj > 0 ) {
				$tmpData = floatval( $tmpData );
			} else {
				$tmpData = urldecode( $tmpData );
			}
			$newData[ $jj ][ $ii ] = $tmpData;
		}
	}

	fclose( $handle );
	$table .= "</table>\n";

}

/***
 *      /$$$$$$  /$$                             /$$            /$$$$$$              /$$     /$$
 *     /$$__  $$| $$                            | $$           /$$__  $$            | $$    |__/
 *    | $$  \__/| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$        | $$  \ $$  /$$$$$$  /$$$$$$   /$$  /$$$$$$  /$$$$$$$   /$$$$$$$
 *    | $$      | $$__  $$ |____  $$ /$$__  $$|_  $$_/        | $$  | $$ /$$__  $$|_  $$_/  | $$ /$$__  $$| $$__  $$ /$$_____/
 *    | $$      | $$  \ $$  /$$$$$$$| $$  \__/  | $$          | $$  | $$| $$  \ $$  | $$    | $$| $$  \ $$| $$  \ $$|  $$$$$$
 *    | $$    $$| $$  | $$ /$$__  $$| $$        | $$ /$$      | $$  | $$| $$  | $$  | $$ /$$| $$| $$  | $$| $$  | $$ \____  $$
 *    |  $$$$$$/| $$  | $$|  $$$$$$$| $$        |  $$$$/      |  $$$$$$/| $$$$$$$/  |  $$$$/| $$|  $$$$$$/| $$  | $$ /$$$$$$$/
 *     \______/ |__/  |__/ \_______/|__/         \___/         \______/ | $$____/    \___/  |__/ \______/ |__/  |__/|_______/
 *                                                                      | $$
 *                                                                      | $$
 *                                                                      |__/
 */

if ( $_POST['chart_detailed_option'] ) {
	$user_options_str = $_POST['chart_detailed_option'];
	$user_options     = stripcslashes( $user_options_str );
} else {
	$user_options = "{}";
}
$chart_title    = $_POST['chart_title'];
$chart_unit     = $_POST['chart_unit'];
$chart_caption  = $_POST['chart_caption'];
$chart_caption  = str_replace( "\n", "<br />",
	$chart_caption );  // Replace \n with <br />
$chart_type     = $_POST['chart_type'];
$plugin_www_url = $_POST['plugin_www_url'];


$data                   = array();
$data['chart_type']     = $chart_type;
$data['chart_title']    = $chart_title;
$data['chart_unit']     = $chart_unit;
$data['chart_caption']  = $chart_caption;
$data['plugin_www_url'] = $plugin_www_url;
$data['csvfile']        = $csvfile;
$data['excelfile']      = $excelfile;
$data['table']          = $table;

$jsonData  = json_encode( $data );
$key       = date( "YmdHis" );
//$json_path = "json/" . $key . ".json";

//$fp = fopen( $json_path, 'w' );
//fwrite( $fp, $jsonData );
//fclose( $fp );


$chart_url = $chart_type . ".php?key=" . $key;


?>


<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SPRI Chart Management</title>

	<!-- Bootstrap -->
	<link href="<?php echo $bootstrap_path; ?>css/bootstrap.min.css"
	      rel="stylesheet">
	<link href="<?php echo $plugin_www_url; ?>css/custom.css" rel="stylesheet">
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>

	<script
		src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="ace/src-noconflict/ace.js" type="text/javascript"
	        charset="utf-8"></script>
</head>
<body>
<div class="space10"></div>
<form name="frm" id="frm" action="save_chart.php" method="POST">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-8">
				<?php echo $table; ?>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-8">
				<textarea cols="55" rows=6
				          id="table"><?php echo $table; ?></textarea>
			</div>
		</div>
		<div class="space10"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div id="chartHTML">
					<script type="text/javascript" id="chart_bootstrap">
						google.load("visualization", "1", {packages: ["corechart"]});
						var chart;
                        var options;
                        var user_options;
					</script>
                    <script type="text/javascript" id="chart_data_script">
                        var data_json = <?php echo json_encode($newData);?>;
                    </script>
					<script type="text/javascript" id="chart_option_script">
						jQuery(document).ready(function () {
							options = {
                                width:"100%",
                                height:"400",
								title: "<?php echo $chart_unit; ?>",
								titlePostition: 'out',
								legend:{
									position:"top",
									alignment:"center"
								},
								chartArea: {
									width: "85%",
									height: "80%"
								}
							};
							user_options = <?php echo $user_options;?>;
                            jQuery.extend(true, options, user_options);
						});
					</script>
					<script type="text/javascript" id="chart_draw_script">
                        jQuery(document).ready(function () {
                            var data;
							data = google.visualization.arrayToDataTable(data_json);
							google.setOnLoadCallback(drawChart);
							function drawChart() {
								chart = new google.visualization.<?php echo $chart_type?>(document.getElementById('chart_div'));
								google.visualization.events.addListener(chart, 'onmouseover', uselessHandler2);
								google.visualization.events.addListener(chart, 'onmouseout', uselessHandler3);
								function uselessHandler2() {
                                    jQuery('#chart_div').css('cursor', 'pointer')
								}
								function uselessHandler3() {
                                    jQuery('#chart_div').css('cursor', 'default')
								}
								chart.draw(data, options);
							}
                            jQuery(window).resize(function () {
                                chart.draw(data, options)
                            });
						});
					</script>
                </div>

                <div id="chart_div"></div>

				<div id="chart_description">
					<p>
						<?php echo $chart_caption; ?>
					</p>
				</div>
                <div class="form-group">
                    <label>User Chart Data
                    <div class="form-group"
                         id="chart_data_editor"></div>
                    </label>
					<label>User Chart Option
					<div class="form-group"
					     id="chart_option_editor"></div>
                    </label>
					<a href="#chart_div" class="btn btn-primary"
					   id="chart_redraw">Redraw</a>
				</div>

				<textarea cols="100" rows=9 id="htmlArea"></textarea>

				<div class="space10"></div>

				<div
					class="col-xs-8 col-sm-8 col-md-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 ">
					<button type="submit" class="btn btn-success pull-left">
						Save
					</button>
					<button type="button" class="btn btn-danger pull-right">
						Cancel
					</button>
				</div>
			</div>
		</div>

		<input type="hidden" name="chart_title" id="chart_title"
		       value="<?php echo $chart_title; ?>">
		<input type="hidden" name="chart_unit" id="chart_unit"
		       value="<?php echo $chart_unit; ?>">
		<input type="hidden" name="chart_caption" id="chart_caption"
		       value="<?php echo $chart_caption; ?>">

		<input type="hidden" name="chart_type" id="chart_type"
		       value="<?php echo $chart_type; ?>">

		<input type="hidden" name="csvfile_relative" id="csvfile_relative"
		       value="<?php echo $csvfile_relative; ?>">
		<input type="hidden" name="excelfile_relative" id="excelfile_relative"
		       value="<?php echo $excelfile_relative; ?>">

		<input type="hidden" name="table_html" id="table_html" value="">
		<input type="hidden" name="chart_html" id="chart_html" value="">
		<input type="hidden" name="key" id="key" value="<?php echo $vtime; ?>">
</form>
<script>
	function iframeLoaded() {
		var frameID = document.getElementById('chart_frame');
		if (frameID) {
			// here you can make the height, I delete it first, then I make it again
			frameID.height = 0;
			frameID.height = frameID.contentWindow.document.body.scrollHeight + "px";
			frameID.width = 0;
			frameID.width = frameID.contentWindow.document.body.scrollWidth + "px";
		}
	}

    jQuery(document).ready(function () {
		setTimeout(function () {
            $('#htmlArea').text($('#chartHTML').html().replace(/\n|\t/g, '') + '<div id="chart_div"></div>');
        }, 1000);

        jQuery('#frm').submit(function (event) {
            jQuery('#table_html').val($('#table').html());
            jQuery('#chart_html').val($('#htmlArea').html());
//                event.preventDefault();
		});
	});

    jQuery(document).ready(function () {
		option_editor = ace.edit("chart_option_editor");
		option_editor.setTheme("ace/theme/monokai");
		option_editor.getSession().setMode("ace/mode/javascript");
		option_editor.setValue(JSON.stringify(options, null, '\t'));

        data_editor = ace.edit("chart_data_editor");
        data_editor.setTheme("ace/theme/monokai");
        data_editor.getSession().setMode("ace/mode/javascript");
        data_editor.setValue(JSON.stringify(data_json, null, '\t'));
    });


    jQuery('#chart_redraw').click(function () {
		event.preventDefault();
		var options_str = option_editor.getValue();
		var options_json = JSON.parse(options_str);
        var data_str = data_editor.getValue();
        var data_json = JSON.parse(data_str);

        var data_table = google.visualization.arrayToDataTable(data_json);

        chart.draw(data_table, options_json);
		jQuery('#chart_option_script').text('var options =');
		jQuery('#chart_option_script').append(options_str);
        jQuery('#chart_data_script').text('var data_json =');
        jQuery('#chart_data_script').append(data_str);
        $('#htmlArea').text($('#chartHTML').html().replace(/\n|\t/g, '') + '<div id="chart_div"></div>');
	})

</script>
</body>
</html>
