================================================================================
  그누보드 루트에 전체 복붙하는 방법 (OnlyCebu / rb.basic 백업)
================================================================================

【할 일】
  이 폴더(paste_into_gnuboard_root) **안에 있는 모든 파일과 폴더**를 선택해서,
  그누보드 설치 **루트 디렉터리**에 붙여넣습니다.
  (common.php, bbs/, extend/, theme/ 등이 한곳에 모여 있는 그 폴더)

【포함되는 것】
  • common.php
  • _debugbar-extend.php
  • extend/          (디버그바 스텁 등)
  • theme/rb.basic/  (리빌더 테마 전체 — OnlyCebu 반영본)

【이 폴더에 없는 것 (복붙 대상 아님)】
  • 상위 폴더의 documentation/  → 설명용만. FTP로 웹 루트에 올리지 마세요.

【반드시 읽을 것】
  • common.php 는 DB·환경마다 다릅니다. 대상 사이트에서 이미 수정했다면
    통째 덮어쓰기 전에 백업 후 diff 로 비교하는 것을 권장합니다.
  • 게시판 스킨(information_bbs, information_mo 등)은 관리자 > 게시판관리에서
    스킨 이름을 지정해야 합니다.

【상세 목록·변경 설명】
  ../documentation/ONLYCEBU_REBUILDER_CHANGES.md
  ../documentation/MANIFEST_paths_878c736_to_HEAD.txt

================================================================================
