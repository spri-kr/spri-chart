READ ME

spri-stat.zip 을 풀면 spri-stat 폴더 밑에 여러 파일들이 생성됩니다.

해당 spri-stat 폴더를 plugin 밑으로 넣어주면 됩니다.


폴더의 내용입니다.

./bootstrap-3.3.2-dist
	:  최신 css framework 중 Bootstrap 을 사용하기 위해 저장
./css
	: 필요한 css 를 따로 추가 했습니다.
./images
	: 몇개의 아이콘 저장


./ 밑의 파일들입니다..

	spri-stat.php : admin 에서 전체 리스트가 보여지는 페이지를 담당

	view.php : view  메뉴 클릭시 보여지는 페이지 담당

	iframe_url.php : iframe 으로 보여지게 하기 위한 페이지

	기타
	Chart Building 담당 페이지 순서
		new_chart.php --> new_chart2.php -> save_chart.php
	Chart Edition 담당 페이지 순서
		edit_chart.php --> edit_chart2.php -> save_edit_chart.php

	* 필요한 table 은 해당 플러그인이 활성화 되고 어드민 페이지가 접속이 될때에 자동으로 생성됩니다.
	사용된 table 은 wp_spri_chart_list 입니다.


사용된 라이브러리
	https://developers.google.com/chart/interactive/docs/quick_start


