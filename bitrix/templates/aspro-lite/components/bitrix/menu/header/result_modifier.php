<?
$arResult = TSolution::getChilds2($arResult);

global $arTheme;
$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];

if ($MENU_TYPE == 3) {
	TSolution::replaceMenuChilds($arResult, $arParams);
}

if($arParams["CATALOG_WIDE"] === "Y" && is_array($arResult) && count($arResult) > 0 ){
	$arResult = reset($arResult)['CHILD'];
}
?>
