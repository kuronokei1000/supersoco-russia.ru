<?
$context = \Bitrix\Main\Context::getCurrent();
$request = $context->getRequest();
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
else{
	$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
}

if($arRegion){
	$bPhone = ($arRegion['PHONES'] ? true : false);
}
else{
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
}

$bCallback = isset($arTheme["SHOW_CALLBACK"]["VALUE"]) ? $arTheme["SHOW_CALLBACK"]["VALUE"] === "Y" : true;
$bOrder = $arTheme['ORDER_VIEW']['VALUE'] === 'Y';
$bCabinet = $arTheme["CABINET"]["VALUE"] === 'Y';
$bCompare = $arTheme["CATALOG_COMPARE"]["VALUE"] === 'Y';
$bFavorite = $arTheme["SHOW_FAVORITE"]["VALUE"] === 'Y';
    
$currentHeaderOptions = $arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ];
$currentHeaderFixedOptions = $arTheme['TOP_MENU_FIXED']['DEPENDENT_PARAMS']['HEADER_FIXED']['LIST'][ $arTheme['TOP_MENU_FIXED']['DEPENDENT_PARAMS']['HEADER_FIXED']['VALUE'] ];
$currentMobileHeaderOptions = $arTheme['HEADER_MOBILE']['LIST'][$arTheme['HEADER_MOBILE']['VALUE']];
$currentMobileMenuOptions = $arTheme['HEADER_MOBILE_MENU']['LIST'][$arTheme['HEADER_MOBILE_MENU']['VALUE']];

$bCatalogInBtn = $currentHeaderOptions['ADDITIONAL_OPTIONS']['CATALOG_IN_BTN']['VALUE'] == 'Y';
$bTopSections = $currentHeaderOptions['ADDITIONAL_OPTIONS']['SHOW_TOP_SECTIONS']['VALUE'] == 'Y';
$bNarrowHeader = $currentHeaderOptions['ADDITIONAL_OPTIONS']['HEADER_NARROW']['VALUE'] == 'Y';
$bCenteredHeader = $currentHeaderOptions['ADDITIONAL_OPTIONS']['LOGO_CENTERED']['VALUE'] == 'Y';

$bShowMegaMenu = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_MEGA_MENU']['VALUE'] == 'Y';
$bRightMegaMenu = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_MEGA_MENU']['ADDITIONAL_OPTIONS']['HEADER_TOGGLE_MEGA_MENU_POSITION']['VALUE'] == 'Y';
$bShowSlogan = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_SLOGAN']['VALUE'] == 'Y';
$bShowPhone = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_PHONE']['VALUE'] == 'Y';
$bShowCallback = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_PHONE']['ADDITIONAL_OPTIONS']['HEADER_TOGGLE_CALLBACK']['VALUE'] == 'Y';
$bShowSearch = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_SEARCH']['VALUE'] == 'Y';
$bShowAddress = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_ADDRESS']['VALUE'] == 'Y';
$bShowSocial = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_SOCIAL']['VALUE'] == 'Y';
$bShowLang = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_LANG']['VALUE'] == 'Y';
$bShowCurrency = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_CURRENCY']['VALUE'] == 'Y';
$bShowThemeSelector = $currentHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_TOGGLE_THEME_SELECTOR']['VALUE'] == 'Y';

$bShowMegaMenuFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_MEGA_MENU']['VALUE'] == 'Y';
$bRightMegaMenuFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_MEGA_MENU']['ADDITIONAL_OPTIONS']['HEADER_FIXED_TOGGLE_MEGA_MENU_POSITION']['VALUE'] == 'Y';
$bShowSloganFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_SLOGAN']['VALUE'] == 'Y';
$bShowPhoneFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_PHONE']['VALUE'] == 'Y';
$bShowCallbackFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_PHONE']['ADDITIONAL_OPTIONS']['HEADER_FIXED_TOGGLE_CALLBACK']['VALUE'] == 'Y';
$bShowSearchFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_SEARCH']['VALUE'] == 'Y';
$bShowAddressFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_ADDRESS']['VALUE'] == 'Y';
$bShowSocialFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_SOCIAL']['VALUE'] == 'Y';
$bShowLangFixed = $currentHeaderFixedOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_FIXED_TOGGLE_LANG']['VALUE'] == 'Y';

$colorMobileHeader = 'white';// strtolower($currentMobileHeaderOptions['ADDITIONAL_OPTIONS']['HEADER_MOBILE_COLOR']['VALUE']);
if ($bWhiteLogoMobileHeader = ($colorMobileHeader === 'colored' || $colorMobileHeader === 'dark')) {
	$APPLICATION->GetPageProperty('HEADER_MOBILE_LOGO', 'light');
}

$bShowBurgerMobileHeader = $currentMobileHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_MOBILE_TOGGLE_BURGER']['VALUE'] == 'Y';
//$bShowRightBurgerMobileHeader = $currentMobileHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_MOBILE_TOGGLE_BURGER']['ADDITIONAL_OPTIONS']['HEADER_MOBILE_TOGGLE_BURGER_POSITION']['VALUE'] == 'Y';
$bShowCartMobileHeader = $arTheme['ORDER_VIEW']['VALUE'] == 'Y';
$bShowPhoneMobileHeader = $currentMobileHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_MOBILE_TOGGLE_PHONE']['VALUE'] == 'Y';
$bShowCallbackMobileHeader = $currentMobileHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_MOBILE_TOGGLE_PHONE']['ADDITIONAL_OPTIONS']['HEADER_MOBILE_TOGGLE_CALLBACK']['VALUE'] == 'Y';
$bShowSearchMobileHeader = $currentMobileHeaderOptions['TOGGLE_OPTIONS']['OPTIONS']['HEADER_MOBILE_TOGGLE_SEARCH']['VALUE'] == 'Y';

$bShowCartMobileMenu = $bShowCartMobileHeader;
$bShowPhoneMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_PHONE']['VALUE'] == 'Y';
$bShowCallbackMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_PHONE']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_CALLBACK']['VALUE'] == 'Y';
$bShowEmailMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_EMAIL']['VALUE'] == 'Y';
$bShowAddressMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_ADDRESS']['VALUE'] == 'Y';
$bShowScheduleMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_SCHEDULE']['VALUE'] == 'Y';
$bShowSocialMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_SOCIAL']['VALUE'] == 'Y';
$bShowLangMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_LANG']['VALUE'] == 'Y';
$bShowLangUpMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_LANG']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_LANG_UP']['VALUE'] == 'Y';
$bShowRegionUpMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_REGION']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_REGION_UP']['VALUE'] == 'Y';
$bShowCabinetUpMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_PERSONAL']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_PERSONAL_UP']['VALUE'] == 'Y';
$bShowCompareUpMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_COMPARE']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_COMPARE_UP']['VALUE'] == 'Y';
$bShowCartUpMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_CART']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_CART_UP']['VALUE'] == 'Y';
$bShowFavoriteUpMobileMenu = $currentMobileMenuOptions['TOGGLE_OPTIONS']['OPTIONS']['MOBILE_MENU_TOGGLE_FAVORITE']['ADDITIONAL_OPTIONS']['MOBILE_MENU_TOGGLE_FAVORITE_UP']['VALUE'] == 'Y';

$whiteBreadcrumbs = $arTheme['PAGE_TITLE']['VALUE'] === '1' || $arTheme['PAGE_TITLE']['VALUE'] === '2';

$siteSelectorName = $arTheme['SITE_SELECTOR_NAME']['VALUE'];

?>