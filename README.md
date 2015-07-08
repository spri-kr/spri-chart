# SPRI Chart Manager

## Overview

This is wordpress plugin for chart creation and management using [google chart](https://developers.google.com/chart/). Currently the plugin support csv file encoded in euc-kr.

## Install plugin

[Download](https://github.com/spri-kr/spri-chart/archive/master.zip) Repository as Zip file. Unzip file and rename `spri-chart-master` to `spri-chart`. Move `spri-chart` to your wordpress plugin directory typically `~/www/wp/wp-content/plugins/`.

Done! That's it!

## How to use

### Upload chart
![Admin menu](img/1.JPG?raw=true)

Navigate admin menu. click the plugin menu.
 
![plugin page](img/2.JPG?raw=true)

![csv file upload](img/3.JPG?raw=true)

Click `Add new chart` then upload your csv file.

![csv file upload](img/4.JPG?raw=true)

Click `Submit`(`제출` in screenshot. depends on your language) then you can see your chart, chart data and option. you can modify your data and options here. Also chart type can be changed here.

After modification, you can click `Upload`.

![chart drawn](img/5.JPG?raw=true)

And then, here is your chart ready to insert.

### Insert into post

![chart drawn](img/5.JPG?raw=true)

At top of single chart, you can see number and chart title you typed. the number is id of chart. you can insert your chart into post by shortcode

```
[nsc id=1234]
```

![chart in post](img/10.JPG?raw=true)

like this. `nsc` stand for `new spri chart`. It will be changed to another string or changed by option to user string in near future.

![chart in post](img/11.JPG?raw=true)

after publish post, you can see your chart on post. At now, insert same chart multiple times does not supported.

### Edit chart

You can edit chart even after upload.

![chart](img/5.JPG?raw=true)

On the plugin page, you can see `Edit` button on every single chart. Click that button.

![chart editor](img/6.JPG?raw=true)

This is chart edit screen . You can edit your chart from here.

Modify your chart freely.

![chart editor](img/7.JPG?raw=true)

![chart editor](img/8.JPG?raw=true)

`Draw` will redraw your chart with modified data and option. After modification, You can click `Update`. 

![chart editor](img/9.JPG?raw=true)

Edit Done!

### Delete chart

![chart editor](img/9.JPG?raw=true)

Simply click `Delete`. Dialog will appear. If you click `OK` then chart will be deleted.

## Customizing Chart
https://developers.google.com/chart/interactive/docs/customizing_charts

You can customizing chart. Here is some reference URLs for customizing. Example codes on this sections is just a snippet. If you want full information about customizing, please visit the reference URL.


### Lines
https://developers.google.com/chart/interactive/docs/lines

Chart has line can be customized.

```javascript
var options = {
  legend: 'none',
  hAxis: { maxValue: 7 },
  vAxis: { maxValue: 13 },
  series: {
    0: { lineWidth: 1 },
    1: { lineWidth: 2 },
    2: { lineWidth: 4 },
    3: { lineWidth: 8 },
    4: { lineWidth: 16 },
    5: { lineWidth: 24 }
  },
  colors: ['#e2431e', '#d3362d', '#e7711b',
           '#e49307', '#e49307', '#b9c246']
};

var options = {
  hAxis: { maxValue: 10 },
  vAxis: { maxValue: 18 },
  chartArea: { width: 380 },
  lineWidth: 4,
  series: {
    0: { lineDashStyle: [1, 1] },
    1: { lineDashStyle: [2, 2] },
    2: { lineDashStyle: [4, 4] },
    3: { lineDashStyle: [5, 1, 3] },
    4: { lineDashStyle: [4, 1] },
    5: { lineDashStyle: [10, 2] },
    6: { lineDashStyle: [14, 2, 7, 2] },
    7: { lineDashStyle: [14, 2, 2, 7] },
    8: { lineDashStyle: [2, 2, 20, 2, 20, 2] }
  },
  colors: ['#e2431e', '#f1ca3a', '#6f9654', '#1c91c0',
           '#4374e0', '#5c3292', '#572a1a', '#999999', '#1a1a1a'],
};

```

You can change:
- Color
- Thickness
- Dashed or solid line

### Points
https://developers.google.com/chart/interactive/docs/points

In line chart you can customizing point on line as you want in several shapes. 

```javascript
var options = {
  legend: 'none',
  hAxis: { minValue: 0, maxValue: 7 },
  pointSize: 30,
  series: {
        0: { pointShape: 'circle' },
        1: { pointShape: 'triangle' },
        2: { pointShape: 'square' },
        3: { pointShape: 'diamond' },
        4: { pointShape: 'star' },
        5: { pointShape: 'polygon' }
    }
};

var options = {
  legend: 'none',
  colors: ['#15A0C8'],
  pointSize: 30,
  pointShape: { type: 'triangle', rotation: 180 }
};
```

Here is example code. more examples are on reference URL.

- fill-color (Specified as a hex string.)
- shape-dent
- shape-rotation
- shape-sides
- shape-type
- stroke-color (Specified as a hex string.)
- stroke-width (Specified as a hex string.)
- size
- visible (Whether the point is visible or not.)

This list is available options for point.


### Axes
https://developers.google.com/chart/interactive/docs/customizing_axes

### Data
https://developers.google.com/chart/interactive/docs/roles#what-are-roles

## Contribution

If you want to contribute to this project, just simply sending a pull request or submit a issue.

## License

