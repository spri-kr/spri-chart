# SPRI Chart Manager

## 개요

[google chart](https://developers.google.com/chart/)를 이용한 차트 관리 워드프레스 플러그인입니다. 현재 플러그인은 euc-kr로 인코딩된 csv만 지원하고 있습니다.

## 플러그인 설치

이 저장소를 zip 파일로 [다운](https://github.com/spri-kr/spri-chart/archive/master.zip)받습니다. 압축을 풀고 `spri-chart-master`폴더를 `spri-chart`로 이름을 바꾸세요. `spri-chart`를 워드프레스 플러그인 폴더로 옮기세요. 보통은 `~/www/wp/wp-content/plugins/`이런 곳입니다.

## 사용법

### 차트 업로드
![Admin menu](img/1.JPG?raw=true)

관리메뉴로 이동해서 플러그인 페이지를 클릭합니다.
 
![plugin page](img/2.JPG?raw=true)

![csv file upload](img/3.JPG?raw=true)

 `Add new chart` 클릭하고 csv 파일을 업로드합니다.

![csv file upload](img/4.JPG?raw=true)

 `제출`을 클릭합니다. csv에 따른 차트가 그려집니다. 차트 데이터와 옵션 및 타입을 여기서 바꿀 수 있습니다.

수정이 끝나면 `Upload`를 클릭합니다..

![chart drawn](img/5.JPG?raw=true)

이제 차트를 포스트나 페이지에 삽입할 준비가 되었습니다.

### 포스트에 차트 삽입하기

![chart drawn](img/5.JPG?raw=true)

개별 차트 상단에서 차트 번호와 제목을 볼 수 있습니다. 번호는 차트 id입니다. 이 번호를 이용한 숏코드로 포스트에 차트를 삽입할 수 있습니다.


```
[nsc id=1234]
```

![chart in post](img/10.JPG?raw=true)

위와같이 숏코드를 사용할 수 있습니다. `nsc` 는 `new spri chart`의 약자입니다.

![chart in post](img/11.JPG?raw=true)

포스트를 발행하고 난 뒤, 포스트에 차트가 삽입된것을 볼 수 있습니다. 현재는 같은 차트를 여러번 삽입하는 기능은 없습니다.

### 차트 수정

차트를 업로드 한 후에도 차트를 수정할 수 있습니다.

![chart](img/5.JPG?raw=true)

플러그인 페이지의 개별 차트 항목에서 `Edit`버튼을 볼 수 있습니다. 클릭합니다.

![chart editor](img/6.JPG?raw=true)

이 화면이 차트 수정 화면입니다. 여기에서 차트를 변경할 수 있습니다.  
자유롭게 수정하면 됩니다.

![chart editor](img/7.JPG?raw=true)

![chart editor](img/8.JPG?raw=true)

`Draw` 버튼을 누르면 변경한 데이터와 옵션으로 차트를 다시 그리게됩니다. 수정이 끝나면 `Update`를 클릭합니다.. 

![chart editor](img/9.JPG?raw=true)

수정이 끝났습니다.

### 차트 삭제

![chart editor](img/9.JPG?raw=true)

`Delete` 를 클릭합니다. 대화상자가 나타나면, `OK`를 클릭합니다. 그러면 차트가 삭제됩니다.

## 차트 커스터마이징
https://developers.google.com/chart/interactive/docs/customizing_charts

차트 커스터마이징은 상단의 레퍼런스 URL을 참조해서 할 수 있습니다. 이 부분에 있는 코드는 전체가 아닌 일부분입니다. 커스터마이징에 대한 전체 정보를 보고 싶다면 상단의 레퍼런스 URL을 방문하세요.


### Lines
https://developers.google.com/chart/interactive/docs/lines

차트의 선은 커스터마이징이 가능합니다.

다음의 요소를 바꿀 수 있습니다.

- 색
- 두께
- 선의 종류

여기 있는 예제 코드를 참조하면 됩니다. 레퍼런스 URL에 더 많은 예제가 있습니다.

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



### Points
https://developers.google.com/chart/interactive/docs/points

라인 그래프에서 데이터를 나타내는 포인트를 커스터마이징 할 수 있습니다.

다음의 요소를 바꿀 수 있습니다:

- 채움 색 (Specified as a hex string.)
- 도형 들어감 정도
- 도형 회전
- 도형 면 갯수
- 도형 타입
- 스트로크 컬러 (Specified as a hex string.)
- 스트로크 폭
- 크기
- 가시성 (Whether the point is visible or not.)

여기 있는 예제 코드를 참조하면 됩니다. 레퍼런스 URL에 더 많은 예제가 있습니다.

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

### Axes
https://developers.google.com/chart/interactive/docs/customizing_axes

Area Chart, Bar Chart, Candlestick Chart, Column Chart, Combo Chart, Line Chart, Stepped Area Chart 그리고 Scatter Chart와 같이 축을 가진 차트의 축을 커스터마이징 할 수 있습니다.

다음과 같은 요소를 바꿀 수 있습니다:

- Discrete vs Continuous
- Direction
- Label positioning and style
- Axis title text and style
- complete list of axis configuration options on the hAxis and vAxis options in the documentation of specific chart.

축 옵션을 어떻게 설정하는지는 레퍼런스 URL을 방문해서 확인하세요.

### Data
https://developers.google.com/chart/interactive/docs/roles#what-are-roles

개별 데이터를 커스터마이징 할 수 있습니다.

다음 요소를 바꿀 수 있습니다.:

- annotation
- annotationText
- certainty
- emphasis
- interval
- scope
- style
- tooltip

위의 요소를 데이터의 role에 지정해서 다음처럼 사용할 수 있습니다:
```javascript
var data = google.visualization.arrayToDataTable([
       ['Employee Name', 'Salary'],
       ['Mike', {v:22500, f:'22,500'}], // Format as "22,500".
       ['Bob', 35000],
       ['Alice', 44000],
       ['Frank', 27000],
       ['Floyd', 92000],
       ['Fritz', 18500]
      ],
      false); // 'false' means that the first row contains labels, not data.
      
var data = google.visualization.arrayToDataTable([
       [ {label: 'Year', id: 'year'},
         {label: 'Sales', id: 'Sales', type: 'number'}, // Use object notation to explicitly specify the data type.
         {label: 'Expenses', id: 'Expenses', type: 'number'} ],
       ['2014', 1000, 400],
       ['2015', 1170, 460],
       ['2016', 660, 1120],
       ['2017', 1030, 540]]);
       

var data = google.visualization.arrayToDataTable([
	[
		"구분",
		"시장규모",
		{
			"role": "style"
		}
	],
	[
		"평판TV",
		986,
		"opacity: 1; color:#000;"
	],
	[
		"LCD패널",
		751,
		"#FF9900"
	],
	[
		"휴대폰",
		3988,
		"#FF9900"
	],
	[
		"반도체",
		3545,
		"#FF9900"
	],
	[
		"SW",
		10671,
		"#DC3912"
	]
]
```

자세한 설명은 레퍼런스 URL을 참고하세요

## 프로젝트 구조
```
├─img                       readme.md 파일을 작성하는데 사용된 스크린샷. 플러그인에서 사용되지 않음 
└─src                       소스 파일
    ├─ace                   웹 소스 에디터
    ├─bootstrap-3.3.2-dist  부트스트랩
    ├─css                   스타일 시트
    └─js                    자바스크립트
```

## 기여

이 프로젝트에 참여하고 싶은 분은 풀 리퀘스트를 보내거나, 이슈를 등록해주세요.

