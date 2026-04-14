<?php
/**
 * 그누보드 Rebuilder / 일부 호스팅에서 common.php 이전에 로드될 수 있는 훅 파일입니다.
 * 이 파일에서 공백·BOM·HTML·바이너리가 한 바이트라도 출력되면
 * "Cannot modify header information - headers already sent" 가 발생합니다.
 *
 * Mac에서 폴더 병합 시 ._debugbar-extend.php(메타데이터)가 섞이거나,
 * 본 파일이 손상된 경우 동일 증상이 납니다. 반드시 UTF-8 BOM 없이 저장하세요.
 */
// 의도적으로 출력 없음 (디버그바는 extend/debugbar.extend.php + G5_DEBUG 설정 사용)
