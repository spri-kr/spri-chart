<?php
/*
Plugin Name: SPRI Chart
Plugin URI: http://spri.krt
Description: 통계 차트생성 플러그인 database 변경 작업
Author: binggle@live.co.kr
Version: 0.4
Author URI: http://spri.krt
*/

add_action( 'admin_menu', 'spri_chart_create_menu' );
add_action( 'admin_head', 'my_custom_js' );
add_action( 'wp_head', 'my_custom_js' );

function my_custom_js() {
	echo '   <script type="text/javascript" src="https://www.google.com/jsapi"></script>';
	wp_enqueue_script( 'spri_add_new_chart',
		plugins_url( '/js/add_new_chart.js', __FILE__ ), array( 'jquery' ) );
}

function spri_chart_create_menu() {
	add_menu_page( 'SPRI CHART', 'SPRI CHART', 'administrator', __FILE__,
		'spri_chart_admin_page', plugin_dir_url( __FILE__ ) . "/spri.ico" );
}

// admin menu html
function spri_chart_admin_page() {
	global $wpdb;
	?>
	<div class="wrap">
		<h4> SPRI Chart Management </h4>
	</div>
	<style>
		.display-none{
			display: none;
		}
	</style>
	<?php

	echo "
<input type='button' value='Add new chart' id='show_add'>

<div class='add_new_chart'>
<form action='wp_ajax.php'>
<input type=file/>
</form>


</div>

<div id='spri_chart_list'>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages: ['corechart']});
	</script>";

	foreach ( spri_chart_db_get_whole_data() as $item ) {

		echo "
	<div id='$item->id' style='width: 700px; float: left;'>
	<div>title: $item->title</div>

	<div id='chart_canvas_$item->id' class='draw'></div>

	<script type='text/javascript' class='chart_data'>
		var data_$item->id = $item->chart_data ;
	</script>

	<script type='text/javascript' class='chart_opt'>
	var option_$item->id = $item->chart_opt ;
	</script>

	<script type='text/javascript' class='chart_draw'>
	jQuery(document).ready(function () {
	    var data;
		data = google.visualization.arrayToDataTable(data_$item->id);
		var options = option_$item->id;
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			chart_canvas_$item->id = new google.visualization.$item->chart_type(document.getElementById('chart_canvas_$item->id'));
			google.visualization.events.addListener(chart_canvas_$item->id, 'onmouseover', uselessHandler2);
			google.visualization.events.addListener(chart_canvas_$item->id, 'onmouseout', uselessHandler3);
			function uselessHandler2() {
	            jQuery('#chart_canvas_$item->id').css('cursor', 'pointer')
			}
			function uselessHandler3() {
	            jQuery('#chart_canvas_$item->id').css('cursor', 'default')
			}
			chart_canvas_$item->id.draw(data, options);
		}
	    jQuery(window).resize(function () {
	        chart_canvas_$item->id.draw(data, options)
	    });
	});
	</script>
</div>";
	}
	echo "</div>";
}

register_activation_hook( __FILE__, 'create_table' );
function create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = $wpdb->prefix . "spri_chart";

	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE $table_name (
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
				";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}

add_shortcode( 'spri_chart', 'spri_chart_draw_shortcode' );
function spri_chart_draw_shortcode( $atts ) {
	global $wpdb;

	$a   = shortcode_atts( array(
		'id' => '1',
	), $atts );
	$sql = "select idx, saved_key, chart_title, chart_title, csvfile_relative, excelfile_relative, chart_type from wp_spri_chart_list ";
	$sql .= " WHERE state='A'  AND idx = {$a['id']}";
	$chart      = $wpdb->get_row(
		$sql
	);
	$saved_key  = $chart->saved_key;
	$uploaddir  = plugin_dir_path( __FILE__ ) . "/../../uploads/spri-stat/";
	$chart_file = $uploaddir . $saved_key . ".chart.dat";

	$chart_code = file_get_contents( $chart_file );

	return '<div class="chart">' . html_entity_decode( $chart_code ) . '</div>';
}

//add_action( 'wp_ajax_spri_ajax', 'spri_ajax' );

function spri_ajax() {
	$whatever = intval( $_POST['whatever'] );

	$whatever += 10;

	echo $whatever;

	wp_die(); // this is required to terminate immediately and return a proper response
}

//add_action( 'admin_enqueue_scripts', 'spri_ajax_hook' );
function spri_ajax_hook( $hook ) {
	wp_enqueue_script( 'spri_ajax_script',
		plugins_url( '/js/local_ajax.js', __FILE__ ), array( 'jquery' ) );

	wp_localize_script( 'spri_ajax_script', 'ajax_object',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'we_value'   => 1234,
			'text_value' => 111111
		) );
}

function spri_chart_db_get_whole_data() {
	global $wpdb;

	return $wpdb->get_results( "
		select * from wp_spri_chart
		where chart_staus = 'p';
	" );

}