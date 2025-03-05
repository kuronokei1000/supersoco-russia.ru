<?if(\Bitrix\Main\ModuleManager::isModuleInstalled("subscribe")):?>
    <?$arOptions = $arConfig['PARAMS'];?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:subscribe.edit", 
        "side", 
        array(
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_SHADOW" => "Y",
            "AJAX_OPTION_STYLE" => "Y",
            "ALLOW_ANONYMOUS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "PAGE" => "",
            "PARAMS" => $arOptions,
            "SET_TITLE" => "N",
            "SHOW_AUTH_LINKS" => "N",
            "BUTTON_TYPE" => "",
            "SHOW_HIDDEN" => "N",
            "COMPONENT_TEMPLATE" => "footer"
        ),
        false
    );?>
<?endif;?>