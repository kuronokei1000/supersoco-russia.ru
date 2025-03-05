<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>


<? $APPLICATION->IncludeComponent(
    "bitrix:map.yandex.view",
    "",
    [
        "API_KEY" => \Bitrix\Main\Config\Option::get("fileman", "yandex_map_api_key", ""),
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "CONTROLS" => ["ZOOM", "SMALLZOOM", "TYPECONTROL"],
        "INIT_MAP_TYPE" => "MAP",
        "KEY" => "AKk9BlwBAAAAcf5CSgMAHyfAq9knnHW9nsNrwnKOBpJ8-FUAAAAAAAAAAABE8lP1ifeROCbNOEGuF0oRi1P0xQ==",
        "MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.690864091052354;s:10:\"yandex_lon\";d:37.53377415374958;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.534191084905274;s:3:\"LAT\";d:55.69082692357004;s:4:\"TEXT\";s:0:\"\";}}}",
        "MAP_HEIGHT" => "550px",
        "MAP_ID" => "",
        "MAP_WIDTH" => "100%",
        "OPTIONS" => ["ENABLE_DBLCLICK_ZOOM", "ENABLE_DRAGGING"],
        "USE_REGION_DATA" => "Y",
    ]
); ?>