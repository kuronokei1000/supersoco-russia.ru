<?$APPLICATION->IncludeComponent(
    "webfly:sbrf.payment", 
    ".default", 
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "WF_DATA_SOURCE_TYPE" => "WF_OWN_VALUES",
        "WF_OWN_NAME" => $_REQUEST["RESULT_ID"],
        "WF_OWN_PRICE" => str_replace(' ', '', $totalSumm),
        "WF_SHOW_FIELDS" => array(
        ),
        "WF_REQUIRED_FIELDS" => array(
        ),
        "WF_COLOR_BUTTON" => "#37B44A",
        "WF_COLOR_TEXT" => "#FFFFFF",
        "WF_JQUERY" => "N"
    ),
    false
);?>