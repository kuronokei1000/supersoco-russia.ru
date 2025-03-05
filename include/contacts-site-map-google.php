<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	"map", 
	array(
		"API_KEY" => \Bitrix\Main\Config\Option::get("fileman","google_map_api_key",""),
		"COMPONENT_TEMPLATE" => "map",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTROLS" => array(
			0 => "SMALL_ZOOM_CONTROL",
			1 => "TYPECONTROL",
		),
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.620438700053;s:3:\"LAT\";d:55.753445723095;s:4:\"TEXT\";s:8:\"Компания\";}}s:10:\"google_lat\";d:55.76;s:10:\"google_lon\";d:37.64;s:12:\"google_scale\";i:10;}",
		"MAP_HEIGHT" => "550px",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"USE_REGION_DATA" => "Y"
	),
	false
);?>