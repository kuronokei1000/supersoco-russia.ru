<?
$context=\Bitrix\Main\Context::getCurrent();
$request=$context->getRequest();
$ajaxBlock = $request->getPost('BLOCK');
$bAjax = $ajaxBlock && $request->getPost('IS_AJAX') == 'Y';

global $arTheme, $arRegion;

if($bAjax){
	CModule::includeModule('aspro.lite');

	$arTheme = $APPLICATION->IncludeComponent("aspro:theme.lite", "", array(), false);

	IncludeTemplateLangFile(SITE_TEMPLATE_PATH.'/header.php');

	if($arTheme['USE_REGIONALITY']['VALUE'] == 'Y'){
		if(!$arRegion){
			$arRegion = CLiteRegionality::getCurrentRegion();
		}
	}
	else{
		$arRegion = array();
	}
}

if($arRegion){
	$bPhone = ($arRegion['PHONES'] ? true : false);
}
else{
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
}

$currentFooterOptions = $arTheme['FOOTER_TYPE']['LIST'][ $arTheme['FOOTER_TYPE']['VALUE'] ];

$bShowSubscribe = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_SUBSCRIBE']['VALUE'] == 'Y';
$bShowPhone = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_PHONE']['VALUE'] == 'Y';
$bShowCallback = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_PHONE']['ADDITIONAL_OPTIONS']['FOOTER_TOGGLE_CALLBACK']['VALUE'] == 'Y';
$bShowAddress = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_ADDRESS']['VALUE'] == 'Y';
$bShowSocial = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_SOCIAL']['VALUE'] == 'Y';
$bShowLang = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_LANG']['VALUE'] == 'Y';
$bShowEmail = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_EMAIL']['VALUE'] == 'Y';
$bShowPaySystems = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_PAY_SYSTEMS']['VALUE'] == 'Y';
$bShowDeveloper = $currentFooterOptions['TOGGLE_OPTIONS']['OPTIONS']['FOOTER_TOGGLE_DEVELOPER']['VALUE'] == 'Y';

$bCallback = isset($arTheme["SHOW_CALLBACK"]["VALUE"]) ? $arTheme["SHOW_CALLBACK"]["VALUE"] === "Y" : true;

$footerColor = strtolower($currentFooterOptions['ADDITIONAL_OPTIONS']['FOOTER_COLOR']['VALUE']);

$siteSelectorName = $arTheme['SITE_SELECTOR_NAME']['VALUE'];
?>