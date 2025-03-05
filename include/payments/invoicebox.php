<?$APPLICATION->IncludeComponent(
    "aspro:invoicebox.payment", 
    ".default", 
    array(
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "ORDER_AMOUNT" => $totalSumm,
        "PARTICIPANT_IDENT" => "",
        "PARTICIPANT_ID" => "",
        "PARTICIPANT_SIGN" => "",
        "ORDER_ID" => $_REQUEST["RESULT_ID"],
        "ORDER_CURRENCY_IDENT" => "",
        "PERSON_NAME" => $name,
        "PERSON_EMAIL" => $email,
        "PERSON_PHONE" => $phone,
        "TESTMODE" => "Y",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);?>