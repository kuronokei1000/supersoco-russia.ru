<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

global $APPLICATION;

$APPLICATION->IncludeComponent(
	"bitrix:subscribe.edit",
	"popup",
	array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"ALLOW_ANONYMOUS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"PAGE" => "personal/subscribe/",
		"SET_TITLE" => "N",
		"SHOW_AUTH_LINKS" => "N",
		"SHOW_HIDDEN" => "N",
		"COMPONENT_TEMPLATE" => "popup"
	),
	false
);
