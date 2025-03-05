<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException;

TSolution\Extensions::init('contacts');

$amount = json_decode($request['amount'], true) ?? [];
$amount = $GLOBALS['APPLICATION']->ConvertCharsetArray($amount, 'UTF-8', SITE_CHARSET);

if ($amount["ID"] <= 0) {
    throw new SystemException(Loc::getMessage('ERROR_ID_ELEMENT'));
}
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:catalog.store.amount",
    "popup",
    array(
        "PER_PAGE" => "10",
        "ELEMENT_ID" => $amount["ID"],
        "MAIN_TITLE"  =>  $amount['PARAMS']["TITLE"],
        "STORE_PATH"  =>  $amount['PARAMS']["PATH"],
        "CACHE_GROUPS" => "Y",
        "CACHE_TYPE" => "N",
        "SHOW_EMPTY_STORE" => $amount['PARAMS']['SHOW_EMPTY'],
        "SHOW_GENERAL_STORE_INFORMATION" => $amount['PARAMS']['SHOW_GENERAL'],
        "USE_MIN_AMOUNT" => $amount['PARAMS']["USE_MIN"],
        "MIN_AMOUNT" => $amount['PARAMS']["MIN"],
        "FIELDS" => $amount['PARAMS']['FIELDS'] ?: [],
        "USER_FIELDS" => $amount['PARAMS']['USER_FIELDS'] ?: [],
        "USE_STORE_PHONE" => "Y",
        "STORES" => $amount['STORES'],
        "STORES_FILTER" => $amount['PARAMS']['FILTER'],
        "STORES_FILTER_ORDER" => $amount['PARAMS']['ORDER'] ?: [],
    ),
    false
);?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>