<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
	\Bitrix\Main\Loader::includeModule("iblock");
	global $NextSectionID, $APPLICATION;
	$cnt = count($arResult);
	$lastindex = $cnt - 1;
	$visibleMobile = 0;
	if(\Bitrix\Main\Loader::includeModule(VENDOR_MODULE_ID))
	{
		global $arTheme;
		$bMobileBreadcrumbs = ($arTheme["MOBILE_CATALOG_BREADCRUMBS"]["VALUE"] == "Y" && $NextSectionID);
	}
	if ($bMobileBreadcrumbs) {
		$visibleMobile = $lastindex - 1;
	}
	for($index = 0; $index < $cnt; ++$index){
		$arSubSections = array();
		$bShowMobileArrow = false;
		$arItem = $arResult[$index];
		$title = htmlspecialcharsex($arItem["TITLE"]);
		$bLast = $index == $lastindex;
		if ($NextSectionID) {
			if ($bMobileBreadcrumbs && $visibleMobile == $index) {
				$bShowMobileArrow = true;
			}
		}
		if($index){
			$strReturn .= '<span class="breadcrumbs__separator">'.TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/arrows.svg#right-7-5', 'muted-use fill-dark-light', ['WIDTH' => 7,'HEIGHT' => 5]).'</span>';
		}
		if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections){
			$strReturn .= '<div class="breadcrumbs__item'.($bMobileBreadcrumbs ? ' breadcrumbs__item--mobile' : '').($bShowMobileArrow ? ' breadcrumbs__item--visible-mobile' : '').($arSubSections ? ' breadcrumbs__item--with-dropdown colored_theme_hover_bg-block' : '').($bLast ? ' cat_last' : '').'" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
			
			$strReturn .= '<a class="breadcrumbs__link " href="'.$arItem["LINK"].'" title="'.$title.'" itemprop="item">';
			// if ($bShowMobileArrow) {
			// 	$strReturn .= TSolution::showIconSvg('colored_theme_hover_bg-el-svg', SITE_TEMPLATE_PATH.'/images/svg/catalog/arrow_breadcrumbs.svg');
			// }
			$strReturn .= '<span itemprop="name" class="breadcrumbs__item-name font_13">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></a>';
			
			$strReturn .= '</div>';
		}
		else{
			$strReturn .= '<span class="breadcrumbs__item'.($bMobileBreadcrumbs ? ' breadcrumbs__item--mobile' : '').'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><link href="'.GetPagePath().'" itemprop="item" /><span><span itemprop="name" class="breadcrumbs__item-name font_13">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></span></span>';
		}
	}

	return '<div class="breadcrumbs swipeignore" itemscope="" itemtype="http://schema.org/BreadcrumbList">'.$strReturn.'</div>';
	//return $strReturn;
}
else{
	return $strReturn;
}
?>