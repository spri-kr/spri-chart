<?php
/*
Plugin Name: SPRI Chart
Plugin URI: http://spri.krt
Description: 통계 차트생성  플러그인
Author: binggle@live.co.kr
Version: 0.4
Author URI: http://spri.krt
*/

add_action('admin_menu', 'spri_chart_create_menu');
add_action('admin_head', 'my_custom_js');
add_action('wp_head', 'my_custom_js');
function my_custom_js() {
	echo '   <script type="text/javascript" src="https://www.google.com/jsapi"></script>';
}

function spri_chart_admin_page( ) {
	global $wpdb;


//	echo plugin_dir_url(__FILE__) ;
?>
	<style>
		.view-chart {
			padding-right: 5px;
			cursor: hand  ;
			cursor: pointer ;
		}

		.btn-edit-chart{
			padding-right: 5px;
			cursor: hand  ;
			cursor: pointer ;
		}

		.view-chart:hover {
			padding-right: 5px;
		}

		.pagination{
			/*border:1px solid green;*/
			text-align: center;;
			width:50%;
			margin-left:25%;
		}
	</style>
	<div class="wrap">
		<h4> SPRI Chart Management </h4>
	</div>
	<?php

	if( isset ( $_GET['p']) ) {
		$p= $_GET['p'] ;
	} else {
		$p=  1;
	}

	$offset = ( $p - 1) * 15 ;

	$sql = "select count( saved_key ) as cnt  from wp_spri_chart_list ";
	$sql .= " WHERE state='A'    ";
	$total_rows = $wpdb->get_var (
		$sql
	);

	$sql = "select idx, saved_key, chart_title, chart_title, csvfile_relative, excelfile_relative, chart_type from wp_spri_chart_list ";
	$sql .= " WHERE state='A' ORDER BY saved_key DESC ";
	$sql .= " LIMIT 15 OFFSET $offset";

	$chart_list = $wpdb->get_results (
		$sql
	);

?>

	<table class="widefat">
		<thead>
		<tr>
			<th> 번호</th>
			<th> Chart Title</th>
			<th> CSV File Saved </th>
			<th> EXCEL File Saved </th>
			<th> Chart Type  </th>
			<th> Created Date</th>
			<th> Links </th>
		</tr>
		</thead>
	<?php
	$www_url = plugin_dir_url(__FILE__) ;
	$_SESSION['www_url'] = $www_url;


	foreach ( $chart_list as $chart) {
		$no = $chart->idx ;
		$saved_key_org = $chart->saved_key;
		$saved_key = substr($saved_key_org, 4,2 ) . "/" . substr($saved_key_org, 6,2 ) . " " .substr($saved_key_org, 8,2 ) . ":" .substr($saved_key_org, 10,2 )  ;
		$chart_title = $chart->chart_title ;
		$csv_file_name_org = $chart->csvfile_relative ;
		$excel_file_name_org = substr($chart->excelfile_relative, 1) ;
		$chart_type = $chart->chart_type ;

		$path_parts = pathinfo($csv_file_name_org );
		$csv_file_name = $path_parts['basename'] ;
		$path_parts = pathinfo($excel_file_name_org );
		$excel_file_name = $path_parts['basename'] ;

		$href_excel = $www_url . $excel_file_name_org ;

		$view_link = $www_url . "view.php?plugin_www_url=" . plugin_dir_url(__FILE__)  . "&key=" . $saved_key_org ;

		$iframe_url = $www_url . "iframe_url.php?www_url=" . $www_url ."&key=" . $saved_key_org ;

		$imgsrc = plugin_dir_url(__FILE__) . "images/view.png" ;

		echo "<tr><td>$no</td><td>$chart_title</td><td> $csv_file_name</td>";
		echo "<td><a href='" . $href_excel  . "' target='_blank' >$excel_file_name</a></td><td>$chart_type</td>";
		echo "<td>$saved_key</td><td><a href='$iframe_url' target='_new' >iframe</a> / <span class='view-chart'  data-key='$saved_key_org' data-url='$view_link'> View </span> " ;
		echo "/ <span class='btn-edit-chart'  data-key='$saved_key_org' > Edit </span></td></tr>";
	}
	echo "</table>";

	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url_page =strtok($_SERVER["REQUEST_URI"],'?');
	$url_array= parse_url($actual_link) ;
	$query_string =$url_array['query'] ;
	$query_array = array();
	parse_str ($query_string, $query_array);

	$maxPage = ceil( $total_rows / 15);
	$firstPage = $p - 2 ;
	$lastPage = $p + 2 ;

	if ($firstPage < 1 ) {
		$firstPage = 1;
	}

	if($lastPage >$maxPage ) {
		$lastPage = $maxPage;
	}
?>
	<div class="wrap">
		<div class="pagination">
	<?php
	for($ii =  $firstPage ; $ii <= $lastPage ; $ii++){
		$query_array['p'] = $ii ;
		$new_query_string = http_build_query ($query_array) ;
		$new_url = $url_page . "?" . $new_query_string;
		echo "<a href=$new_url >$ii</a>";
	}

	?>
		</div>
	</div>


	<div class="wrap">
		<button    class="btn btn-primary" id="btn-new-chart" >Create New Chart </button>
	</div>

	<script>
		var plugin_www_url = '<?php echo plugin_dir_url(__FILE__) ;?>' ;
		var plugins_url = '<?php echo plugins_url() ;?>' ;

		(function($){

			function popupwindow(url, title, w, h) {
				wLeft = window.screenLeft ? window.screenLeft : window.screenX;
				wTop = window.screenTop ? window.screenTop : window.screenY;

				var left = wLeft + (window.innerWidth / 2) - (w / 2);
				var top = wTop + (window.innerHeight / 2) - (h / 2);
				return window.open(url, title, 'toolbar=yes, location=yes, directories=no, status=yes, menubar=no, scrollbars=yes, resizable=yes,  width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
			}

			$(document).ready(function(){
				var popup_url = plugin_www_url + "new_chart.php?plugin_www_url="; // + plugin_www_url ;
				var edit_url = plugin_www_url + "edit_chart.php?plugin_www_url="; // + plugin_www_url ;

				$('#btn-new-chart').on('click', function(){
					var new_chart_window  = popupwindow(popup_url, 'new Chart', 900, 850);
					if (window.focus) {new_chart_window.focus()}
					if (!new_chart_window.closed) {new_chart_window.focus()}

				}) ;
				$('.btn-edit-chart').on('click', function(){
					var key = $(this).attr('data-key') ;
					var url = edit_url + '&key='+ key
					var new_edit_window =popupwindow(url, 'Edit Chart', 900, 850);
					if (window.focus) {new_edit_window.focus()}
					if (!new_edit_window.closed) {new_edit_window.focus()}
				}) ;
				$('.view-chart').on('click', function(){
					var view_url = $(this).attr('data-url') ;
					var view_chart = popupwindow(view_url, 'Chart View', 900, 850);

					if (window.focus) {view_chart.focus()}
					if (!view_chart.closed) {view_chart.focus()}
				}) ;
			});
		})(jQuery);
	</script>
<?php
}


function spri_chart_create_menu() {
//	add_action( 'admin_init', 'register_mysettings' );
// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	create_table() ;

	add_menu_page('SPRI CHART', 'SPRI CHART', 'administrator', __FILE__, 'spri_chart_admin_page' , plugin_dir_url( __FILE__ ) ."/spri.ico" );
}

function create_table(){
	global $wpdb;
	$table_name = "wp_spri_chart_list";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE `wp_spri_chart_list` (
				`idx` INT(11) NOT NULL AUTO_INCREMENT,
				`saved_key` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8_unicode_ci',
				`chart_title` VARCHAR(200) NOT NULL DEFAULT '0' COLLATE 'utf8_unicode_ci',
				`chart_unit` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
				`chart_caption` VARCHAR(250) NOT NULL COLLATE 'utf8_unicode_ci',
				`csvfile_relative` VARCHAR(200) NOT NULL COLLATE 'utf8_unicode_ci',
				`excelfile_relative` VARCHAR(200) NOT NULL COLLATE 'utf8_unicode_ci',
				`chart_type` VARCHAR(20) NOT NULL COLLATE 'utf8_unicode_ci',
				`state` VARCHAR(1) NOT NULL DEFAULT 'R' COLLATE 'utf8_unicode_ci',
				PRIMARY KEY (`idx`),
				UNIQUE INDEX `INDEX_SPRI 1` (`saved_key`)
				)
				COLLATE='utf8_unicode_ci'
				AUTO_INCREMENT=1; ";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}
//function register_mysettings() {
//	register_setting( 'spri_chart_setting', 'font_name' );
//	register_setting( 'spri_chart_setting', 'font_tags' );
//}
/***
 *      /$$$$$$  /$$                             /$$                                     /$$
 *     /$$__  $$| $$                            | $$                                    | $$
 *    | $$  \__/| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$          /$$$$$$$  /$$$$$$   /$$$$$$$  /$$$$$$
 *    |  $$$$$$ | $$__  $$ /$$__  $$ /$$__  $$|_  $$_/         /$$_____/ /$$__  $$ /$$__  $$ /$$__  $$
 *     \____  $$| $$  \ $$| $$  \ $$| $$  \__/  | $$          | $$      | $$  \ $$| $$  | $$| $$$$$$$$
 *     /$$  \ $$| $$  | $$| $$  | $$| $$        | $$ /$$      | $$      | $$  | $$| $$  | $$| $$_____/
 *    |  $$$$$$/| $$  | $$|  $$$$$$/| $$        |  $$$$/      |  $$$$$$$|  $$$$$$/|  $$$$$$$|  $$$$$$$
 *     \______/ |__/  |__/ \______/ |__/         \___/         \_______/ \______/  \_______/ \_______/
 *
 *
 *
 */
function spri_chart_getter( $atts ) {
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
add_shortcode( 'spri_chart', 'spri_chart_getter' );