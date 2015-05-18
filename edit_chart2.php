<?php
if (!isset ($_POST)) {
    exit();
}
if (!isset ($_POST['key'])) {
    exit();
}
if (!isset ($_POST['plugin_www_url'])) {
    exit();
}
$bootstrap_path = $_POST['plugin_www_url'] . "bootstrap-3.3.2-dist/";
$plugin_www_url = $_POST['plugin_www_url'];
$key = $_POST['key'];

$chart_width = 790;
$chart_height = 400;

global $wpdb;
if (!isset($wpdb)) {
    $wp_path = dirname(dirname(dirname(getcwd())));
    include_once($wp_path . '/wp-config.php');
    include_once($wp_path . '/wp-load.php');
    include_once($wp_path . '/wp-includes/wp-db.php');

}

$vtime = $key;


$uploaddir = getcwd() . "/../../uploads/spri-stat/";
$uploaddir_relative = "/../../uploads/spri-stat/";

if (!file_exists($uploaddir)) {
    if (!mkdir($uploaddir, 0777, true)) {
        die('Failed to upload file ');
    }
}


$json_dir = getcwd() . "/json/";
if (!file_exists($json_dir)) {
    if (!mkdir($json_dir, 0777, true)) {
        die('Failed to json file ');
    }
}

$path_csv = $_FILES['csv_file']['name'];
$ext_cvs = pathinfo($path_csv, PATHINFO_EXTENSION);


$path_excel = $_FILES['excel_file']['name'];
$ext_excel = pathinfo($path_excel, PATHINFO_EXTENSION);


$csvTitle = str_replace("." . $ext_cvs, "", $path_csv);

$csvfile = $uploaddir . $vtime . "." . $ext_cvs;

$csvfile_relative = $uploaddir_relative . $vtime . "." . $ext_cvs;


//echo '<pre>';
if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $csvfile)) {
//    echo "csv_file 파일이 유효하고, 성공적으로 업로드 되었습니다.\n";
}

$excelfile = $uploaddir . basename($_FILES['excel_file']['name']);
$excelfile = $uploaddir . $vtime . "." . $ext_excel;
$excelfile_relative = $uploaddir_relative . $vtime . "." . $ext_excel;
if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $excelfile)) {

} else {
    $excelfile = "";
    $excelfile_relative = "";
}


$row = 1;

setlocale(LC_CTYPE, 'ko_KR.eucKR');
$table = "";

$sData = array();

if (($handle = fopen($csvfile, "r")) !== FALSE) {
    $table .= "<table border='1' >\n";
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        $tmpArray = array();
        $table .= "<tr>\n";
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = iconv("euc-kr", "utf-8", $data[$i]);
            array_push($tmpArray, $data[$i]);

            $table .= "<td>" . $data[$i] . "</td>\n";
        }

        array_push($sData, $tmpArray);
        $table .= "</tr>\n";
    }


    $newData = array();
    for ($ii = 0; $ii < count($sData); $ii++) {
        $vv = $sData[$ii];
        for ($jj = 0; $jj < count($sData[$ii]); $jj++) {
            $tmpData = $sData[$ii][$jj];
            if ($ii > 0 && $jj > 0) {
                $tmpData = floatval($tmpData);
            } else {
                $tmpData = urldecode($tmpData);
            }
            $newData[$jj][$ii] = $tmpData;
        }
    }

    fclose($handle);
    $table .= "</table>\n";
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPRI Chart Management</title>

    <!-- Bootstrap -->
    <link href="<?php echo $bootstrap_path; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $plugin_www_url; ?>css/custom.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

</head>
<body>
<?php

$chart_title = $_POST['chart_title'];
$chart_unit = $_POST['chart_unit'];
$chart_caption = $_POST['chart_caption'];
$chart_caption = str_replace("\n", "<br />", $chart_caption);  // Replace \n with <br />
$chart_type = $_POST['chart_type'];
$plugin_www_url = $_POST['plugin_www_url'];


$data = array();
$data['chart_type'] = $chart_type;
$data['chart_title'] = $chart_title;
$data['chart_unit'] = $chart_unit;
$data['chart_caption'] = $chart_caption;
$data['plugin_www_url'] = $plugin_www_url;
$data['csvfile'] = $csvfile;
$data['excelfile'] = $excelfile;
$data['table'] = $table;

$jsonData = json_encode($data);

//$json_path = "json/" . $key . ".json";
//
//$fp = fopen($json_path, 'w');
//fwrite($fp, $jsonData);
//fclose($fp);

$chart_url = $chart_type . ".php?key=" . $key;
?>
<div class="space10"></div>
<form name="frm" id="frm" action="save_edit_chart.php" method="POST">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-8">
                <?php echo $table; ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-8">
                <textarea cols="55" rows=6 id="table"><?php echo $table; ?></textarea>
            </div>
        </div>
        <div class="space10"></div>


        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div id="chartHTML">
                    <script type="text/javascript">google.load("visualization", "1", {packages: ["corechart"]});
                        google.setOnLoadCallback(drawChart);
                        function drawChart() {
                            var data = google.visualization.arrayToDataTable(<?php echo json_encode($newData);?>);
                            var options = {
                                title: '<?php echo $chart_unit;?>',
                                titlePosition: 'out',
                                legend: 'bottom',
                                width: <?php echo $chart_width; ?>,
                                height: <?php echo $chart_height; ?>,
                                chartArea: {width: "85%", height: "80%"}
                            };
                            <?php
// Bar chart
// /$$$$$$$                                      /$$                             /$$
//| $$__  $$                                    | $$                            | $$
//| $$  \ $$  /$$$$$$   /$$$$$$         /$$$$$$$| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$
//| $$$$$$$  |____  $$ /$$__  $$       /$$_____/| $$__  $$ |____  $$ /$$__  $$|_  $$_/
//| $$__  $$  /$$$$$$$| $$  \__/      | $$      | $$  \ $$  /$$$$$$$| $$  \__/  | $$
//| $$  \ $$ /$$__  $$| $$            | $$      | $$  | $$ /$$__  $$| $$        | $$ /$$
//| $$$$$$$/|  $$$$$$$| $$            |  $$$$$$$| $$  | $$|  $$$$$$$| $$        |  $$$$/
//|_______/  \_______/|__/             \_______/|__/  |__/ \_______/|__/         \___/
//
                                if ($chart_type == "BarChart") { ?>
                            options.bar = {groupWidth : "90%"};
                            new google.visualization.BarChart(document.getElementById('chart_div')).draw(data, options);
                            <?php



// Column Chart
//  /$$$$$$            /$$                                          /$$$$$$  /$$                             /$$
// /$$__  $$          | $$                                         /$$__  $$| $$                            | $$
//| $$  \__/  /$$$$$$ | $$ /$$   /$$ /$$$$$$/$$$$  /$$$$$$$       | $$  \__/| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$
//| $$       /$$__  $$| $$| $$  | $$| $$_  $$_  $$| $$__  $$      | $$      | $$__  $$ |____  $$ /$$__  $$|_  $$_/
//| $$      | $$  \ $$| $$| $$  | $$| $$ \ $$ \ $$| $$  \ $$      | $$      | $$  \ $$  /$$$$$$$| $$  \__/  | $$
//| $$    $$| $$  | $$| $$| $$  | $$| $$ | $$ | $$| $$  | $$      | $$    $$| $$  | $$ /$$__  $$| $$        | $$ /$$
//|  $$$$$$/|  $$$$$$/| $$|  $$$$$$/| $$ | $$ | $$| $$  | $$      |  $$$$$$/| $$  | $$|  $$$$$$$| $$        |  $$$$/
// \______/  \______/ |__/ \______/ |__/ |__/ |__/|__/  |__/       \______/ |__/  |__/ \_______/|__/         \___/
//
                            } else if ($chart_type == "ColumnChart") {?>
                            new google.visualization.ColumnChart(document.getElementById('chart_div')).draw(data, options);
                            <?php
// Stack Chart
//  /$$$$$$   /$$                         /$$              /$$$$$$  /$$                             /$$
// /$$__  $$ | $$                        | $$             /$$__  $$| $$                            | $$
//| $$  \__//$$$$$$    /$$$$$$   /$$$$$$$| $$   /$$      | $$  \__/| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$
//|  $$$$$$|_  $$_/   |____  $$ /$$_____/| $$  /$$/      | $$      | $$__  $$ |____  $$ /$$__  $$|_  $$_/
// \____  $$ | $$      /$$$$$$$| $$      | $$$$$$/       | $$      | $$  \ $$  /$$$$$$$| $$  \__/  | $$
// /$$  \ $$ | $$ /$$ /$$__  $$| $$      | $$_  $$       | $$    $$| $$  | $$ /$$__  $$| $$        | $$ /$$
//|  $$$$$$/ |  $$$$/|  $$$$$$$|  $$$$$$$| $$ \  $$      |  $$$$$$/| $$  | $$|  $$$$$$$| $$        |  $$$$/
// \______/   \___/   \_______/ \_______/|__/  \__/       \______/ |__/  |__/ \_______/|__/         \___/
//
                        } else if ($chart_type == "StackChart") {?>
                            options.isStacked = true;
                            new google.visualization.ColumnChart(document.getElementById('chart_div')).draw(data, options);
                            <?php



    // Line chart
    // /$$       /$$                            /$$$$$$  /$$                             /$$
    //| $$      |__/                           /$$__  $$| $$                            | $$
    //| $$       /$$ /$$$$$$$   /$$$$$$       | $$  \__/| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$
    //| $$      | $$| $$__  $$ /$$__  $$      | $$      | $$__  $$ |____  $$ /$$__  $$|_  $$_/
    //| $$      | $$| $$  \ $$| $$$$$$$$      | $$      | $$  \ $$  /$$$$$$$| $$  \__/  | $$
    //| $$      | $$| $$  | $$| $$_____/      | $$    $$| $$  | $$ /$$__  $$| $$        | $$ /$$
    //| $$$$$$$$| $$| $$  | $$|  $$$$$$$      |  $$$$$$/| $$  | $$|  $$$$$$$| $$        |  $$$$/
    //|________/|__/|__/  |__/ \_______/       \______/ |__/  |__/ \_______/|__/         \___/
    //
                            } else if ($chart_type == "LineChart") { ?>
                            new google.visualization.LineChart(document.getElementById('chart_div')).draw(data, options);
                            <?php



    // Pie chart
    // /$$$$$$$  /$$                  /$$$$$$  /$$                             /$$
    //| $$__  $$|__/                 /$$__  $$| $$                            | $$
    //| $$  \ $$ /$$  /$$$$$$       | $$  \__/| $$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$
    //| $$$$$$$/| $$ /$$__  $$      | $$      | $$__  $$ |____  $$ /$$__  $$|_  $$_/
    //| $$____/ | $$| $$$$$$$$      | $$      | $$  \ $$  /$$$$$$$| $$  \__/  | $$
    //| $$      | $$| $$_____/      | $$    $$| $$  | $$ /$$__  $$| $$        | $$ /$$
    //| $$      | $$|  $$$$$$$      |  $$$$$$/| $$  | $$|  $$$$$$$| $$        |  $$$$/
    //|__/      |__/ \_______/       \______/ |__/  |__/ \_______/|__/         \___/
    //
                            } else if ($chart_type == "PieChart") { ?>
                            options.height= <?php echo $chart_width; ?>;
                            options.legend='right';
                            new google.visualization.PieChart(document.getElementById('chart_div')).draw(data, options);
                            <?php } ?>
                        }</script>
                    <div id="chart_div"></div>
                </div>

                <div id="chart_description">
                    <p>
                        <?php echo $chart_caption; ?>
                    </p>
                </div>


                <textarea cols="100" rows=9 id="htmlArea"></textarea>

                <div class="space10"></div>

                <div class="col-xs-8 col-sm-8 col-md-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 ">
                    <button type="submit" class="btn btn-success pull-left">Save</button>
                    <button type="button" class="btn btn-danger pull-right">Cancel</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="chart_title" id="chart_title" value="<?php echo $chart_title; ?>">
        <input type="hidden" name="chart_type" id="chart_type" value="<?php echo $chart_type; ?>">

        <input type="hidden" name="csvfile_relative" id="csvfile_relative" value="<?php echo $csvfile_relative; ?>">
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

    (function ($) {
        $(document).ready(function () {
            setTimeout(function () {
                $('#htmlArea').text($('#chartHTML').html());
            }, 1000);

            $('#frm').submit(function (event) {
                $('#table_html').val($('#table').html());
                $('#chart_html').val($('#htmlArea').html());

//                event.preventDefault();
            });
        });
    })(jQuery);

</script>
</body>
</html>
