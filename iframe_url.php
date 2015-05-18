<?php

if ( ! isset ($_GET) ) {
    exit();
}
if ( ! isset ($_GET['key']) ) {
    exit();
}
if ( ! isset ($_GET['www_url']) ) {
    exit();
}
$saved_key = $_GET['key'];
$www_url = $_GET['www_url'];


$bootstrap_path = $www_url  . "bootstrap-3.3.2-dist/";

global $wpdb;
if( !isset($wpdb) ) {
    $wp_path = dirname ( dirname (dirname(getcwd()) ) );
    include_once($wp_path . '/wp-config.php');
    include_once($wp_path . '/wp-load.php');
    include_once($wp_path  .'/wp-includes/wp-db.php');
}

$sql = "select idx, saved_key, chart_title, chart_title, csvfile_relative, excelfile_relative, chart_type, chart_caption from wp_spri_chart_list ";
$sql .= " WHERE state='A'  AND saved_key = $saved_key ";


$chart = $wpdb->get_row (
    $sql
);
$no = $chart->idx ;
$saved_key = $chart->saved_key;

$chart_title = $chart->chart_title ;
$chart_caption = $chart->chart_caption;
$csv_file_name_org = $chart->csvfile_relative ;
$excel_file_name_org = $chart->excelfile_relative ;
$chart_type = $chart->chart_type ;

$uploaddir = getcwd() . "/../../uploads/spri-stat/" ;

$href_excel = $www_url . $excel_file_name_org ;


$table_file = $uploaddir . $saved_key . ".table.dat" ;
$chart_file = $uploaddir . $saved_key . ".chart.dat" ;

$table_html = file_get_contents($table_file) ;
$chart_html = file_get_contents($chart_file) ;

setlocale(LC_CTYPE, 'ko_KR.eucKR');
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPRI Chart Management</title>
    <link href="<?php echo $bootstrap_path;?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $www_url;?>css/custom.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
<div class="space10"></div>
<div class="container-fluid">
    <div class="space10"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h3><?php echo $chart_title;?></h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div id="chartHTML"><?php echo  htmlspecialchars_decode ($chart_html ); ?></div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <?php echo htmlspecialchars_decode($table_html );?>
        </div>

        <br/>

        <div>
            <p>
                <?php echo $chart_caption; ?>
            </p>
        </div>
        <?php
            if ($excel_file_name_org !=""){
        ?>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="download pull-right"><a href='<?php echo $href_excel ;?>' target='_blank' >data download</a></a></div>
        </div>
        <?php
            }
        ?>
    </div>
</body>
</html>