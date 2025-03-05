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
$isPopup = $isInline === 'N' ? 'Y' : 'N';

if ($isInline === 'N') {
	// preload site header !!!it`s need because there is a header in the including index page!!!
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
	$GLOBALS['APPLICATION']->RestartBuffer();

	// show ajax css, js, strings
	$GLOBALS['APPLICATION']->ShowAjaxHead();

	if($GLOBALS['APPLICATION']->GetShowIncludeAreas()){
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
	?>
	<span class="jqmClose top-close fill-theme-hover fill-use-svg-999" onclick="window.b24form = false;" title="<?=Loc::getMessage('CLOSE_BLOCK');?>">
		<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/header_icons.svg#close-14-14', '', [
			'WIDTH' => 14,
			'HEIGHT' => 14
		]);?>
	</span>
	<?
}

$shareBasketPageUrl = Bitrix\Main\Config\Option::get(TSolution::moduleID, 'SHARE_BASKET_PAGE_URL', '#'.'SITE_DIR'.'#'.'sharebasket/', SITE_ID);
$shareBasketPageUrl = str_replace('#'.'SITE_DIR'.'#', SITE_DIR, $shareBasketPageUrl).'/index.php';
$shareBasketPageUrl = preg_replace('/\/{2,}/', '/', $shareBasketPageUrl);

include __DIR__.'/..'.$shareBasketPageUrl;

if ($isInline === 'N') {
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
}
