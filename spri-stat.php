<?php
/*
Plugin Name: SPRI Chart
Plugin URI: http://spri.krt
Description: 통계 차트생성 플러그인 database 변경 작업
Author: binggle@live.co.kr
Version: 0.4
Author URI: http://spri.krt
*/

add_action('admin_head', 'my_custom_js');
add_action('wp_head', 'my_custom_js');
function my_custom_js() {
	echo '   <script type="text/javascript" src="https://www.google.com/jsapi"></script>';
}

register_activation_hook(__FILE__, 'create_table');
function create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . "spri_chart";


	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE $table_name (
				id INT(11) NOT NULL AUTO_INCREMENT,
				chart_id INT(11) NOT NULL,
				chart_data TEXT NOT NULL,
				chart_opt TEXT NOT NULL,
				chart_type VARCHAR(20) NOT NULL COLLATE 'utf8_unicode_ci',
				chart_rev INT(11) NOT NULL,
				PRIMARY KEY (id)
				) $charset_collate;
				";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}

add_action('admin_menu', 'spri_chart_create_menu');
function spri_chart_create_menu() {
	add_menu_page('SPRI CHART', 'SPRI CHART', 'administrator', __FILE__, 'spri_chart_admin_page' , plugin_dir_url( __FILE__ ) ."/spri.ico" );
}
// admin menu html
function spri_chart_admin_page( ) {
	global $wpdb;
	?>
	<div class="wrap">
		<h4> SPRI Chart Management </h4>
	</div>

<?php
}



add_shortcode( 'spri_chart', 'spri_chart_draw_shortcode' );
function spri_chart_draw_shortcode( $atts ) {
    global $wpdb;

    $a = shortcode_atts( array(
        'id' => '1',
    ), $atts );
    $sql = "select idx, saved_key, chart_title, chart_title, csvfile_relative, excelfile_relative, chart_type from wp_spri_chart_list ";
    $sql .= " WHERE state='A'  AND idx = {$a['id']}";
    $chart = $wpdb->get_row (
        $sql
    );
    $saved_key = $chart->saved_key;
    $uploaddir = plugin_dir_path(__FILE__) . "/../../uploads/spri-stat/";
    $chart_file = $uploaddir . $saved_key . ".chart.dat" ;

    $chart_code = file_get_contents($chart_file) ;

    return '<div class="chart">'.html_entity_decode ($chart_code).'</div>';
}
