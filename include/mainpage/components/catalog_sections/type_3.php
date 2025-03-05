<?
$indexPageOptions = $GLOBALS['arTheme']['INDEX_TYPE']['SUB_PARAMS'][$GLOBALS['arTheme']['INDEX_TYPE']['VALUE']];
$blockOptions = $indexPageOptions['CATALOG_SECTIONS'];
$blockTemplateOptions = $blockOptions['TEMPLATE']['LIST'][$blockOptions['TEMPLATE']['VALUE']];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	"slider", 
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
		"COMPONENT_TEMPLATE" => "slider",
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
		"IMAGES" => "FROM_THEME",
		"IMAGE_ON_FON" => "FROM_THEME",
		"SHOW_TITLE" => $blockOptions["INDEX_BLOCK_OPTIONS"]["BOTTOM"]["SHOW_TITLE"]["VALUE"],
		"TITLE" => "Весь каталог электромотоциклов",
		"RIGHT_TITLE" => "Посмотреть весь каталог электромотоциклов",
		"RIGHT_LINK" => "catalog/electromotobikes/",
		"CHECK_REQUEST_BLOCK" => TSolution::checkRequestBlock("catalog_sections"),
		"IS_AJAX" => TSolution::checkAjaxRequest(),
		"THEME" => array(
			"IMAGES" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["IMAGES"]["VALUE"],
			"IMAGE_ON_FON" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["IMAGE_ON_FON"]["VALUE"],
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
		"ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
		"HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N"
	),
	false
);?>