<?
use Bitrix\Main\Loader;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	die();
}

if(
	!Loader::includeModule('sale') ||
	!Loader::includeModule('catalog') ||
	!Loader::includeModule('iblock') ||
	!Loader::includeModule(VENDOR_MODULE_ID)
) {
	die();
}

$arBasketItems = Aspro\Lite\Itemaction\Basket::getItems();
$arCompareItems = Aspro\Lite\Itemaction\Compare::getItems();
$arFavoriteItems = Aspro\Lite\Itemaction\Favorite::getItems();
$arSubscribeItems = Aspro\Lite\Itemaction\Subscribe::getItems();
?>
<script type="text/javascript">
var arAsproCounters = <?=CUtil::PhpToJSObject([
	'BASKET' => [
		'ITEMS' => $arBasketItems['BASKET'],
		'COUNT' => count($arBasketItems['BASKET']),
		'TITLE' => Aspro\Lite\Itemaction\Basket::getTitle(),
	],
	'DELAY' => [
		'ITEMS' => $arBasketItems['DELAY'],
		'COUNT' => count($arBasketItems['DELAY']),
	],
	'NOT_AVAILABLE' => [
		'ITEMS' => $arBasketItems['NOT_AVAILABLE'],
		'COUNT' => count($arBasketItems['NOT_AVAILABLE']),
	],
	'COMPARE' => [
		'ITEMS' => $arCompareItems,
		'COUNT' => count($arCompareItems),
		'TITLE' => Aspro\Lite\Itemaction\Compare::getTitle(),
	],
	'FAVORITE' => [
		'ITEMS' => $arFavoriteItems,
		'COUNT' => count($arFavoriteItems),
		'TITLE' => Aspro\Lite\Itemaction\Favorite::getTitle(),
	],
	'SUBSCRIBE' => [
		'ITEMS' => $arSubscribeItems,
		'COUNT' => count($arSubscribeItems),
		'TITLE' => Aspro\Lite\Itemaction\Subscribe::getTitle(),
	],
], false, true);?>;

if (typeof window.JItemAction === 'function') {
	JItemActionBasket.markBadges();
	JItemActionBasket.markItems();

	JItemActionCompare.markBadges();
	JItemActionCompare.markItems();

	JItemActionFavorite.markBadges();
	JItemActionFavorite.markItems();

	JItemActionSubscribe.markItems();
}

if(typeof obLitePredictions === 'object'){
	obLitePredictions.updateAll();
}
</script>