<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('aspro.lite');
?>
<?$APPLICATION->IncludeComponent(
	"aspro:basket.lite", 
	"header", 
	array(
		"COMPONENT_TEMPLATE" => "header",
		"SHOW_404" => "N",
		"HIDE_ON_CART_PAGE" => "Y",
	),
	false, array("HIDE_ICONS" => "Y")
);?>