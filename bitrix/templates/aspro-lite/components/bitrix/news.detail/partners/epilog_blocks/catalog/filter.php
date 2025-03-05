<?
global $arTheme, $preFilterBrand;
$arParams["FILTER_NAME"] = "arrGoodsFilter";
TSolution\Extensions::init(['smart_filter']);

if($arTheme['SHOW_SMARTFILTER']['VALUE'] !== 'N'){		
	$APPLICATION->IncludeComponent(
		'bitrix:catalog.smart.filter', "main_compact",
		array(
			'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
			'IBLOCK_ID' => $arParams['LINK_GOODS_IBLOCK_ID'],
			'SECTION_ID' => '',
			//"AJAX_FILTER_FLAG" => $isAjax,
			"PREFILTER_NAME" => "preFilterBrand",
			'FILTER_NAME' => $arParams['FILTER_NAME'],
			'PRICE_CODE' => $arParams['PRICE_CODE'],
			'CACHE_TYPE' => $arParams['CACHE_TYPE'],
			'CACHE_TIME' => $arParams['CACHE_TIME'],
			'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
			'SAVE_IN_SESSION' => 'N',
			'FILTER_VIEW_MODE' => 'VERTICAL',
			'VIEW_MODE' => '',
			'DISPLAY_ELEMENT_COUNT' => 'Y',
			'POPUP_POSITION' => ($arTheme['SIDE_MENU']['VALUE'] == 'LEFT' ? 'right' : 'left'),
			'INSTANT_RELOAD' => 'Y',
			"SECTION_IDS" => ($setionIDRequest ? array($setionIDRequest) : $arSectionsID),
			"ELEMENT_IDS" => ($setionIDRequest ? $arAllSections[$setionIDRequest]["ITEMS"] : $arItemsID),
			'XML_EXPORT' => 'N',
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'SORT_HTML' => $sortHTML,
			'AJAX' => TSolution::GetFrontParametrValue('AJAX_FILTER'),
			"HIDDEN_PROP" => array("BRAND"),
			'SHOW_HINTS' => $arParams['SHOW_HINTS'],
			"SEF_MODE" => "N",
			"SEF_RULE" => $arParams["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
			"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
			"HIDE_NOT_AVAILABLE" => TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE'),
			"SEF_RULE_FILTER" => $arResult["URL_TEMPLATES"]["smart_filter"],
			"HIDE_SMART_SEO" => "Y",
		),
		$component
	);
}
?>