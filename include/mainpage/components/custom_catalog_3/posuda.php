<?
use Bitrix\Main\SystemException;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	include_once '../../../../ajax/const.php';
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
}

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	throw new SystemException('Error include solution constants');
}
?>
<?$APPLICATION->IncludeComponent(
	"aspro:tabs.lite", 
	".default", 
	array(
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "",
		"FILTER_NAME" => "arFilterCatalog",
		"HIT_PROP" => "HIT",
		"IBLOCK_ID" => "13",
		"IBLOCK_TYPE" => "aspro_lite_catalog",
		"PARENT_SECTION" => "",
		"PROPERTY_CODE" => array(
			0 => "HIT",
			1 => "BRAND",
			2 => "FORM_ORDER",
			3 => "PRICE",
			4 => "PRICEOLD",
			5 => "ECONOMY",
			6 => "STATUS",
			7 => "SHOW_ON_INDEX_PAGE",
			8 => "ARTICLE",
			9 => "DATE_COUNTER",
			10 => "RECOMMEND",
			11 => "",
		),
		"ELEMENT_SORT_FIELD" => "SORT",
		"ELEMENT_SORT_FIELD2" => "ID",
		"ELEMENT_SORT_ORDER" => "ASC",
		"ELEMENT_SORT_ORDER2" => "ASC",
		"TITLE" => "Товары для дома и дачи",
		"COMPONENT_TEMPLATE" => ".default",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "",
		),
		"ELEMENTS_TABLE_TYPE_VIEW" => "FROM_MODULE",
		"SHOW_SECTION" => "Y",
		"COUNT_IN_LINE" => "4",
		"RIGHT_LINK" => "catalog/tovary-dlya-doma-i-dachi/",
		"SHOW_DISCOUNT_TIME" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PREVIEW_TEXT" => "N",
		"SHOW_DISCOUNT_PRICE" => "Y",
		"SHOW_GALLERY" => "Y",
		"ADD_PICT_PROP" => "PHOTOS",
		"MAX_GALLERY_ITEMS" => "5",
		"SKU_IBLOCK_ID" => "14",
		"SKU_PROPERTY_CODE" => array(
			0 => "STATUS",
			1 => "PRICE_CURRENCY",
			2 => "PRICE",
			3 => "PRICEOLD",
			4 => "FILTER_PRICE",
			5 => "ECONOMY",
			6 => "MORE_PHOTO",
			7 => "COLOR_REF",
			8 => "SIZES",
			9 => "SIZES4",
			10 => "SIZES5",
			11 => "SIZES3",
		),
		"SKU_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES",
			2 => "VOLUME",
			3 => "SIZES4",
			4 => "SIZES5",
			5 => "SIZES3",
		),
		"TYPE_TEMPLATE" => "catalog_block",
		"NARROW" => "FROM_THEME",
		"ITEMS_OFFSET" => "FROM_THEME",
		"TEXT_CENTER" => "FROM_THEME",
		"IMG_CORNER" => "N",
		"ELEMENT_IN_ROW" => "5",
		"COUNT_ROWS" => "1",
		"TABS_FILTER" => "PROPERTY",
		"BORDERED" => "Y",
		"ELEMENTS_SOURCE" => "SHOW_ON_INDEX_PAGE",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"SHOW_TABS" => "Y",
		"PAGE_ELEMENT_COUNT" => ""
	),
	false
);?>