<?php
/*
Plugin Name: SPRI Chart
Plugin URI: http://spri.krt
Description: 통계 차트생성 플러그인 database 변경 작업
Author: binggle@live.co.kr, ungsik.yun@gmail.com
Version: 0.5
Author URI: http://spri.krt
*/

add_action( 'admin_menu', 'spri_chart_create_menu' );
add_action( 'admin_head', 'include_scripts' );
add_action( 'wp_head', 'include_scripts' );

function include_scripts() {
	echo '   <script type="text/javascript" src="https://www.google.com/jsapi"></script>';
	wp_enqueue_script( 'spri-local-js',
		plugins_url( '/js/local_js.js', __FILE__ ), array( 'jquery' ) );

	wp_enqueue_script( 'ajax_form_plugin',
		plugins_url( '/js/jquery.form.js', __FILE__ ), array( 'jquery' ) );

	wp_enqueue_script( 'ace_editor',
		plugins_url( '/ace/src-noconflict/ace.js', __FILE__ ), array(
			'jquery',
		) );

	wp_enqueue_style( 'spri-chart-css',
		plugins_url( 'css/custom.css', __FILE__ ) );

//	Bootstrap
	wp_enqueue_style( 'bootstrap-css',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap-theme-css',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css' );
	wp_enqueue_script( 'bootstrap-js',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js' );

}

function spri_chart_create_menu() {
	add_menu_page( 'SPRI CHART', 'SPRI CHART', 'administrator', __FILE__,
		'spri_chart_admin_page', plugin_dir_url( __FILE__ ) . "/spri.ico" );
}

// admin menu html
function spri_chart_admin_page() {
	global $wpdb;
	?>
	<div id="container_warp" xmlns="http://www.w3.org/1999/html">
		<div class="container-fluid">

			<div class="row">
				<h4> SPRI Chart Management </h4>
			</div>

			<div class="row">
				<input class="btn btn-default" type='button' value='Add new chart' id='display_toggle'>
			</div>

			<div id="new_chart_area row">
				<div class='add_new_chart row' id="add_new_chart_file_uploader">
					<form id="csv_file_upload" class="form-inline" method="post"
					      action="<?php echo admin_url( 'admin-ajax.php' ) ?>">
						<div class="form-group">
							<input class="form-control" name="csv_file" type="file"/>
							<input class="form-control" type="submit"/>
						</div>
					</form>
				</div>
				<div id="chart_type_selector" class="add_new_chart row form-group">
					<label class="radio-inline">
					<input name="chart_type" type="radio" value="ColumnChart">Column Chart</input>
					</label>
					<label class="radio-inline">
					<input name="chart_type" type="radio" value="BarChart">Bar Chart</input>
					</label>
					<label class="radio-inline">
					<input name="chart_type" type="radio" value="ComboChart">Combo Chart</input>
					</label>
					<label class="radio-inline">
					<input name="chart_type" type="radio" value="PieChart">Pie Chart</input>
					</label>
					<label class="radio-inline">
					<input name="chart_type" type="radio" value="LineChart">Line Chart</input>
					</label>
				</div>
				<div class="row">
					<input id="chart_redraw" value="Redraw" type="button" class="btn btn-primary"/>
				</div>

				<div class="row" id="new_chart_draw_area"></div>

				<div id="editor_area" class="add_new_chart row">
					<div id="chart_data" class="editor_warp col-xs-6">
						<script type="text/javascript"
						        id="chart_data_script"></script>
						<div>Data</div>
						<div id="data_editor" class="editor"></div>
					</div>
					<div id="chart_option" class="editor_warp col-xs-6">
						<script type="text/javascript"
						        id="chart_option_script"></script>
						<div>Option</div>
						<div id="option_editor" class="editor"></div>
					</div>
				</div>
			</div>
			<div class="graph_list row">
				<?php foreach ( get_all_graph() as $item ): ?>
					<div id='<?php echo $item->id ?>' class="graph col-xs-6">
						<div class="row">

							<div class="col-xs-6">title: <?php echo $item->title ?></div>
							<div class="col-xs-offset-6"></div>
							<div id='chart_canvas_<?php echo $item->id ?>'
							     class='chart_draw col-lg-12'></div>

							<div id='spri_chart_list'>
								<script type='text/javascript'>
									google.load('visualization', '1', {packages: ['corechart']});
								</script>
								<script type='text/javascript' class='chart_data'>
									var data_<?php echo $item->id ?> = <?php echo $item->chart_data ?>;
								</script>

								<script type='text/javascript' class='chart_opt'>
									var option_<?php echo $item->id ?> = <?php echo $item->chart_opt ?>;
								</script>

								<script type='text/javascript' class='chart_draw'>
									jQuery(document).ready(function () {
										var data;
										data = google.visualization.arrayToDataTable(data_<?php echo $item->id ?>);
										var options = option_<?php echo $item->id ?>;
										google.setOnLoadCallback(drawChart);
										function drawChart() {
											chart_canvas_<?php echo $item->id ?> = new google.visualization.<?php echo $item->chart_type ?>(document.getElementById('chart_canvas_<?php echo $item->id ?>'));
											google.visualization.events.addListener(chart_canvas_<?php echo $item->id ?>, 'onmouseover', uselessHandler2);
											google.visualization.events.addListener(chart_canvas_<?php echo $item->id ?>, 'onmouseout', uselessHandler3);
											function uselessHandler2() {
												jQuery('#chart_canvas_<?php echo $item->id ?>').css('cursor', 'pointer')
											}

											function uselessHandler3() {
												jQuery('#chart_canvas_<?php echo $item->id ?>').css('cursor', 'default')
											}

											chart_canvas_<?php echo $item->id ?>.draw(data, options);
										}

										jQuery(window).resize(function () {
											chart_canvas_<?php echo $item->id ?>.draw(data, options);
										});
									});
								</script>
							</div>
						</div>
					</div>

				<?php endforeach ?>

			</div>
		</div>
	</div>
<?php
//	end spri_chart_admin_page
}

register_activation_hook( __FILE__, 'create_table' );
function create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = $wpdb->prefix . "spri_chart";

	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$sql = 'CREATE TABLE $table_name (
				id INT(11) NOT NULL AUTO_INCREMENT,
				chart_id INT(11) NOT NULL,
				chart_title VARCHAR(100) NOT NULL,
				chart_data TEXT NOT NULL,
				chart_option TEXT NOT NULL,
				chart_type VARCHAR(20) NOT NULL,
				chart_rev INT(11) NOT NULL,
				chart_staus VARCHAR(10) NOT NULL,
				PRIMARY KEY (id)
				) $charset_collate;
				';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}


add_action( 'wp_ajax_spri_ajax_csv_upload', 'spri_ajax_csv_upload' );

// TODO this function assume that file is only euc-kr encoding. may need detecting encoding process.
function spri_ajax_csv_upload() {
	$csv = $_FILES['csv_file'];
	//var_dump( $csv );

	setlocale( LC_CTYPE, 'ko_KR.eucKR' );
	$fp      = fopen( $csv['tmp_name'], 'r' );
	$raw_data = array();

	while ( $fdata = fgetcsv( $fp ) ) {

		$tmp_data = array();
		foreach ( $fdata as $data ) {
			$tmp_data[] = iconv( "euc-kr", "UTF-8", $data );
		}

		$raw_data[] = $tmp_data;
	}

	// 2D array transpose
	array_unshift($raw_data, null);
	$raw_data = call_user_func_array("array_map", $raw_data);

	// number string to number
	$result= array();
	foreach($raw_data as $row){
		$tmp1 = array_map( "floatVal", array_slice($row,1));
		array_unshift($tmp1, $row[0]  );
		$result[] = $tmp1;
	}

	// set column header
	$result[0] = $raw_data[0];

	echo( json_encode( $result) );

	wp_die();
}

function get_all_graph() {
	global $wpdb;

	return $wpdb->get_results( "
		select * from wp_spri_chart
		where chart_staus = 'p';
	" );

}