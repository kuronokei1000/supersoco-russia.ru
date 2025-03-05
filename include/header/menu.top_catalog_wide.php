<?global $arTheme;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"header",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "header",
		"COUNT_ITEM" => "6",
		"DELAY" => "N",
		"MAX_LEVEL" => min(CLite::GetFrontParametrValue('MAX_DEPTH_MENU'), '4'),
		"MENU_CACHE_GET_VARS" => array(),
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"ALLOW_MULTI_SELECT" => "Y",
		"ROOT_MENU_TYPE" => "only_catalog",
		"USE_EXT" => "Y",
        "CATALOG_WIDE" => "Y"
	)
);?>