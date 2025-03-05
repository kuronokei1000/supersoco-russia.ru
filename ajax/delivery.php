<?
use Bitrix\Main\Localization\Loc;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	die('Error include solution constants');
}

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$post = $request->getPostList();

$isInline = strpos($_SERVER['SCRIPT_NAME'], '/ajax/') === false ? 'Y' : 'N';
$isPreview = isset($post['is_preview']) && $post['is_preview'] === 'Y' ? 'Y' : 'N';
$isPopup = $isInline === 'N' && $isPreview === 'N' ? 'Y' : 'N';

$productId = isset($request['product_id']) && intval($request['product_id']) > 0 ? intval($request['product_id']) : false;
$quantity = isset($request['quantity']) && floatval($request['quantity']) > 0 ? floatval($request['quantity']) : 0;

if ($isInline === 'N') {
	if ($isPopup === 'Y') {
		$GLOBALS['APPLICATION']->ShowAjaxHead();
	}

	if ($GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
		$areaIndex = isset($post['index']) && intval($post['index']) > 0 ? intval($post['index']) : 1000;
		$GLOBALS['APPLICATION']->editArea = new CEditArea();
		$GLOBALS['APPLICATION']->editArea->includeAreaIndex = array(0 => $areaIndex);

		// z-index for component`s menu opener
		?>
		<script>
		if (typeof BX.ZIndexManager !== 'undefined') {
			BX.ZIndexManager.getStack(document.body).setBaseIndex(3000);
		}
		</script>
		<?
	}
}
?>
<?if($isPopup === 'Y'):?>
	<span class="jqmClose top-close fill-theme-hover fill-use-svg-999" onclick="window.b24form = false;" title="<?=Loc::getMessage('CLOSE_BLOCK'); ?>">
		<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/header_icons.svg#close-14-14', '', [
			'WIDTH' => 14,
			'HEIGHT' => 14
		]);?>
	</span>
<?endif;?>
<?
include __DIR__ . '/../include/comp_catalog_delivery.php';

if ($isInline === 'N') {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
}
