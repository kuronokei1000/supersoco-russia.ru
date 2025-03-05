<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager,
	Bitrix\Main\Web\Json,
	Bitrix\Iblock,
	Bitrix\Main\Localization\Loc,
	Aspro\Lite\Functions\ExtComponentParameter;

if (!Loader::includeModule('iblock')) {
	return;
}

$arSKU = $boolSKU = false;
$arPropertySort = $arPropertySortDefault = $arPropertyDefaultSort = array();
$arPrice = $arProperty = $arProperty_N = $arProperty_X = $arProperty_F = array();
$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);
$arPropertySortDefault = array("SORT", "SHOWS", "NAME");
$arPropertySort = array(
	"SORT"=>GetMessage("SORT_BUTTONS_SORT"),
	"SHOWS"=>GetMessage("SORT_BUTTONS_POPULARITY"),
	"NAME"=>GetMessage("SORT_BUTTONS_NAME"),
	// "CUSTOM"=>GetMessage("SORT_BUTTONS_CUSTOM")
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
		'=IBLOCK_ID' => $arCurrentValues['LINK_GOODS_IBLOCK_ID'],
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

$arIBlocks = [];
$rsIBlock = CIBlock::GetList(
	[
		'ID' => 'ASC'
	],
	[
		// 'TYPE' => $arCurrentValues['IBLOCK_TYPE'], 
		'ACTIVE' => 'Y'
	]
);
while ($arIBlock = $rsIBlock->Fetch()) {
	$arIBlocks[$arIBlock['ID']] = "[{$arIBlock['ID']}] {$arIBlock['NAME']}";
}

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

$arAscDesc = array(
	'asc' => GetMessage('IBLOCK_SORT_ASC'),
	'desc' => GetMessage('IBLOCK_SORT_DESC'),
);

$arRegionPrice = $arPrice;
if (Loader::includeModule("catalog")) {
	$arPriceSort  = array_merge(array("MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE"), "REGION_PRICE"=>GetMessage("SORT_PRICES_REGION_PRICE")), $arPrice);
}

ExtComponentParameter::init(__DIR__, $arCurrentValues);

ExtComponentParameter::addBaseParameters(array(
	array(
		array('SECTION' => 'SECTION', 'OPTION' => 'BRANDS_PAGE'),
		'SECTION_ELEMENTS_TYPE_VIEW'
	),
	array(
		array('SECTION' => 'SECTION', 'OPTION' => 'BRANDS_DETAIL_PAGE'),
		'ELEMENT_TYPE_VIEW'
	),
));

ExtComponentParameter::addRelationBlockParameters([
	ExtComponentParameter::RELATION_BLOCK_DOCS,
	ExtComponentParameter::RELATION_BLOCK_LINK_GOODS,
	ExtComponentParameter::RELATION_BLOCK_LINK_SECTIONS,
	ExtComponentParameter::RELATION_BLOCK_COMMENTS,
]);

ExtComponentParameter::addTextParameter('DEPTH_LEVEL_BRAND', [
	"NAME" => GetMessage('T_DEPTH_LEVEL_BRAND'),
	"DEFAULT" => 2
]);

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
ExtComponentParameter::addSelectParameter('SECTION_LIST_DISPLAY_TYPE', [
	'VALUES' => [
		3 => GetMessage("V_SECTION_LIST_DISPLAY_TYPE_BIG"),
		4 => GetMessage("V_SECTION_LIST_DISPLAY_TYPE_SMALL")
	],
	"NAME" => GetMessage('T_SECTION_LIST_DISPLAY_TYPE'),
	"DEFAULT" => 3
]);

ExtComponentParameter::appendTo($arTemplateParameters);

$arTemplateParameters['SHOW_DETAIL_LINK'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => Loc::getMessage('SHOW_DETAIL_LINK'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters['USE_SHARE'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => Loc::getMessage('USE_SHARE'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters['SORT_PROP'] = array(
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DETAIL,
	'NAME' => GetMessage('T_SORT_PROP'),
	'TYPE' => 'LIST',
'VALUES' => array_merge(array(/*"CUSTOM"=>GetMessage("SORT_BUTTONS_CUSTOM")*/), $arPropertySort),
	"DEFAULT" => $arPropertySortDefault,
	'SIZE' => 5,
	'MULTIPLE' => 'Y',
	'REFRESH' => 'Y'
);

$arTemplateParameters['SORT_PROP_DEFAULT'] = array(
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DETAIL,
	'NAME' => GetMessage('T_SORT_PROP_DEFAULT'),
	'TYPE' => 'LIST',
	'VALUES' => $arPropertyDefaultSort,
);

$arTemplateParameters['SORT_DIRECTION'] = array(
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DETAIL,
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
			"VALUES" => $arPriceSort,
			"DEFAULT" => array("MINIMUM_PRICE"),
			"PARENT" => "DETAIL_SETTINGS",
			"MULTIPLE" => "N",
		);
		$arTemplateParameters["SORT_REGION_PRICE"] = Array(
			"SORT"=>200,
			"NAME" => GetMessage("SORT_REGION_PRICE"),
			"TYPE" => "LIST",
			"VALUES" => $arRegionPrice,
			"DEFAULT" => array("BASE"),
			"PARENT" => "DETAIL_SETTINGS",
			"MULTIPLE" => "N",
		);
	}
}

$arTemplateParameters["VIEW_TYPE"] = array(
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DETAIL,
	"NAME" => GetMessage("DEFAULT_LIST_TEMPLATE"),
	"TYPE" => "LIST",
	"VALUES" => array(
		"table" => GetMessage("DEFAULT_LIST_TEMPLATE_BLOCK"),
		"list" => GetMessage("DEFAULT_LIST_TEMPLATE_LIST"),
		"price" => GetMessage("DEFAULT_LIST_TEMPLATE_TABLE")),
	"DEFAULT" => "table",
);

if (\Bitrix\Main\Loader::includeModule('catalog')) {

	$arTemplateParameters['PRICE_CODE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('PRICE_CODE_TITLE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $arPrice,
		'ADDITIONAL_VALUES' => 'Y'
	);
}

$arTemplateParameters['PRICE_VAT_INCLUDE'] = array(
	"PARENT" => "DETAIL_SETTINGS",
	"NAME" => GetMessage("IBLOCK_PRICE_VAT_INCLUDE"),
	"TYPE" => "CHECKBOX",
	"REFRESH" => "N",
	"DEFAULT" => "Y",
);

/* check for custom option */
$siteID = SITE_ID;
if (isset($_REQUEST['src_site'])) {
	$siteID = $_REQUEST['src_site'];
}
$viewTemplate = $arCurrentValues["SECTION_ELEMENTS_TYPE_VIEW"];

if ($viewTemplate === 'FROM_MODULE') {
	if (isset($_SESSION) 
		&& isset($_SESSION['THEME'])
		&& isset($_SESSION['THEME'][$siteID])
		&& isset($_SESSION['THEME'][$siteID]['BRANDS_PAGE'])
	) {
		$viewTemplate = $_SESSION['THEME'][$siteID]['BRANDS_PAGE'];
	} else {
		$viewTemplate = \Bitrix\Main\Config\Option::get(CLite::moduleID, 'BRANDS_PAGE', '', $siteID);
	}
}
if(strpos($viewTemplate, 'with_group') !== false){
	$arTemplateParameters["USE_AGENT"] = Array(
		"NAME" => GetMessage("T_USE_AGENT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"PARENT" => "LIST_SETTINGS",
	);
}
?>