<?
$indexPageOptions = $GLOBALS['arTheme']['INDEX_TYPE']['SUB_PARAMS'][ $GLOBALS['arTheme']['INDEX_TYPE']['VALUE'] ];
$blockOptions = $indexPageOptions['SALE'];
$blockTemplateOptions = $blockOptions['TEMPLATE']['LIST'][ $blockOptions['TEMPLATE']['VALUE'] ];

$bShowMore = $blockTemplateOptions["ADDITIONAL_OPTIONS"]["LINES_COUNT"]["VALUE"] === 'SHOW_MORE';
$linesCount = $bShowMore ? 1 : (intval($blockTemplateOptions["ADDITIONAL_OPTIONS"]["LINES_COUNT"]["VALUE"]) ?: 1);
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"sale-list", 
	array(
		"IBLOCK_TYPE" => "aspro_lite_content",
		"IBLOCK_ID" => "16",
		"NEWS_COUNT" => $linesCount*$blockTemplateOptions["ADDITIONAL_OPTIONS"]["ELEMENTS_COUNT"]["VALUE"],
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arFrontFilter",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "ACTIVE_TO",
			4 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "PERIOD",
			1 => "SALE_NUMBER",
			2 => "REDIRECT",
			3 => "",
		),
		"CHECK_DATES" => "N",
		"SHOW_SECTION" => "N",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "N",
		"PAGER_TEMPLATE" => "ajax",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => $bShowMore ? "Y" : "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"TITLE" => "Акции",
		"RIGHT_TITLE" => "Все акции",
		"RIGHT_LINK" => "sales/",
		"COMPONENT_TEMPLATE" => "sale-list",
		"SET_LAST_MODIFIED" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"SHOW_DETAIL_LINK" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"SHOW_DATE" => "Y",
		"ELEMENTS_ROW" => "FROM_THEME",
		"THEME" => array(
			"ELEMENTS_ROW" => $blockTemplateOptions["ADDITIONAL_OPTIONS"]["ELEMENTS_COUNT"]["VALUE"],
		),
		"SHOW_PREVIEW" => false,
		"SHOW_PREVIEW_TEXT" => "Y",
		"NAME_SIZE" => "18",
		"SHOW_TITLE" => $blockOptions["INDEX_BLOCK_OPTIONS"]["BOTTOM"]["SHOW_TITLE"]["VALUE"]=="Y",
		"CHECK_REQUEST_BLOCK" => TSolution::checkRequestBlock("sale"),
		"IS_AJAX" => TSolution::checkAjaxRequest(),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"GRID_LIST_ROW_GAP_40" => true,
		"MAXWIDTH_WRAP" => "Y"
	),
	false
);?>