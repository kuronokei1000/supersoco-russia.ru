<?
use Bitrix\Main\Localization\Loc;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$isPopup = strpos($_SERVER['SCRIPT_NAME'], '/ajax/') !== false;
$site = $request->get('site') ?? '';
$lang = $request->get('lang') ?? LANGUAGE_ID;

Loc::setCurrentLang($lang);

if ($isPopup) {
	$GLOBALS['APPLICATION']->ShowAjaxHead();

	?>
	<span class="jqmClose top-close stroke-theme-hover" onclick="window.b24form = false;" title="<?=Loc::getMessage('CLOSE_BLOCK');?>"><?=TSolution::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></span>
	<?
}

$APPLICATION->IncludeComponent(
	"aspro:wizard.solution.lite",
	'',
	array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SITE_ID" => $site,
		"IS_POPUP" => $isPopup ? "Y" : "N",
	),
	false
);
