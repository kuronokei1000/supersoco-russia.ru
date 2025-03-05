<?
$indexPageOptions = $GLOBALS['arTheme']['INDEX_TYPE']['SUB_PARAMS'][$GLOBALS['arTheme']['INDEX_TYPE']['VALUE']];
$blockOptions = $indexPageOptions['CATALOG_SECTIONS'];
$blockTemplateOptions = $blockOptions['TEMPLATE']['LIST'][$blockOptions['TEMPLATE']['VALUE']];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	"main", 
	array(
		"IBLOCK_TYPE" => "aspro_lite_catalog",
		"IBLOCK_ID" => "13",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "arrPopularSections",
		"TOP_DEPTH" => "2",
		"SECTION_URL" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"COMPONENT_TEMPLATE" => "main",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(
			0 => "NAME",
			1 => "PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "UF_POPULAR",
			1 => "UF_CATALOG_ICON",
			2 => "UF_SVG_INLINE",
			3 => "",
		),
		"BORDERED" => "FROM_THEME",
		"ELEMENTS_IN_ROW" => "FROM_THEME",
		"LINES_COUNT" => "FROM_THEME",
		"IMAGES" => "PICTURES",
		"SHOW_TITLE" => $blockOptions["INDEX_BLOCK_OPTIONS"]["BOTTOM"]["SHOW_TITLE"]["VALUE"],
		"TITLE" => "Показать весь каталог",
		"RIGHT_TITLE" => "Весь каталог",
		"RIGHT_LINK" => "catalog/eleсtromoto/",
		"CHECK_REQUEST_BLOCK" => TSolution::checkRequestBlock("catalog_sections"),
		"IS_AJAX" => TSolution::checkAjaxRequest(),
		"THEME" => array(
			"BORDERED" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["BORDERED"]["VALUE"],
			"ELEMENTS_IN_ROW" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["ELEMENTS_COUNT"]["VALUE"],
			"LINES_COUNT" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["LINES_COUNT"]["VALUE"],
			"IMAGES" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["IMAGES"]["VALUE"],
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
		"ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
		"HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N"
	),
	false
);?>