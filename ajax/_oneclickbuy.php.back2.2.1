<?
use	Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	throw new SystemException('Error include solution constants');
}

if (!Loader::includeModule(VENDOR_MODULE_ID)) {
	throw new SystemException('Error include module '.VENDOR_MODULE_ID);
}

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$post = $request->getPostList();

$productId = isset($request['ELEMENT_ID']) && intval($request['ELEMENT_ID']) > 0 ? intval($request['ELEMENT_ID']) : 0;
$iblockId = isset($request['IBLOCK_ID']) && intval($request['IBLOCK_ID']) > 0 ? intval($request['IBLOCK_ID']) : 0;
$quantity = isset($request['ELEMENT_QUANTITY']) && floatval($request['ELEMENT_QUANTITY']) > 0 ? floatval($request['ELEMENT_QUANTITY']) : 1;
$offerProps = isset($request['OFFER_PROPS']) && $request['OFFER_PROPS'] ? $request['OFFER_PROPS'] : [];

$GLOBALS['APPLICATION']->ShowAjaxHead();
$areaIndex = 1000;

if ($GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
	$GLOBALS['APPLICATION']->editArea = new CEditArea();
	$GLOBALS['APPLICATION']->editArea->includeAreaIndex = array(0 => $areaIndex);

	echo "<style>.bx-core-adm-dialog, div.bx-component-opener, .bx-core-popup-menu{z-index:3001 !important;}#popup_iframe_wrapper{z-index:2400 !important;}</style>";
}
?>
<span class="jqmClose top-close fill-theme-hover fill-use-svg-999" onclick="window.b24form = false;" title="<?=Loc::getMessage('CLOSE_BLOCK'); ?>">
	<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/header_icons.svg#close-14-14', '', [
		'WIDTH' => 14,
		'HEIGHT' => 14
	]);?>
</span>
<?
include __DIR__ . '/../include/comp_oneclickbuy.php';

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');