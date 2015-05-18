<?php

$saved_key = $_POST['key'];
$chart_title = $_POST['chart_title'];
$chart_caption = $_POST['chart_caption'];
$chart_type = $_POST['chart_type'];
$csvfile_relative = $_POST['csvfile_relative'];
$excelfile_relative = $_POST['excelfile_relative'];

$table_html =  $_POST['table_html'];
$chart_html =  $_POST['chart_html'];

$uploaddir = getcwd() . "/../../uploads/spri-stat/" ;

$table_file = $uploaddir . $saved_key . ".table.dat" ;
$chart_file = $uploaddir . $saved_key . ".chart.dat" ;

$fp = fopen($table_file , 'w');
fwrite($fp, $table_html);
fclose($fp);

$fp = fopen($chart_file , 'w');
fwrite($fp, $chart_html);
fclose($fp);

global $wpdb;

if( !isset($wpdb) ) {
    $wp_path = dirname ( dirname (dirname(getcwd()) ) );
    include_once($wp_path . '/wp-config.php');
    include_once($wp_path . '/wp-load.php');
    include_once($wp_path  .'/wp-includes/wp-db.php');
}

$data = array(
    "chart_title"=>$chart_title,
    "chart_type"=>$chart_type,
    "csvfile_relative"=>$csvfile_relative,
    "excelfile_relative"=>$excelfile_relative,
    "state"=>'A'
) ;

$data_format = array(
    "%s",
    "%s",
    "%s",
    "%s",
    "%s",
    "%s"
);
$where = array (
    "saved_key"=>$saved_key
);
$table = "wp_spri_chart_list";

$wpdb->update( $table, $data, $where );

echo "<script>alert('수정되었습니다');window.opener.location.reload();window.close();</script>";
