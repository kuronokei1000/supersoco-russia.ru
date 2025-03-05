<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("Оплата заказа");

\Bitrix\Main\Loader::includeModule('aspro.lite');

if (!CLite::isSaleMode()) {
	$url = CLite::GetFrontParametrValue('ORDER_PAGE_URL', SITE_ID, SITE_DIR);
	LocalRedirect($url);
}
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment",
	"",
	Array(
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>