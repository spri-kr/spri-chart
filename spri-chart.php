<?php
/*
Plugin Name: SPRI Chart Manager
Plugin URI: http://spri.kr
Description: Chart plugin for wordpress using google jsapi chart
Author: ungsik.yun@gmail.com, binggle@live.co.kr
Version: 1.1
Author URI: http://spri.kr
*/

add_action( 'admin_menu', 'spri_chart_admin_menu' );

function spri_chart_admin_menu() {
	$spri_chart_hook = add_menu_page( 'SPRI CHART Manager', 'SPRI CHART Manager', 'administrator', "spri-chart",
		'spri_chart_draw_admin', plugin_dir_url( __FILE__ ) . "/spri.ico" );

	add_action( 'load-' . $spri_chart_hook, "spri_chart_loads" );
}

function spri_chart_loads() {

	add_action( 'admin_enqueue_scripts', "spri_chart_load_js" );
	add_action( 'admin_enqueue_scripts', "spri_chart_load_css" );
	add_action( 'admin_enqueue_scripts', "spri_chart_load_lib" );

}

function spri_chart_load_js() {
	wp_enqueue_script( 'spri-local-js',
		plugins_url( '/src/js/local_js.js', __FILE__ ), array( 'jquery' ) );
}

function spri_chart_load_css() {
	wp_enqueue_style( 'spri-chart-css',
		plugins_url( '/src/css/custom.css', __FILE__ ) );
}

function spri_chart_load_lib() {

	echo '   <script type="text/javascript" src="https://www.google.com/jsapi"></script>';

	wp_enqueue_script( 'ajax_form_plugin',
		plugins_url( '/src/js/jquery.form.js', __FILE__ ), array( 'jquery' ) );

	wp_enqueue_script( 'ace_editor',
		plugins_url( '/src/ace/src-noconflict/ace.js', __FILE__ ), array(
			'jquery',
		) );

	//	Bootstrap
	wp_enqueue_style( 'bootstrap-css',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap-theme-css',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css' );
	wp_enqueue_script( 'bootstrap-js',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js' );
}

// admin menu html
function spri_chart_draw_admin() {
	global $wpdb;
	?>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages: ['corechart']});
	</script>
	<div id="container_warp" xmlns="http://www.w3.org/1999/html">
		<div class="container-fluid">

			<div class="row">
				<h4> SPRI Chart Management </h4>
			</div>

			<div class="row">
				<button class="btn btn-default" id='display_toggle'>Add new chart</button>
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
				<div id="chart_type_selector" class="add_new_chart row form-group upload_data">
					<label class="radio-inline">
						<input checked name="chart_type" type="radio" value="ColumnChart">Column Chart</input>
					</label>
					<label class="radio-inline">
						<input name="chart_type" type="radio" value="BarChart">Bar Chart</input>
					</label>
					<label class="radio-inline">
						<input name="chart_type" type="radio" value="PieChart">Pie Chart</input>
					</label>
					<label class="radio-inline">
						<input name="chart_type" type="radio" value="LineChart">Line Chart</input>
					</label>
					<label class="radio-inline">
						<input name="chart_type" type="radio" value="ComboChart">Combo Chart</input>
					</label>
				</div>

				<div class="row add_new_chart">
					<input id="new_chart_title" type="text" name="new_chart_title" class="upload_data"/>
					<input id="chart_redraw" value="Draw" type="button" class="btn btn-primary"/>
					<input id="new_chart_upload" class="btn btn-success" type="button" value="Upload"/>
				</div>

				<div class="row add_new_chart col-xs-6">
					<div id="new_chart_draw_area"></div>
				</div>

				<div id="editor_area" class="add_new_chart row col-xs-6">
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
			<hr/>
			<div class="graph_list row">
				<?php $loop_counter = 0; ?>
				<?php $chart_per_row = 2; ?>
				<?php foreach ( spri_chart_get_all_graph() as $item ): ?>
					<?php $loop_counter += 1; ?>
					<?php if ( $loop_counter % $chart_per_row == 1 ): ?>
						<div class="chart_row row">
					<?php endif; ?>
					<div id='chart_<?php echo $item->chart_id ?>' class="chart col-xs-6">
						<div class="row chart_data">
							<h4 class="col-xs-1"><?php echo $item->chart_id ?></h4>
							<h4 id="chart_<?php echo $item->chart_id ?>_title"
							    class="col-xs-10"><?php echo $item->chart_title ?></h4>

							<div id='chart_canvas_<?php echo $item->chart_id ?>'
							     class='chart_draw col-lg-12'></div>
							<div class="chart_scripts">
								<script type='text/javascript' id="chart_data_<?php echo $item->chart_id ?>"
								        class='chart_data'>
									var data_<?php echo $item->chart_id ?> = <?php echo $item->chart_data ?>;
								</script>

								<script type='text/javascript' id="chart_option_<?php echo $item->chart_id ?>"
								        class='chart_opt'>
									var option_<?php echo $item->chart_id ?> = <?php echo $item->chart_option ?>;
								</script>

								<script type="text/javascript" id="chart_type_<?php echo $item->chart_id ?>">
									var type_<?php echo $item->chart_id ?> = "<?php echo $item->chart_type ?>"
								</script>

								<script type='text/javascript' class='chart_draw'>
									jQuery(document).ready(function () {
										var data;
										data = google.visualization.arrayToDataTable(data_<?php echo $item->chart_id ?>);
										var options = option_<?php echo $item->chart_id ?>;
										google.setOnLoadCallback(drawChart);
										function drawChart() {
											chart_canvas_<?php echo $item->chart_id ?> = new google.visualization.<?php echo $item->chart_type ?>(document.getElementById('chart_canvas_<?php echo $item->chart_id ?>'));
											google.visualization.events.addListener(chart_canvas_<?php echo $item->chart_id ?>, 'onmouseover', uselessHandler2);
											google.visualization.events.addListener(chart_canvas_<?php echo $item->chart_id ?>, 'onmouseout', uselessHandler3);
											function uselessHandler2() {
												jQuery('#chart_canvas_<?php echo $item->chart_id ?>').css('cursor', 'pointer')
											}

											function uselessHandler3() {
												jQuery('#chart_canvas_<?php echo $item->chart_id ?>').css('cursor', 'default')
											}

											chart_canvas_<?php echo $item->chart_id ?>.draw(data, options);
										}

										jQuery(window).resize(function () {
											chart_canvas_<?php echo $item->chart_id ?>.draw(data, options);
										});
									});
								</script>
							</div>
							<!--	End Chart script end-->
						</div>
						<!-- End chart_data-->

						<div class="row chart_editor_area"></div>
						<div class="row chart_control_buttons">
							<div class="col-xs-6">
								<button id="edit_<?php echo $item->chart_id ?>"
								        class="btn btn-primary btn-block chart_edit_btn"
								        type="button" chart_id="<?php echo $item->chart_id ?>">Edit
								</button>
							</div>

							<div class="col-xs-6">
								<button id="delete_<?php echo $item->chart_id ?>"
								        class="btn btn-danger btn-block chart_delete_btn"
								        type="button" chart_id="<?php echo $item->chart_id ?>"
										row_id="<?php echo $item->id ?>">Delete
								</button>
							</div>
						</div>
						<!--	End chart_control_buttons-->

						<div class="chart_edit_area row" id="chart_edit_area_<?php echo $item->chart_id ?>">
							<div class="col-xs-12">

								<div class="row">
									<div class="form-group">

										<div class="col-xs-6">
											<input class="form-control" type="text"
											       id="chart_<?php echo $item->chart_id ?>_title_editor"/>
										</div>

										<div class="col-xs-6">
											<div class="row">
												<div class="col-xs-6">
													<button id="" class="btn btn-primary btn-block chart_edit_draw_btn"
													        chart_id="<?php echo $item->chart_id ?>">
														Draw
													</button>

												</div>
												<div class="col-xs-6">
													<button id="update_<?php echo $item->chart_id ?>"
													        class="btn btn-success btn-block chart_update_btn"
													        type="button" chart_id="<?php echo $item->chart_id ?>">
														Update
													</button>
												</div>
											</div>

										</div>
									</div>

								</div>
								<div class="row">
									<div id="chart_<?php echo $item->chart_id ?>_type_selector_editor"
									     class="col-xs-12 form-group">
										<label class="radio-inline">
											<input name="chart_<?php echo $item->chart_id ?>_type" type="radio"
											       value="ColumnChart">Column
											Chart</input>
										</label>
										<label class="radio-inline">
											<input name="chart_<?php echo $item->chart_id ?>_type" type="radio"
											       value="BarChart">Bar Chart</input>
										</label>
										<label class="radio-inline">
											<input name="chart_<?php echo $item->chart_id ?>_type" type="radio"
											       value="PieChart">Pie Chart</input>
										</label>
										<label class="radio-inline">
											<input name="chart_<?php echo $item->chart_id ?>_type" type="radio"
											       value="LineChart">Line Chart</input>
										</label>
										<label class="radio-inline">
											<input name="chart_<?php echo $item->chart_id ?>_type" type="radio"
											       value="ComboChart">Combo Chart</input>
										</label>
									</div>
								</div>

								<div class="row">
									<div class="col-xs-12">
										<div class="row">

											<div id="chart_<?php echo $item->chart_id ?>_data_editor"
											     class="editor col-xs-6">

											</div>
											<div id="chart_<?php echo $item->chart_id ?>_option_editor"
											     class="editor col-xs-6">
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<!--	End chart_edit_area-->

					</div>
					<!--End graph-->
					<?php if ( $loop_counter % $chart_per_row == 0 ): ?>
						</div>
					<?php endif; ?>
				<?php endforeach ?>


			</div>
			<!-- End graph_list-->
		</div>
		<!--	End container-fluid-->
	</div>
	<!--	End container_warp-->
<?php
//	end spri_chart_admin_page
}

register_activation_hook( __FILE__, 'spri_chart_create_table' );
function spri_chart_create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = $wpdb->prefix . "spri_chart";

	//if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
	$sql = "CREATE TABLE $table_name (
				id INT(11) NOT NULL AUTO_INCREMENT,
				chart_id INT(11) NOT NULL,
				chart_title VARCHAR(100) NOT NULL,
				chart_data TEXT NOT NULL,
				chart_option TEXT NOT NULL,
				chart_type VARCHAR(20) NOT NULL,
				chart_rev INT(11) NOT NULL,
				chart_status VARCHAR(10) NOT NULL,
				PRIMARY KEY (id),
				index (chart_id),
				index (chart_status)
				) $charset_collate;
				";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	//}
}


add_action( 'wp_ajax_spri_ajax_csv_upload', 'spri_ajax_csv_upload' );

// TODO this function assume that file is only euc-kr encoding. may need detecting encoding process.
function spri_ajax_csv_upload() {
	$csv = $_FILES['csv_file'];
	//var_dump( $csv );

	setlocale( LC_CTYPE, 'ko_KR.eucKR' );
	$fp       = fopen( $csv['tmp_name'], 'r' );
	$raw_data = array();

	while ( $fdata = fgetcsv( $fp ) ) {

		$tmp_data = array();
		foreach ( $fdata as $data ) {
			$tmp_data[] = iconv( "euc-kr", "UTF-8", $data );
		}

		$raw_data[] = $tmp_data;
	}

	// 2D array transpose
	array_unshift( $raw_data, null );
	$raw_data = call_user_func_array( "array_map", $raw_data );

	// number string to number
	$result_data = array();
	foreach ( $raw_data as $row ) {
		$tmp1 = array_map( "floatVal", array_slice( $row, 1 ) );
		array_unshift( $tmp1, $row[0] );
		$result_data[] = $tmp1;
	}

	// set column header
	$result_data[0] = $raw_data[0];

	$result['data']  = $result_data;
	$result['title'] = $_FILES['csv_file']['name'];


	echo( json_encode( $result ) );

	wp_die();
}


add_action( 'wp_ajax_spri_ajax_db_insert', 'spri_ajax_db_insert' );
function spri_ajax_db_insert() {
	global $wpdb;
	$table_name = $wpdb->prefix . "spri_chart";
	$data       = $_POST["pkg"];

	$chart_lastest_id = $wpdb->get_var( "
	SELECT max(chart_id) from $table_name
	" );
	if ( $chart_lastest_id == null ) {
		$chart_lastest_id = 0;
	}

	$insert_data = array(
		"chart_id"     => $chart_lastest_id + 1,
		"chart_title"  => $data['title'],
		"chart_data"   => stripslashes( $data['data'] ),
		"chart_option" => stripslashes( $data['option'] ),
		"chart_type"   => $data['type'],
		"chart_rev"    => 1,
		"chart_status" => "P"

	);

	//print_r($chart_lastest_id);
	print_r( $insert_data );

	$wpdb->insert( $table_name, $insert_data, array(
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',
		'%d',
		'%s',
	) );

	wp_die();

}


add_action( 'wp_ajax_spri_ajax_chart_update', 'spri_ajax_chart_update' );
function spri_ajax_chart_update() {
	global $wpdb;
	$table_name = $wpdb->prefix . "spri_chart";
	$data       = $_POST["pkg"];
	$chart_id   = $data['chart_id'];

	$prev_chart_rev = $wpdb->get_row( "
	SELECT chart_rev from $table_name WHERE chart_id = $chart_id
	ORDER by chart_rev desc limit 1;
	" )->chart_rev;

	$insert_data = array(
		"chart_id"     => $data['chart_id'],
		"chart_title"  => $data['title'],
		"chart_data"   => stripslashes( $data['data'] ),
		"chart_option" => stripslashes( $data['option'] ),
		"chart_type"   => $data['type'],
		"chart_rev"    => $prev_chart_rev + 1,
		"chart_status" => "P"

	);

	print_r( $prev_chart_rev );
	//print_r( $insert_data );

	$wpdb->insert( $table_name, $insert_data, array(
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',
		'%d',
		'%s',
	) );

	wp_die();
}

add_action( 'wp_ajax_spri_ajax_chart_delete', 'spri_ajax_chart_delete' );
function spri_ajax_chart_delete(){
	global $wpdb;
	$table_name = $wpdb->prefix . "spri_chart";
	$data       = $_POST["pkg"];

	$update_data = array(
		"chart_status" => "D"
	);

	$wpdb->update( $table_name, $update_data,
		array("id"=>$data['row_id']),
		array(	'%s') );

	wp_die();

}

function spri_chart_get_all_graph() {
	global $wpdb;
	$table_name = $wpdb->prefix . "spri_chart";

	return $wpdb->get_results( "
		select t1.* from $table_name as t1
  INNER JOIN
  (
    SELECT chart_id, max(chart_rev) as max_rev
    from $table_name
    GROUP BY chart_id
    ) as t2
  on t1.chart_id = t2.chart_id
  and t1.chart_rev = t2.max_rev
where chart_status = 'P' order by chart_id desc ;
	" );

}

//add_shortcode("spri_chart", "chart_shortcode");
add_shortcode( "new_spri_chart", "spri_chart_shortcode" );
function spri_chart_shortcode( $attr ) {

	$attrs = shortcode_atts( array(
		'id' => - 1
	), $attr );

	if ( $attrs['id'] == - 1 ) {
		$r = "Please input ID number";

		return $r;
	} else {
		global $wpdb;
		$table_name = $wpdb->prefix . "spri_chart";
		$id         = $attr['id'];

		$item = $wpdb->get_row( "
		select * from $table_name where chart_id = $id order by chart_rev desc limit 1;
		" );

		$r = <<<RESULT_TEXT
<div>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type='text/javascript'>
    google.load('visualization', '1', {
        packages: ['corechart']
    });
    </script>
    <script type='text/javascript' class='chart_data'>
    var data_$item->chart_id = $item->chart_data;
    </script>
    <script type='text/javascript' class='chart_opt'>
    var option_$item->chart_id = $item->chart_option;
    </script>
    <div id='chart_canvas_$item->chart_id' class='chart_draw col-lg-12'></div>
    <script type='text/javascript' class='chart_draw'>
    jQuery(document).ready(function() {
        var data;
        data = google.visualization.arrayToDataTable(data_$item->chart_id);
        var options = option_$item->chart_id;
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            chart_canvas_$item->chart_id = new google.visualization.$item->chart_type(document.getElementById('chart_canvas_$item->chart_id'));
            google.visualization.events.addListener(chart_canvas_$item->chart_id, 'onmouseover', uselessHandler2);
            google.visualization.events.addListener(chart_canvas_$item->chart_id, 'onmouseout', uselessHandler3);

            function uselessHandler2() {
                jQuery('#chart_canvas_$item->chart_id').css('cursor', 'pointer')
            }

            function uselessHandler3() {
                jQuery('#chart_canvas_$item->chart_id').css('cursor', 'default')
            }

            chart_canvas_$item->chart_id.draw(data, options);
        }

        jQuery(window).resize(function() {
            chart_canvas_$item->chart_id.draw(data, options);
        });
    });
    </script>
</div>

RESULT_TEXT;


		return $r;
	}
}