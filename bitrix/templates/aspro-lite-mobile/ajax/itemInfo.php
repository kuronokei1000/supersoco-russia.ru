<?
use \Bitrix\Main\Loader,
	\Bitrix\Main\Web\Json;

include_once('const.php');

if(isset($_REQUEST['site_id'])) {
	$SITE_ID = htmlspecialchars($_REQUEST['site_id']);
	define('SITE_ID', $SITE_ID);
}
if(isset($_REQUEST['site_dir'])) {
	$SITE_DIR = htmlspecialchars($_REQUEST['site_dir']);
	define('SITE_DIR', $SITE_DIR);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

$arResult = [
	'STATUS' => 'ERROR'
];

if ($request->get('sessid') === bitrix_sessid()) {
	// need for solution class and variables
	if (!include_once('../vendor/php/solution.php')) {
		$arResult['ERROR'] = 'No vendor';
		echo Json::encode($arResult);
		die();
	}

	$arBasketItems = TSolution\Itemaction\Basket::getItems();
	$arCompareItems = TSolution\Itemaction\Compare::getItems();
	$arFavoriteItems = TSolution\Itemaction\Favorite::getItems();
	$arSubscribeItems = TSolution\Itemaction\Subscribe::getItems();

	$arResult['STATUS'] = 'OK';
	$arResult['INFO'] = [
		'BASKET' => [
			'ITEMS' => $arBasketItems['BASKET'],
			'COUNT' => count($arBasketItems['BASKET']),
			'TITLE' => TSolution\Itemaction\Basket::getTitle(),
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
			'TITLE' => TSolution\Itemaction\Compare::getTitle(),
		],
		'FAVORITE' => [
			'ITEMS' => $arFavoriteItems,
			'COUNT' => count($arFavoriteItems),
			'TITLE' => TSolution\Itemaction\Favorite::getTitle(),
		],
		'SUBSCRIBE' => [
			'ITEMS' => $arSubscribeItems,
			'COUNT' => count($arSubscribeItems),
			'TITLE' => TSolution\Itemaction\Subscribe::getTitle(),
		],
	];
	echo Json::encode($arResult);
	die();
}
$arResult['ERROR'] = 'No token';
echo Json::encode($arResult);
