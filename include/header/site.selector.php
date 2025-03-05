<?$APPLICATION->IncludeComponent(
    "bitrix:main.site.selector",
    $options['TEMPLATE'],
    array(
        "SITE_LIST" => $options['SITE_LIST'], 
        "CACHE_TYPE" => "A", 
        "CACHE_TIME" => "3600",
        'IS_AJAX' => $options['IS_AJAX'],
        'ONLY_ICON' => $options['ONLY_ICON'],
        'DROPDOWN_TOP' => $options['DROPDOWN_TOP'],
        'SITE_SELECTOR_NAME' => $options['SITE_SELECTOR_NAME'],
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>