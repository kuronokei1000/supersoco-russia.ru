<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER_FIELD_MANAGER;
use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager,
	Bitrix\Main\Web\Json,
	Bitrix\Iblock,
	Aspro\Lite\Functions\ExtComponentParameter;

if(
	!Loader::includeModule('iblock') ||
	!Loader::includeModule('aspro.lite')
){
	return;
}

CBitrixComponent::includeComponentClass('bitrix:catalog.section');

$arSKU = $boolSKU = false;
$arPropertySort = $arPropertySortDefault = $arPropertyDefaultSort = array();
$arPrice = $arProperty = $arProperty_N = $arProperty_X = $arProperty_F = array();

$arAscDesc = array(
	'asc' => GetMessage('IBLOCK_SORT_ASC'),
	'desc' => GetMessage('IBLOCK_SORT_DESC'),
);
$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);
$arPropertySortDefault = array("SORT", "SHOWS", "NAME");
$arPropertySort = array(
	"SORT"=>GetMessage("SORT_BUTTONS_SORT"),
	"SHOWS"=>GetMessage("SORT_BUTTONS_POPULARITY"),
	"NAME"=>GetMessage("SORT_BUTTONS_NAME"),
	"CUSTOM"=>GetMessage("SORT_BUTTONS_CUSTOM")
);

if (Loader::includeModule("catalog")) {
	$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields(), array("PROPERTY_MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "PROPERTY_MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE"), "REGION_PRICE"=>GetMessage("SORT_PRICES_REGION_PRICE")));
	if (isset($arSort['CATALOG_AVAILABLE'])) {
		unset($arSort['CATALOG_AVAILABLE']);
	}

	$rsPrice = CCatalogGroup::GetList($v1="sort", $v2="asc");
	while ($arr=$rsPrice->Fetch()) {
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	}
	if ((isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID']) > 0) {
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);
	}
	$arPropertySortDefault = array_merge($arPropertySortDefault, array("PRICES", "QUANTITY"));
	$arPropertySort = array_merge($arPropertySort, array(
		"PRICES"=>GetMessage("SORT_BUTTONS_PRICE"),
		"QUANTITY"=>GetMessage("SORT_BUTTONS_QUANTITY")
	));
} else {
	$arPrice = $arProperty_N;
}

$propertyIterator = Iblock\PropertyTable::getList(array(
	'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE', 'SORT'),
	'filter' => array(
		'=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
		'=ACTIVE' => 'Y'
	),
	'order' => array(
		'SORT' => 'ASC',
		'NAME' => 'ASC'
	)
));
while($property = $propertyIterator->fetch()){
	$propertyCode =(string)$property['CODE'];

	if($propertyCode == '')
		$propertyCode = $property['ID'];

	$propertyName = '['.$propertyCode.'] '.$property['NAME'];
	$arPropertySort[$propertyCode] = $propertyName;

	if($property['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE){
		$arProperty[$propertyCode] = $propertyName;

		if($property['MULTIPLE'] == 'Y'){
			$arProperty_X[$propertyCode] = $propertyName;
		}
		elseif($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST){
			$arProperty_X[$propertyCode] = $propertyName;
		}
		elseif($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT &&(int)$property['LINK_IBLOCK_ID'] > 0){
			$arProperty_X[$propertyCode] = $propertyName;
		}
	}
	else{
		$arProperty_F[$propertyCode] = $propertyName;
	}

	if($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER){
		$arProperty_N[$propertyCode] = $propertyName;
	}

	if($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING){
		$arProperty_S[$propertyCode] = $propertyName;
	}
}

unset($propertyCode, $propertyName, $property, $propertyIterator);

if($arCurrentValues['SORT_PROP']){
	foreach($arCurrentValues['SORT_PROP'] as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}
else {
	foreach($arPropertySortDefault as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}

$arRegionPrice = $arPrice;
if (Loader::includeModule("catalog")) {
	$arPrice  = array_merge(array("MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE"), "REGION_PRICE"=>GetMessage("SORT_PRICES_REGION_PRICE")), $arPrice);
}

$arUserFields_S = $arUserFields_E = array();
$arUserFields = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
foreach($arUserFields as $FIELD_NAME=>$arUserField){
	if($arUserField["USER_TYPE"]["BASE_TYPE"] == "enum"){
		$arUserFields_E[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;
	}

	if($arUserField["USER_TYPE"]["BASE_TYPE"] == "string"){
		$arUserFields_S[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;
	}
}
$arIBlocks = [];
$rsIBlock = CIBlock::GetList(
	[
		'ID' => 'ASC'
	],
	[
		'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
		'ACTIVE' => 'Y'
	]
);
while ($arIBlock = $rsIBlock->Fetch()) {
	$arIBlocks[$arIBlock['ID']] = "[{$arIBlock['ID']}] {$arIBlock['NAME']}";
}

$arTemplateParametersParts = array();

ExtComponentParameter::init(__DIR__, $arCurrentValues);
ExtComponentParameter::addBaseParameters(array(
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'SECTIONS_TYPE_VIEW_CATALOG'),
		'SECTIONS_TYPE_VIEW',
	),
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'SECTION_TYPE_VIEW_CATALOG'),
		'SECTION_TYPE_VIEW',
	),
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'ELEMENTS_CATALOG'),
	),
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'CATALOG'),
	),
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'ELEMENTS_TABLE_TYPE_VIEW'),
	),
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'ELEMENTS_LIST_TYPE_VIEW'),
	),
	array(
		array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'ELEMENTS_PRICE_TYPE_VIEW'),
	),
));

ExtComponentParameter::addRelationBlockParameters(array(
	ExtComponentParameter::RELATION_BLOCK_DESC,
	ExtComponentParameter::RELATION_BLOCK_CHAR,
	array(
		ExtComponentParameter::RELATION_BLOCK_GALLERY,
		'additionalParams' => array(
			'toggle' => true,
			// 'type' => array(
			// 	ExtComponentParameter::GALLERY_TYPE_BIG,
			// 	ExtComponentParameter::GALLERY_TYPE_SMALL,
			// )
		),
	),
	ExtComponentParameter::RELATION_BLOCK_VIDEO,
	ExtComponentParameter::RELATION_BLOCK_DOCS,
	ExtComponentParameter::RELATION_BLOCK_FAQ,
	ExtComponentParameter::RELATION_BLOCK_REVIEWS,
	ExtComponentParameter::RELATION_BLOCK_SALE,
	ExtComponentParameter::RELATION_BLOCK_ARTICLES,
	ExtComponentParameter::RELATION_BLOCK_SERVICES,
	ExtComponentParameter::RELATION_BLOCK_SKU,
	ExtComponentParameter::RELATION_BLOCK_ASSOCIATED,
	ExtComponentParameter::RELATION_BLOCK_EXPANDABLES,
	array(
		ExtComponentParameter::RELATION_BLOCK_BUY,
		'additionalParams' => array(
			'toggle' => false,
		),
	),
	array(
		ExtComponentParameter::RELATION_BLOCK_PAYMENT,
		'additionalParams' => array(
			'toggle' => false,
		),
	),
	array(
		ExtComponentParameter::RELATION_BLOCK_DELIVERY,
		'additionalParams' => array(
			'toggle' => false,
		),
	),
	array(
		ExtComponentParameter::RELATION_BLOCK_DOPS,
		'additionalParams' => array(
			'toggle' => false,
		),
	),
	ExtComponentParameter::RELATION_BLOCK_COMMENTS,
));

ExtComponentParameter::addOrderBlockParameters(array(
	// ExtComponentParameter::ORDER_BLOCK_SALE,
	ExtComponentParameter::ORDER_BLOCK_TABS,
	ExtComponentParameter::ORDER_BLOCK_GALLERY,
	ExtComponentParameter::ORDER_BLOCK_SKU,
	ExtComponentParameter::ORDER_BLOCK_SERVICES,
	ExtComponentParameter::ORDER_BLOCK_ARTICLES,
	ExtComponentParameter::ORDER_BLOCK_ASSOCIATED,
	ExtComponentParameter::ORDER_BLOCK_EXPANDABLES,
	ExtComponentParameter::ORDER_BLOCK_COMMENTS,
	ExtComponentParameter::ORDER_BLOCK_COMPLECT,
	ExtComponentParameter::ORDER_BLOCK_KIT,
	ExtComponentParameter::ORDER_BLOCK_GIFT,
));

ExtComponentParameter::addOrderTabParameters(array(
	ExtComponentParameter::ORDER_BLOCK_DESC,
	ExtComponentParameter::ORDER_BLOCK_CHAR,
	ExtComponentParameter::ORDER_BLOCK_VIDEO,
	ExtComponentParameter::ORDER_BLOCK_DOCS,
	ExtComponentParameter::ORDER_BLOCK_FAQ,
	ExtComponentParameter::ORDER_BLOCK_REVIEWS,
	ExtComponentParameter::ORDER_BLOCK_BUY,
	ExtComponentParameter::ORDER_BLOCK_PAYMENT,
	ExtComponentParameter::ORDER_BLOCK_DELIVERY,
	ExtComponentParameter::ORDER_BLOCK_DOPS,
));

ExtComponentParameter::addOrderAllParameters(array(
	// ExtComponentParameter::ORDER_BLOCK_SALE,
	ExtComponentParameter::ORDER_BLOCK_DESC,
	ExtComponentParameter::ORDER_BLOCK_CHAR,
	ExtComponentParameter::ORDER_BLOCK_REVIEWS,
	ExtComponentParameter::ORDER_BLOCK_GALLERY,
	ExtComponentParameter::ORDER_BLOCK_VIDEO,
	ExtComponentParameter::ORDER_BLOCK_SKU,
	ExtComponentParameter::ORDER_BLOCK_SERVICES,
	ExtComponentParameter::ORDER_BLOCK_ARTICLES,
	ExtComponentParameter::ORDER_BLOCK_DOCS,
	ExtComponentParameter::ORDER_BLOCK_FAQ,
	ExtComponentParameter::ORDER_BLOCK_ASSOCIATED,
	ExtComponentParameter::ORDER_BLOCK_EXPANDABLES,
	ExtComponentParameter::ORDER_BLOCK_BUY,
	ExtComponentParameter::ORDER_BLOCK_PAYMENT,
	ExtComponentParameter::ORDER_BLOCK_DELIVERY,
	ExtComponentParameter::ORDER_BLOCK_DOPS,
	ExtComponentParameter::ORDER_BLOCK_COMMENTS,
	ExtComponentParameter::ORDER_BLOCK_COMPLECT,
	ExtComponentParameter::ORDER_BLOCK_KIT,
	ExtComponentParameter::ORDER_BLOCK_GIFT,
));

ExtComponentParameter::addUseTabParameter('USE_DETAIL_TABS');

ExtComponentParameter::addSelectParameter('SKU_SORT_FIELD', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arSort,
	'DEFAULT' => 'name',
	'ADDITIONAL_VALUES' => 'Y',
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_ORDER', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arAscDesc,
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_FIELD2', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arSort,
	'ADDITIONAL_VALUES' => 'Y',
	'DEFAULT' => 'sort',
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_ORDER2', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arAscDesc,
	'SORT' => 999
]);

ExtComponentParameter::addCheckBoxParameter('USE_SHARE', [
	"DEFAULT" => "N"
]);
ExtComponentParameter::addCheckBoxParameter('HEADING_COUNT_ELEMENTS', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => GetMessage("T_HEADING_COUNT_ELEMENTS"),
	"DEFAULT" => "Y"
]);

if(strpos($arCurrentValues['SECTIONS_TYPE_VIEW'], 'sections_1') !== false){
	ExtComponentParameter::addSelectParameter('SECTIONS_BORDERED', [
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__BORDERED'),
		"VALUES" => [
			"Y" => GetMessage('ASPRO__SELECT_PARAM__YES'),
			"N" => GetMessage('ASPRO__SELECT_PARAM__NO'),
		],
		"DEFAULT" => "Y",
		'SORT' => 999
	]);
	ExtComponentParameter::addSelectParameter('SECTIONS_IMAGES', [
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__IMAGES'),
		"VALUES" => [
			'ICONS' => GetMessage('ASPRO__SELECT_PARAM__ICONS'),
			'PICTURES' => GetMessage('ASPRO__SELECT_PARAM__PICTURES'),
		],
		"DEFAULT" => "PICTURES",
		'SORT' => 999
	]);
	ExtComponentParameter::addSelectParameter('SECTIONS_ELEMENTS_COUNT', [
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW'),
		"VALUES" => [
			"4" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 4]),
			"5" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 5]),
			"6" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 6]),
			"8" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 8]),
		],
		"DEFAULT" => "8",
		'SORT' => 999
	]);
}
if(strpos($arCurrentValues['SECTION_TYPE_VIEW'], 'section_1') !== false){
	ExtComponentParameter::addSelectParameter('SECTION_BORDERED', [
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__BORDERED'),
		"VALUES" => [
			"Y" => GetMessage('ASPRO__SELECT_PARAM__YES'),
			"N" => GetMessage('ASPRO__SELECT_PARAM__NO'),
		],
		"DEFAULT" => "Y",
		'SORT' => 999
	]);
	ExtComponentParameter::addSelectParameter('SECTION_IMAGES', [
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__IMAGES'),
		"VALUES" => [
			'ICONS' => GetMessage('ASPRO__SELECT_PARAM__ICONS'),
			'PICTURES' => GetMessage('ASPRO__SELECT_PARAM__PICTURES'),
		],
		"DEFAULT" => "PICTURES",
		'SORT' => 999
	]);
}
if(strpos($arCurrentValues['SECTION_TYPE_VIEW'], 'section_2') !== false){
	ExtComponentParameter::addSelectParameter('SECTION_BORDERED', [
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__BORDERED'),
		"VALUES" => [
			"Y" => GetMessage('ASPRO__SELECT_PARAM__YES'),
			"N" => GetMessage('ASPRO__SELECT_PARAM__NO'),
		],
		"DEFAULT" => "Y",
		'SORT' => 999
	]);
	ExtComponentParameter::addSelectParameter('SECTION_IMAGES', [
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__IMAGES'),
		"VALUES" => [
			'ICONS' => GetMessage('ASPRO__SELECT_PARAM__ICONS'),
			'PICTURES' => GetMessage('ASPRO__SELECT_PARAM__PICTURES'),
		],
		"DEFAULT" => "PICTURES",
		'SORT' => 999
	]);
	ExtComponentParameter::addSelectParameter('SECTION_ELEMENTS_COUNT', [
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'NAME' => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW'),
		"VALUES" => [
			"4" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 4]),
			"5" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 5]),
			"6" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 6]),
			"8" => GetMessage('ASPRO__SELECT_PARAM__ELEMENTS_IN_ROW_VALUE', ['#ELEMENTS#' => 8]),
		],
		"DEFAULT" => "8",
		'SORT' => 999
	]);
}

ExtComponentParameter::appendTo($arTemplateParameters);

if($arCurrentValues['ELEMENTS_TABLE_TYPE_VIEW'] !== 'FROM_MODULE'){
	$arTemplateParametersParts[] = array(
		'SECTION_ITEM_LIST_IMG_CORNER' => array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('SECTION_ITEM_LIST_IMG_CORNER'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'SORT' => 1501
		),
		'SECTION_ITEM_LIST_BORDERED' => array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('SECTION_ITEM_LIST_BORDERED'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'SORT' => 1502
		),
	);
}

$arTemplateParameters["LANDING_IBLOCK_ID"] = array(
	"NAME" => GetMessage("T_LANDING_IBLOCK_ID"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"PARENT" => "LIST_SETTINGS",
);

$arTemplateParameters["LANDING_TIZER_IBLOCK_ID"] = array(
	"NAME" => GetMessage("T_LANDING_TIZER_IBLOCK_ID"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"PARENT" => "LIST_SETTINGS",
);

$arTemplateParameters["LANDING_SECTION_COUNT"] = array(
	"NAME" => GetMessage("T_LANDING_SECTION_COUNT"),
	"TYPE" => "STRING",
	"DEFAULT" => "20",
	"PARENT" => "LIST_SETTINGS",
);

$arTemplateParameters["LANDING_SECTION_COUNT_VISIBLE"] = array(
	"NAME" => GetMessage("T_LANDING_SECTION_COUNT_VISIBLE"),
	"TYPE" => "STRING",
	"DEFAULT" => "3",
	"PARENT" => "LIST_SETTINGS",
);

if( Loader::includeModule('aspro.smartseo') ){
	$arTemplateParametersParts[] = [
		"SHOW_SMARTSEO_TAGS" => array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('SHOW_SMARTSEO_TAGS_TITLE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'REFRESH' => 'Y',
		),
	];
	if($arCurrentValues["SHOW_SMARTSEO_TAGS"] === 'Y'){
		$arTemplateParametersParts[] = array(
			"SMARTSEO_TAGS_COUNT" => array(
				"NAME" => GetMessage("SMARTSEO_TAGS_COUNT"),
				"TYPE" => "STRING",
				"DEFAULT" => "10",
				"PARENT" => "LIST_SETTINGS",
			),
			/*"SMARTSEO_TAGS_COUNT_MOBILE" => array(
				"NAME" => GetMessage("SMARTSEO_TAGS_COUNT_MOBILE"),
				"TYPE" => "STRING",
				"DEFAULT" => "3",
				"PARENT" => "LIST_SETTINGS",
			),*/
			"SMARTSEO_TAGS_BY_GROUPS" => array(
				"NAME" => GetMessage("SMARTSEO_TAGS_BY_GROUPS"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
				"PARENT" => "LIST_SETTINGS",
			),
			'SMARTSEO_TAGS_SHOW_DEACTIVATED' => array(
				"PARENT" => "LIST_SETTINGS",
				'NAME' => GetMessage('SMARTSEO_TAGS_SHOW_DEACTIVATED'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N',
			),
			'SMARTSEO_TAGS_SORT' => array(
				"PARENT" => "LIST_SETTINGS",
				'NAME' => GetMessage('SMARTSEO_TAGS_SORT'),
				'TYPE' => 'LIST',
				'VALUES' => array(
					'NAME' => GetMessage('SMARTSEO_TAGS_SORT_NAME'),
					'SORT' => GetMessage('SMARTSEO_TAGS_SORT_SORT'),
				),
				'DEFAULT' => 'SORT',
			),
		);
	}
}


$arTemplateParameters['SORT_PROP'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('T_SORT_PROP'),
	'TYPE' => 'LIST',
	'VALUES' => array_merge(array("CUSTOM"=>GetMessage("SORT_BUTTONS_CUSTOM")), $arPropertySort),
	// "VALUES" => array("SORT"=>GetMessage("SORT_BUTTONS_SORT"),"POPULARITY"=>GetMessage("SORT_BUTTONS_POPULARITY"), "NAME"=>GetMessage("SORT_BUTTONS_NAME"), "PRICE"=>GetMessage("SORT_BUTTONS_PRICE"), "QUANTITY"=>GetMessage("SORT_BUTTONS_QUANTITY"), "CUSTOM"=>GetMessage("SORT_BUTTONS_CUSTOM")) + (array)$arPropertySort,
	"DEFAULT" => $arPropertySortDefault,
	'SIZE' => 5,
	'MULTIPLE' => 'Y',
	'REFRESH' => 'Y'
);

$arTemplateParameters['SORT_PROP_DEFAULT'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('T_SORT_PROP_DEFAULT'),
	'TYPE' => 'LIST',
	'VALUES' => $arPropertyDefaultSort,
);

$arTemplateParameters['SORT_DIRECTION'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('T_SORT_DIRECTION'),
	'TYPE' => 'LIST',
	'VALUES' => $arAscDesc
);

if (is_array($arCurrentValues["SORT_PROP"])) {
	if (in_array("PRICES", $arCurrentValues["SORT_PROP"])) {
		$arTemplateParameters["SORT_PRICES"] = Array(
			"SORT"=>200,
			"NAME" => GetMessage("SORT_PRICES"),
			"TYPE" => "LIST",
			"VALUES" => $arPrice,
			"DEFAULT" => array("MINIMUM_PRICE"),
			"PARENT" => "LIST_SETTINGS",
			"MULTIPLE" => "N",
		);
		$arTemplateParameters["SORT_REGION_PRICE"] = Array(
			"SORT"=>200,
			"NAME" => GetMessage("SORT_REGION_PRICE"),
			"TYPE" => "LIST",
			"VALUES" => $arRegionPrice,
			"DEFAULT" => array("BASE"),
			"PARENT" => "LIST_SETTINGS",
			"MULTIPLE" => "N",
		);
	}
}

$arTemplateParameters['SHOW_SORT_RANK_BUTTON'] = array(
	'PARENT' => 'SEARCH_SETTINGS',
	'NAME' => GetMessage('SHOW_SORT_RANK_BUTTON_TITLE'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y',
);


$arTemplateParameters["VIEW_TYPE"] = array(
	"NAME" => GetMessage("DEFAULT_LIST_TEMPLATE"),
	"TYPE" => "LIST",
	"VALUES" => array(
		"table" => GetMessage("DEFAULT_LIST_TEMPLATE_BLOCK"),
		"list" => GetMessage("DEFAULT_LIST_TEMPLATE_LIST"),
		"price" => GetMessage("DEFAULT_LIST_TEMPLATE_TABLE")),
	"DEFAULT" => "table",
	"PARENT" => "LIST_SETTINGS",
);

$arTemplateParameters["SHOW_LIST_TYPE_SECTION"] = array(
	"PARENT" => "LIST_SETTINGS",
	"NAME" => GetMessage("T_SHOW_LIST_TYPE_SECTION"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);

$arTemplateParameters["SECTION_DISPLAY_PROPERTY"] = array(
	"NAME" => GetMessage("SECTION_DISPLAY_PROPERTY"),
	"TYPE" => "LIST",
	"VALUES" => $arUserFields_E,
	"DEFAULT" => "list",
	"MULTIPLE" => "N",
	"PARENT" => "LIST_SETTINGS",
);

$arTemplateParameters["SECTION_TOP_BLOCK_TITLE"] = array(
	"NAME" => GetMessage("SECTION_TOP_BLOCK_TITLE"),
	"TYPE" => "STRING",
	"DEFAULT" => GetMessage("SECTION_TOP_BLOCK_TITLE_VALUE"),
	"PARENT" => "TOP_SETTINGS",
);

$arTemplateParameters["SHOW_ASK_BLOCK"] = array(
	"NAME" => GetMessage("SHOW_ASK_BLOCK"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["ASK_FORM_ID"] = array(
	"NAME" => GetMessage("ASK_FORM_ID"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["SHOW_CHEAPER_FORM"] = array(
	"NAME" => GetMessage("SHOW_CHEAPER_FORM"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["CHEAPER_FORM_NAME"] = array(
	"NAME" => GetMessage("CHEAPER_FORM_NAME"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["SEND_GIFT_FORM_NAME"] = array(
	"NAME" => GetMessage("SEND_GIFT_FORM_NAME"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["SHOW_SEND_GIFT"] = array(
	"NAME" => GetMessage("SHOW_SEND_GIFT"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
	"PARENT" => "DETAIL_SETTINGS",
);
/*
$arTemplateParameters["SHOW_HINTS"] = array(
	"NAME" => GetMessage("SHOW_HINTS"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);
*/

$arTemplateParameters["IBLOCK_TIZERS_ID"] = array(
	"NAME" => GetMessage("IBLOCK_TIZERS_NAME"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
);

$arTemplateParameters["SHOW_LANDINGS"] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('SHOW_LANDINGS_TITLE'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y',
);

$arTemplateParameters["OPT_BUY"] = array(
	"PARENT" => "LIST_SETTINGS",
	"NAME" => GetMessage("T_OPT_BUY"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);

$arTemplateParameters["PROPERTIES_DISPLAY_TYPE"] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("PROPERTIES_DISPLAY_TYPE"),
	"TYPE" => "LIST",
	"MULTIPLE" => "N",
	"VALUES" => array(
		"BLOCK" => GetMessage("PROPERTIES_DISPLAY_TYPE_BLOCK"),
		"TABLE" => GetMessage("PROPERTIES_DISPLAY_TYPE_TABLE")
	),
	"DEFAULT" => "TABLE",
);

$arTemplateParameters["VISIBLE_PROP_COUNT"] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("VISIBLE_PROP_COUNT_TITLE"),
	"TYPE" => "STRING",
	"DEFAULT" => "6",
);

$arTemplateParameters["LINKED_ELEMENT_TAB_SORT_FIELD"] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_FIELD"),
	"TYPE" => "LIST",
	"VALUES" => $arSort,
	"ADDITIONAL_VALUES" => "Y",
	"DEFAULT" => "sort",
);

$arTemplateParameters["LINKED_ELEMENT_TAB_SORT_ORDER"] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_ORDER"),
	"TYPE" => "LIST",
	"VALUES" => $arAscDesc,
	"DEFAULT" => "asc",
	"ADDITIONAL_VALUES" => "Y",
);

$arTemplateParameters["LINKED_ELEMENT_TAB_SORT_FIELD2"] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_FIELD2"),
	"TYPE" => "LIST",
	"VALUES" => $arSort,
	"ADDITIONAL_VALUES" => "Y",
	"DEFAULT" => "id",
);

$arTemplateParameters["LINKED_ELEMENT_TAB_SORT_ORDER2"] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_ORDER2"),
	"TYPE" => "LIST",
	"VALUES" => $arAscDesc,
	"DEFAULT" => "desc",
	"ADDITIONAL_VALUES" => "Y",
);

$arTemplateParameters["SHOW_KIT_PARTS"] = array(
	"NAME" => GetMessage("SHOW_KIT_PARTS"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
	"REFRESH" => "N",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["SHOW_KIT_PARTS_PRICES"] = array(
	"NAME" => GetMessage("SHOW_KIT_PARTS_PRICES"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
	"REFRESH" => "N",
	"PARENT" => "DETAIL_SETTINGS",
);

$arTemplateParameters["STORES_FILTER"] = array(
	"NAME" => GetMessage("STORES_FILTER_TITLE"),
	"TYPE" => "LIST",
	"DEFAULT" => "TITLE",
	"VALUES" => array(
		"TITLE" => GetMessage("STORES_FILTER_NAME_TITLE"),
		"SORT" => GetMessage("STORES_FILTER_SORT_TITLE"),
		"AMOUNT" => GetMessage("STORES_FILTER_AMOUNT_TITLE"),
	),
	"PARENT" => "STORE_SETTINGS",
);

$arTemplateParameters["STORES_FILTER_ORDER"] = array(
	"NAME" => GetMessage("STORES_FILTER_ORDER_TITLE"),
	"TYPE" => "LIST",
	"DEFAULT" => "SORT_ASC",
	"VALUES" => array(
		"SORT_ASC" => GetMessage("STORES_FILTER_ORDER_ASC_TITLE"),
		"SORT_DESC" => GetMessage("STORES_FILTER_ORDER_DESC_TITLE"),
	),
	"PARENT" => "STORE_SETTINGS",
);

$arTemplateParameters['ADD_PICT_PROP'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_ADD_PICT_PROP'),
	'TYPE' => 'LIST',
	'MULTIPLE' => 'N',
	'ADDITIONAL_VALUES' => 'N',
	'REFRESH' => 'N',
	'DEFAULT' => '-',
	'VALUES' => $arProperty_F
);

$arTemplateParameters["SALE_STIKER"] = array(
	"PAREN" => "ADDITIONAL_SETTINGS",
	"NAME" => GetMessage("SALE_STIKER"),
	"TYPE" => "LIST",
	"VALUES" => array_merge(Array("-"=>" "), $arProperty_S),
	"DEFAULT" => "",
);

$arTemplateParameters["USE_COMPARE_GROUP"] = array(
	"PARENT" => "COMPARE_SETTINGS",
	"NAME" => GetMessage("T_USE_COMPARE_GROUP"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
);

if (Loader::includeModule("blog")) {
	$arTemplateParametersParts[] =  array(
		"MAX_IMAGE_SIZE" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("CP_BC_TPL_MAX_IMAGE_SIZE"),
			"TYPE" => "STRING",
			"DEFAULT" => "0.5"
		),
		"REVIEW_COMMENT_REQUIRED" => array(
			"NAME" => GetMessage("T_REVIEW_COMMENT_REQUIRED"),
			"PARENT" => "REVIEW_SETTINGS",
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"REVIEW_FILTER_BUTTONS" => array(
			"NAME" => GetMessage("T_REVIEW_FILTER_BUTTONS"),
			"TYPE" => "LIST",
			"DEFAULT" => array(),
			"PARENT" => "REVIEW_SETTINGS",
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"SIZE" => 3,
			"VALUES" => array(
				"PHOTO" => GetMessage("FILTER_BUTTONS_PHOTO"), 
				"RATING" => GetMessage("FILTER_BUTTONS_RATING"), 
				"TEXT" => GetMessage("FILTER_BUTTONS_TEXT"), 
			),
		),
		'REAL_CUSTOMER_TEXT' => array(
			"PARENT" => "REVIEW_SETTINGS",
			"DEFAULT" => "",
			"NAME"=> GetMessage("T_REAL_CUSTOMER_TEXT"),
			"TYPE" => "STRING",
		),
		'SHOW_REVIEW' => array(
			"PARENT" => "REVIEW_SETTINGS",
			"DEFAULT" => "Y",
			"NAME"=> GetMessage("T_SHOW_REVIEW"),
			"TYPE" => "CHECKBOX",
		),
	);
}

// merge parameters
foreach($arTemplateParametersParts as $i => $part){
	$arTemplateParameters = array_merge($arTemplateParameters, $part);
}
?>
