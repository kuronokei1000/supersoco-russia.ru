<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результат оплаты");

\Bitrix\Main\Loader::includeModule('aspro.lite');

if (!CLite::isSaleMode()) {
	$url = CLite::GetFrontParametrValue('ORDER_PAGE_URL', SITE_ID, SITE_DIR);
	LocalRedirect($url);
}
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment.receive",
	"",
	Array(
		"PAY_SYSTEM_ID" => "",
		"PERSON_TYPE_ID" => ""
	),
false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>