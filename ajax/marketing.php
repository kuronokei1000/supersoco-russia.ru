<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent(
	"aspro:marketing.popup.lite", 
	".default", 
	array(
		'SHOW_FORM' => 'Y'
	),
	false, array('HIDE_ICONS' => 'Y')
);
?>
<span class="jqmClose top-close stroke-theme-hover" onclick="window.b24form = false;" title="<?=\Bitrix\Main\Localization\Loc::getMessage('CLOSE_BLOCK');?>"><?=CLite::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></span>
