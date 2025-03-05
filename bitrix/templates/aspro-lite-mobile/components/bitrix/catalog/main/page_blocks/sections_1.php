<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();

if($arParams['SECTIONS_TYPE_VIEW'] === 'FROM_MODULE'){
	$blockTemplateOptions = $GLOBALS['arTheme']['SECTIONS_TYPE_VIEW_CATALOG']['LIST'][$GLOBALS['arTheme']['SECTIONS_TYPE_VIEW_CATALOG']['VALUE']];
	$bordered = $blockTemplateOptions['ADDITIONAL_OPTIONS']['SECTIONS_BORDERED']['VALUE'];
	$images = $blockTemplateOptions['ADDITIONAL_OPTIONS']['SECTIONS_IMAGES']['VALUE'];
	$elementsInRow = $blockTemplateOptions['ADDITIONAL_OPTIONS']['SECTIONS_ELEMENTS_COUNT']['VALUE'];
}
else{
	$bordered = $arParams['SECTIONS_BORDERED'];
	$images = $arParams['SECTIONS_IMAGES'];
	$elementsInRow = $arParams['SECTIONS_ELEMENTS_COUNT'];
}
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	"main", 
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=> $arParams["IBLOCK_ID"],
		"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
		// "TOP_DEPTH" => 1,
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"ADD_SECTIONS_CHAIN" => "N",
		"COMPONENT_TEMPLATE" => "main",
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_FIELDS" => array(
			0 => "NAME",
			2 => "PICTURE",
			3 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "UF_CATALOG_ICON",
			1 => "UF_SVG_INLINE",
			2 => "",
		),
		"BORDERED" => $bordered,
		"IMAGES" => $images,
		"ELEMENTS_IN_ROW" => $elementsInRow,
		"NARROW" => "Y",
		"CHECK_REQUEST_BLOCK" => TSolution::checkRequestBlock("catalog_sections"),
		"IS_AJAX" => TSolution::checkAjaxRequest(),
		"MOBILE_SCROLLED" => "N",
		"MOBILE_COMPACT" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
