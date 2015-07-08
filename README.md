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

## Customizing

You can customizing chart. Here is examples and reference URLs for customizing. 

### Chart
https://developers.google.com/chart/interactive/docs/customizing_charts

### Points
https://developers.google.com/chart/interactive/docs/points

### Lines
https://developers.google.com/chart/interactive/docs/lines

### Axes
https://developers.google.com/chart/interactive/docs/customizing_axes

### Data
https://developers.google.com/chart/interactive/docs/roles#what-are-roles

## Contribution

If you want to contribute to this project, just simply sending a pull request or submit a issue.

## License

