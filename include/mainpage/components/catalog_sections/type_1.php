<?
$indexPageOptions = $GLOBALS['arTheme']['INDEX_TYPE']['SUB_PARAMS'][$GLOBALS['arTheme']['INDEX_TYPE']['VALUE']];
$blockOptions = $indexPageOptions['CATALOG_SECTIONS'];
$blockTemplateOptions = $blockOptions['TEMPLATE']['LIST'][$blockOptions['TEMPLATE']['VALUE']];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	".default", 
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
		"COMPONENT_TEMPLATE" => ".default",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(
			0 => "NAME",
			1 => "PICTURE",
			2 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "UF_POPULAR",
			1 => "UF_CATALOG_ICON",
			2 => "UF_SVG_INLINE",
			3 => "",
		),
		"BORDERED" => "FROM_THEME",
		"IMAGES" => "FROM_THEME",
		"SHOW_TITLE" => $blockOptions["INDEX_BLOCK_OPTIONS"]["BOTTOM"]["SHOW_TITLE"]["VALUE"],
		"TITLE" => "Популярные категории",
		"RIGHT_TITLE" => "Весь каталог",
		"RIGHT_LINK" => "catalog/",
		"CHECK_REQUEST_BLOCK" => TSolution::checkRequestBlock("catalog_sections"),
		"IS_AJAX" => TSolution::checkAjaxRequest(),
		"THEME" => array(
			"BORDERED" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["BORDERED"]["VALUE"],
			"IMAGES" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["IMAGES"]["VALUE"],
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE"
	),
	false
);?>