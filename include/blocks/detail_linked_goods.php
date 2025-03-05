<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?
use Bitrix\Main\Loader;

global $arTheme;

$arParams = $arConfig['PARAMS'];
$arAdditionalParams = [
	"OFFERS_FIELD_CODE" => ['ID', 'NAME'],
	"SKU_IBLOCK_ID"	=>	$arParams["SKU_IBLOCK_ID"],
	"SKU_TREE_PROPS"	=>	$arParams["SKU_TREE_PROPS"],
	"OFFER_TREE_PROPS"	=>	$arParams["SKU_TREE_PROPS"],
	"SKU_PROPERTY_CODE"	=>	$arParams["SKU_PROPERTY_CODE"],
	"OFFERS_PROPERTY_CODE"	=>	$arParams["SKU_PROPERTY_CODE"],
	"OFFERS_SORT_FIELD" => $arParams["SKU_SORT_FIELD"],
	"OFFERS_SORT_ORDER" => $arParams["SKU_SORT_ORDER"],
	"OFFERS_SORT_FIELD2" => $arParams["SKU_SORT_FIELD2"],
	"OFFERS_SORT_ORDER2" => $arParams["SKU_SORT_ORDER2"],
	"PRICE_CODE" => explode(',', TSolution::GetFrontParametrValue('PRICES_TYPE')),
	"USE_PRICE_COUNT" => 'N',
	"STORES" => explode(',', TSolution::GetFrontParametrValue('STORES')),
];

$bSlider = TSolution::GetFrontParametrValue('VIEW_LINKED_GOODS') === 'catalog_slider';
if (!$bSlider) {
	$arAdditionalParams = array_merge($arAdditionalParams, [
		"TYPE_SKU" => $arTheme['CATALOG_PAGE_DETAIL_SKU']['VALUE'] ?? 'TYPE_1',
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"ITEM_HOVER_SHADOW" => true,
	]);
}

global $arRegion;
if ($arRegion) {
    if ($arRegion['LIST_PRICES'] && reset($arRegion['LIST_PRICES']) !== 'component') {
        $arAdditionalParams["PRICE_CODE"] = array_keys($arRegion['LIST_PRICES']);
    }
    if ($arRegion['LIST_STORES'] && reset($arRegion['LIST_STORES']) !== 'component') {
        $arAdditionalParams["STORES"] = $arRegion['LIST_STORES'];
    }
    $arAdditionalParams["USE_REGION"] = 'Y';
}
$arProps = $arParams['LINKED_PROPERTY_CODE'] ?? $arParams['LIST_PROPERTY_CODE'] ?? $arParams['PROPERTY_CODE'];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"catalog_block",
	array_merge(
		Array(
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
			"DETAIL_URL" => "",
			"FILTER_NAME" => $arParams['FILTER_NAME'] ?? "arrGoodsFilter",
			"HIT_PROP" => "HIT",
			"IBLOCK_TYPE" => "aspro_lite_catalog",
			"COMPATIBLE_MODE" => "Y",
			"IBLOCK_ID"	=> TSolution::GetFrontParametrValue('CATALOG_IBLOCK_ID'),
			"PAGE_ELEMENT_COUNT" => TSolution::GetFrontParametrValue('COUNT_LINKED_GOODS') ?? "20",
			"PROPERTY_CODE"	=> array_merge($arProps, ['HIT']),
			"ELEMENT_SORT_FIELD" => "SORT",
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_FIELD2" => "ID",
			"ELEMENT_SORT_ORDER2" => "DESC",
			//"SECTION_ID" => "",
			//"SECTION_CODE" => "",
			"FIELD_CODE" => $arParams['LINKED_FIELD_CODE'] ?? $arParams['LIST_FIELD_CODE'] ?? $arParams['FIELD_CODE'],
			"ELEMENTS_TABLE_TYPE_VIEW" => "FROM_MODULE",
			"SHOW_SECTION" => "Y",
			"COUNT_IN_LINE" => "",
			"LINE_ELEMENT_COUNT" => "4",
			"SHOW_GALLERY" => TSolution::GetFrontParametrValue('SHOW_CATALOG_GALLERY_IN_LIST'),
			"MAX_GALLERY_ITEMS" => TSolution::GetFrontParametrValue('MAX_GALLERY_ITEMS'),
			"ADD_PICT_PROP" => TSolution::GetFrontParametrValue('GALLERY_PROPERTY_CODE') ?? 'MORE_PHOTO',
			"OFFER_ADD_PICT_PROP" => TSolution::GetFrontParametrValue('GALLERY_PROPERTY_CODE') ?? 'MORE_PHOTO',
			"DISPLAY_TOP_PAGER"	=>	"N",
			"DISPLAY_BOTTOM_PAGER"	=>	"N",
			"PAGER_TITLE"	=>	"",
			"PAGER_TEMPLATE"	=>	"ajax",
			"PAGER_SHOW_ALWAYS"	=>	"N",
			"PAGER_DESC_NUMBERING"	=>	"N",
			"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	"36000",
			"PAGER_SHOW_ALL" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"SHOW_ALL_WO_SECTION" => "Y",
			"SECTION_COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
			"IS_CATALOG_PAGE" => 'Y',
			"META_KEYWORDS" => "",
			"META_DESCRIPTION" => "",
			"BROWSER_TITLE" => "",
			"ADD_SECTIONS_CHAIN" => "N",
			"DISPLAY_COMPARE" => TSolution::GetFrontParametrValue('CATALOG_COMPARE'),
			"SHOW_FAVORITE" => TSolution::GetFrontParametrValue('SHOW_FAVORITE'),
			"CONVERT_CURRENCY" => TSolution::GetFrontParametrValue('CONVERT_CURRENCY'),
			"CURRENCY_ID" => TSolution::GetFrontParametrValue('CURRENCY_ID'),
			"PRICE_VAT_INCLUDE" => TSolution::GetFrontParametrValue('PRICE_VAT_INCLUDE'),
			"HIDE_NOT_AVAILABLE" => TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE'),
			"HIDE_NOT_AVAILABLE_OFFERS" => TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE_OFFERS'),
			"SHOW_HINTS" => TSolution::GetFrontParametrValue('SHOW_HINTS'),
			
			"SHOW_ONE_CLICK_BUY" => TSolution::GetFrontParametrValue('SHOW_ONE_CLICK_BUY'),
			"USE_FAST_VIEW_PAGE_DETAIL" => TSolution::GetFrontParametrValue('USE_FAST_VIEW_PAGE_DETAIL'),
			"EXPRESSION_FOR_FAST_VIEW" => TSolution::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'),
			"ORDER_VIEW" => TSolution::GetFrontParametrValue('ORDER_VIEW'),
			
			"ELEMENT_IN_ROW" => $arParams['ELEMENT_IN_ROW'] ?? 3,
			"ITEM_768" => "3",
			"ITEM_992" => "4",
			"ITEM_1200" => $arParams['ITEM_1200'] ?? 3,
			"POSITION_BTNS" => "4",
			"AJAX_REQUEST" => $arParams['IS_AJAX'],
			"TEXT_CENTER" => false,
			"IMG_CORNER" => false,
			"GRID_GAP" => "20",
			"ROW_VIEW" => true,
			"SLIDER" => $bSlider,
			"SLIDER_BUTTONS_BORDERED" => false,
			"IS_COMPACT_SLIDER" => false,
			"BORDERED" => 'Y',
			"IMG_CORNER" => 'N',
			"ELEMENTS_ROW" => 1,
			"MAXWIDTH_WRAP" => false,
			"MOBILE_SCROLLED" => false,
			"ITEM_0" => "2",
			"ITEM_380" => "2",
			"NARROW" => "Y",
			"IS_CATALOG_PAGE" => "N",
			"IMAGES" => "PICTURE",
			"IMAGE_POSITION" => "LEFT",
			"SHOW_PREVIEW" => true,
			"SHOW_TITLE" => false,
			"TITLE_POSITION" => "",
			"TITLE" => "",
			"RIGHT_TITLE" => "",
			"RIGHT_LINK" => "",
			"CHECK_REQUEST_BLOCK" => $arParams['CHECK_REQUEST_BLOCK'],
			"TYPE_SKU" => "TYPE_2",
			"NAME_SIZE" => "18",
			"SUBTITLE" => "",
			"SHOW_PREVIEW_TEXT" => "N",
			"SHOW_DISCOUNT_TIME" => TSolution::GetFrontParametrValue('SHOW_DISCOUNT_TIME'),
			"SHOW_DISCOUNT_PERCENT" => TSolution::GetFrontParametrValue('SHOW_DISCOUNT_PERCENT'),
			"SHOW_OLD_PRICE" => TSolution::GetFrontParametrValue('SHOW_OLD_PRICE'),
			"SHOW_RATING" => Loader::includeModule('blog') ? TSolution::GetFrontParametrValue('SHOW_RATING') : false,
			"NO_USE_SHCEMA_ORG" => "Y",
		),
		$arAdditionalParams
	),
	$component, array('HIDE_ICONS' => 'Y')
);?>