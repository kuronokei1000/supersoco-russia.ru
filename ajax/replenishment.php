<?
$arParams = json_decode($request['params'], true) ?? [];
$arParams = $GLOBALS['APPLICATION']->ConvertCharsetArray($arParams, 'UTF-8', SITE_CHARSET);

$component = false;
if (
	strlen($arParams['PARENT_COMPONENT'] ?? '') &&
	strlen($arParams['PARENT_COMPONENT_TEMPLATE'] ?? '') &&
	strlen($arParams['PARENT_COMPONENT_PAGE'] ?? '')
) {
	$component = new \CBitrixComponent();
	if ($component->InitComponent($arParams['PARENT_COMPONENT'])) {
		$component->setTemplateName($arParams['PARENT_COMPONENT_TEMPLATE']);
		$component->initComponentTemplate($arParams['PARENT_COMPONENT_PAGE'], SITE_ID);
	}
}
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.account.pay",
	"main",
	Array(
		"COMPONENT_TEMPLATE" => "main",
		"REFRESHED_COMPONENT_MODE" => "Y",
		"ELIMINATED_PAY_SYSTEMS" => $arParams["ELIMINATED_PAY_SYSTEMS"],
		"PATH_TO_BASKET" => $arParams["PATH_TO_BASKET"],
		"PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
		"PERSON_TYPE" => $arParams["PERSON_TYPE"],
		"REDIRECT_TO_CURRENT_PAGE" => $arParams["REDIRECT_TO_CURRENT_PAGE"],
		"SELL_AMOUNT" => $arParams["SELL_TOTAL"],
		"SELL_CURRENCY" => $arParams["SELL_CURRENCY"],
		"SELL_SHOW_FIXED_VALUES" => $arParams["SELL_SHOW_FIXED_VALUES"],
		"SELL_SHOW_RESULT_SUM" =>  $arParams["SELL_SHOW_RESULT_SUM"],
		"SELL_TOTAL" => $arParams["SELL_TOTAL"],
		"SELL_USER_INPUT" => $arParams["SELL_USER_INPUT"],
		"SELL_VALUES_FROM_VAR" => $arParams["SELL_VALUES_FROM_VAR"],
		"SELL_VAR_PRICE_VALUE" => $arParams["SELL_VAR_PRICE_VALUE"],
		"SET_TITLE" => $arParams["SET_TITLE"],
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>