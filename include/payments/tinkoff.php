<?$APPLICATION->IncludeComponent(
    "rover:tinkoff.payform", 
    ".default", 
    array(
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "FAIL_URL" => $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/tinkoff-final/fail.php",
        "ITEM_ID" => $_REQUEST["RESULT_ID"],
        "LANGUAGE" => "ru",
        "MESSAGE" => "",
        "NOTIFICATION_URL" => "",
        "ORDER_ID" => $_REQUEST["RESULT_ID"],
        "REDIRECT_URLS" => "",
        "SHOP_SECRET_WORD" => "",
        "SUCCESS_URL" => $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/tinkoff-final/success.php",
        "SUM" => str_replace(" ","",$totalSumm),
        "SUM_FORMATTED" => $totalSumm,
        "SUM_IN_CURRENCY_FORMATTED" => "",
        "TERMINAL_ID" => "",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);?>