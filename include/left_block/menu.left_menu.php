<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left",
	array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => min(CLite::GetFrontParametrValue('MAX_DEPTH_MENU'), '4'),
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "Y",
		"COMPONENT_TEMPLATE" => "left",
		"CACHE_SELECTED_ITEMS" => "Y",
	),
	false, ['HIDE_ICONS' => 'N']
);?>