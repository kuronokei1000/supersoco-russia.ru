<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"aspro:oneclickbuy.lite", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",

		"BUY_ALL_BASKET" => "N",
		"IBLOCK_ID" => $iblockId,
		"ELEMENT_ID" => $productId,
		"ELEMENT_QUANTITY" => $quantity,
		"OFFER_PROPERTIES" => $offerProps,

		"OPTIONS_FROM_MODULE" => "Y",
	),
	false
);?>