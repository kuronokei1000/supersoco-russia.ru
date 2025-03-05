<?
use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	throw new SystemException('Error include solution constants');
}

if (!Loader::includeModule(VENDOR_MODULE_ID)) {
	throw new SystemException('Error include module '.VENDOR_MODULE_ID);
}

$arParams = json_decode($request['params'], true) ?? [];
$arParams = $GLOBALS['APPLICATION']->ConvertCharsetArray($arParams, 'UTF-8', SITE_CHARSET);

$action = $request['action'] ?: '';

Loader::includeModule('iblock');

$ELEMENT_ID = isset($arParams['ELEMENT_ID']) && intval($arParams['ELEMENT_ID']) > 0 ? intval($arParams['ELEMENT_ID']) : 0;
if ($ELEMENT_ID <= 0) {
	throw new SystemException(Loc::getMessage('ERROR_ID_ELEMENT'));
}

$arElement = CIBlockElement::GetList([], ['ID' => $ELEMENT_ID], false, ['nTopCount' => 1], ['ID', 'IBLOCK_ID'])->Fetch();
if (!$arElement) {
	throw new SystemException(Loc::getMessage('ERROR_ELEMENT'));
}

$IBLOCK_ID = $arElement ? $arElement['IBLOCK_ID'] : '';
$RATE = isset($arParams['RATE']) && intval($arParams['RATE']) > 0 ? intval($arParams['RATE']) : 0;
$BLOG_URL = $arParams['BLOG_URL'] ?? '';

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

$arComponentParams = [
	'CACHE_TYPE' => 'N',
	'CACHE_TIME' => '0',
	'CACHE_GROUPS' => 'Y',
	'COMMENTS_COUNT' => '0',
	'ELEMENT_CODE' => '',
	'ELEMENT_ID' => $ELEMENT_ID,
	'XML_ID' => '',
	'IBLOCK_ID' => $IBLOCK_ID,
	'IBLOCK_TYPE' => '',
	'SHOW_DEACTIVATED' => 'N',
	'TEMPLATE_THEME' => 'blue',
	'URL_TO_COMMENT' => '',
	'AJAX_POST' => 'Y',
	'WIDTH' => '',
	'COMPONENT_TEMPLATE' => 'popup',
	'BLOG_USE' => 'Y',
	'PATH_TO_SMILE' => '/bitrix/images/blog/smile/',
	'EMAIL_NOTIFY' => 'Y',
	'SHOW_SPAM' => 'Y',
	'SHOW_RATING' => 'Y',
	'RATING_TYPE' => 'like_graphic_catalog_reviews',
	'MAX_IMAGE_SIZE' => '',
	'BLOG_URL' => $BLOG_URL,
	'REVIEW_COMMENT_REQUIRED' => 'N',
	'REAL_CUSTOMER_TEXT' => '',
	'RATE' => $RATE,
];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.comments",
	"popup",
	$arComponentParams,
	$component,
	array("HIDE_ICONS" => "Y")
);?>

<?
if ($action === 'vote') {
	$APPLICATION->RestartBuffer();

	$_REQUEST['sessid'] = $_POST['sessid'] = $request['sessid'] ?? '';
	$_REQUEST['SITE_ID'] = $_POST['SITE_ID'] = SITE_ID;
	$_REQUEST['IBLOCK_ID'] = $_POST['IBLOCK_ID'] = $IBLOCK_ID;
	$_REQUEST['ELEMENT_ID'] = $_POST['ELEMENT_ID'] = $ELEMENT_ID;
	$_REQUEST['parentId'] = $_REQUEST['edit_id'] = $_REQUEST['blog_upload_cid'] = $_POST['parentId'] = $_POST['edit_id'] = $_POST['blog_upload_cid'] = false;
	$_REQUEST['act'] = $_POST['act'] = 'add';
	$_REQUEST['post'] = $_POST['post'] = 'Y';
	$_REQUEST['comment'] = $_POST['comment'] = '<comment></comment>';
	$_REQUEST['rating'] = $_POST['rating'] = $RATE;

	$component = new \CBitrixComponent();
	if ($component->InitComponent('bitrix:catalog.comments')) {
		$component->setTemplateName('popup');
		$component->initComponentTemplate('ajax', SITE_ID);

		$template = $component->getTemplate();
		if ($template) {
			$page = $template->getFile();
			if ($page) {
				include_once $_SERVER['DOCUMENT_ROOT'].$page;
			}
		}
	}
}
?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>