<?global $arTheme;
$maxLevel = $arParams["USE_NLO_MENU"] !== "Y" || ($arParams["USE_NLO_MENU"] === "Y" && $arParams["NLO_MENU_CODE"] && isset($_REQUEST['nlo']) && $_REQUEST['nlo'] === $arParams["NLO_MENU_CODE"]) ? TSolution::GetFrontParametrValue('MAX_DEPTH_MENU') : '1';
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"header",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "header",
		"COUNT_ITEM" => "6",
		"DELAY" => "N",
		"MAX_LEVEL" => min($maxLevel, '4'),
		"MENU_CACHE_GET_VARS" => array(),
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"ALLOW_MULTI_SELECT" => "Y",
		"ROOT_MENU_TYPE" => "only_catalog",
		"USE_EXT" => "Y",
        "ONLY_CATALOG" => "Y",
		"LARGE_CATALOG_BUTTON" => $arParams["LARGE_CATALOG_BUTTON"] ?? 'N',
		"USE_NLO_MENU" => $arParams["USE_NLO_MENU"] ?? 'N',
		"NLO_MENU_CODE" => $arParams["NLO_MENU_CODE"] ?? 'menu-fixed',
	)
);?>