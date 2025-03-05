<?
use Bitrix\Main\SystemException;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	include_once '../../../../ajax/const.php';
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
}

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	throw new SystemException('Error include solution constants');
}
?>
<?$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.lite", 
	"front_vk", 
	array(
		"COMPONENT_TEMPLATE" => "front_vk",
		"API_TOKEN_VK" => "FROM_THEME",
		"GROUP_ID_VK" => "FROM_THEME",
		"VK_TITLE_BLOCK" => "FROM_THEME",
		"VK_TITLE_ALL_BLOCK" => "FROM_THEME",
		"VK_TEXT_LENGTH" => "FROM_THEME",
		"BORDERED" => "FROM_THEME",
		"SHOW_TITLE" => "FROM_THEME",
		"LINE_ELEMENT_COUNT" => "FROM_THEME",
		"MOBILE_SCROLLED" => true,
		"MAXWIDTH_WRAP" => true,
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_GROUPS" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CACHE_FILTER" => "N"
	),
	false
);?>