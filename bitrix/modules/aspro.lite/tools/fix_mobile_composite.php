<?
$fileName = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/aspro.lite/lib/vendor/mobiledetect.php';
if (file_exists($fileName)) {
	require_once($fileName);
	$GLOBALS['DETECT_LIB'] = new Detection\MobileDetect;
	
	if ($GLOBALS['DETECT_LIB']->isMobile()) {
		define('TEMPLATE_TYPE', 'mobile');
		$_GET['is_aspro_mobile'] = 'Y';
	} else {
		define('TEMPLATE_TYPE', 'desktop');
	}
}
?>