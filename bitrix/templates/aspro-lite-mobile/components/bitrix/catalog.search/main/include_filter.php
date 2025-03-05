<?
global $arTheme, $APPLICATION, $preFilterCatalog;

if($arTheme['SHOW_SMARTFILTER']['VALUE'] !== 'N'){
	$template = 'main_compact';	
	
	$preFilterCatalog = ['ID' => $arElements];
	
	$this->__component->__template->SetViewTarget('filter_content');
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.smart.filter', $template,
			array(
				'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'SECTION_ID' => '',
				"PREFILTER_NAME" => "preFilterCatalog",
				'FILTER_NAME' => $arParams['FILTER_NAME'],
				'PRICE_CODE' => $arParams['PRICE_CODE'],
				'CACHE_TYPE' => $arParams['CACHE_TYPE'],
				'CACHE_TIME' => $arParams['CACHE_TIME'],
				'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
				'SAVE_IN_SESSION' => 'N',
				'FILTER_VIEW_MODE' => 'VERTICAL',
				'DISPLAY_ELEMENT_COUNT' => 'Y',
				'POPUP_POSITION' => ($arTheme['SIDE_MENU']['VALUE'] == 'LEFT' ? 'right' : 'left'),
				'INSTANT_RELOAD' => 'Y',
				'XML_EXPORT' => 'N',
				'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
				'SORT_HTML' => $sortHTML,
				'SHOW_HINTS' => $arParams['SHOW_HINTS'],
				"SEF_MODE" => (strlen($arResult["URL_TEMPLATES"]["smart_filter"]) ? "Y" : "N"),
				"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
				"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
				"HIDE_NOT_AVAILABLE" => TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE'),
			),
			$component
		);
	$this->__component->__template->EndViewTarget();
}

?>