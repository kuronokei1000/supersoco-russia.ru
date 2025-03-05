<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?$APPLICATION->IncludeComponent(
    "aspro:developer.lite",
    ".default",
    array(
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600000",
        "CACHE_GROUPS" => "N",
        "COMPONENT_TEMPLATE" => ".default",
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>