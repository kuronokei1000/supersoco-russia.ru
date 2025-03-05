<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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

$arFromTheme = [];
/* check for custom option */
if (isset($_REQUEST['src_path'])) {
	$_SESSION['src_path_component'] = $_REQUEST['src_path'];
}
if (strpos($_SESSION['src_path_component'], 'custom') === false) {
	$arFromTheme = ["FROM_THEME" => GetMessage("T_FROM_THEME")];
}

$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);
$arAscDesc = array(
	'asc' => GetMessage('IBLOCK_SORT_ASC'),
	'desc' => GetMessage('IBLOCK_SORT_DESC'),
);

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

ExtComponentParameter::init(__DIR__, $arCurrentValues);

$arTemplateFiles = file_exists(__DIR__."/page_blocks") 
	? array_diff(scandir(__DIR__."/page_blocks"), ['..', '.']) 
	: false;

$arComponentTemplates = [];
if ($arTemplateFiles) {
	foreach ($arTemplateFiles as $key => $value) {
		$value = str_replace('.php', '', $value);
		$langName = 'V_TYPE_TEMPLATE_'.strtoupper(str_replace('-', '_', $value));
		$langValue = GetMessage($langName);

		$arComponentTemplates[$value] = $langValue ?  $langValue." (".$value.")" : $value;
	}
}

ExtComponentParameter::addSelectParameter('SKU_SORT_FIELD', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DATA_SOURCE,
	'VALUES' => $arSort,
	'DEFAULT' => 'name',
	'ADDITIONAL_VALUES' => 'Y',
	'SORT' => 999
]);

ExtComponentParameter::addSelectParameter('SKU_SORT_ORDER', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DATA_SOURCE,
	'VALUES' => $arAscDesc,
	'SORT' => 999
]);

ExtComponentParameter::addSelectParameter('SKU_SORT_FIELD2', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DATA_SOURCE,
	'VALUES' => $arSort,
	'ADDITIONAL_VALUES' => 'Y',
	'DEFAULT' => 'sort',
	'SORT' => 999
]);

ExtComponentParameter::addSelectParameter('SKU_SORT_ORDER2', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_DATA_SOURCE,
	'VALUES' => $arAscDesc,
	'SORT' => 999
]);

if (count($arComponentTemplates) > 1) {
	ExtComponentParameter::addSelectParameter('TYPE_TEMPLATE', [
		'PARENT' => ExtComponentParameter::PARENT_GROUP_BASE,
		'VALUES' => $arComponentTemplates,
		'DEFAULT' => $arComponentTemplates ? $arComponentTemplates[array_keys($arComponentTemplates)[0]] : '',
		'REFRESH' => 'Y',
		'SORT' => 999
	]);
}

ExtComponentParameter::appendTo($arTemplateParameters);

$arTemplateParameters["PAGE_ELEMENT_COUNT"] = array(
	"NAME" => GetMessage("T_PAGE_ELEMENT_COUNT"),
	"TYPE" => "LIST",
	"VALUES" => $arFromTheme,
	"ADDITIONAL_VALUES" => "Y",
	"DEFAULT" => "",
	"PARENT" => "BASE",
);

if (strpos($arCurrentValues["TYPE_TEMPLATE"], 'catalog_block') !== false || !$arCurrentValues["TYPE_TEMPLATE"]) {
	$arTemplateParameters += array(
		"BORDERED" => Array(
			"NAME" => GetMessage("T_BORDERED"),
			"TYPE" => "LIST",
			"VALUES" => array_merge(
				$arFromTheme,
				[
					"Y" => GetMessage("T_YES"),
					"N" => GetMessage("T_NO"),
				],
			),
			"DEFAULT" => "Y",
		),
		"IMG_CORNER" => Array(
			"NAME" => GetMessage("T_IMG_CORNER"),
			"TYPE" => "LIST",
			"VALUES" => array_merge(
				$arFromTheme,
				[
					"Y" => GetMessage("T_YES"),
					"N" => GetMessage("T_NO"),
				],
			),
			"DEFAULT" => "N",
		),
		"ELEMENT_IN_ROW" => Array(
			"NAME" => GetMessage("T_ELEMENT_IN_ROW"),
			"TYPE" => "LIST",
			"VALUES" => 
				$arFromTheme + 
				[
					"3" => GetMessage("V_ELEMENT_IN_ROW_3"),
					"4" => GetMessage("V_ELEMENT_IN_ROW_4"),
					"5" => GetMessage("V_ELEMENT_IN_ROW_5"),
				],
			"DEFAULT" => "4",
		),
		"COUNT_ROWS" => Array(
			"NAME" => GetMessage("T_COUNT_ROWS"),
			"TYPE" => "LIST",
			"VALUES" => 
				$arFromTheme + 
				[
					"1" => GetMessage("V_COUNT_ROWS_1"),
					"2" => GetMessage("V_COUNT_ROWS_2"),
					"3" => GetMessage("V_COUNT_ROWS_3"),
					"SHOW_MORE" => GetMessage("V_COUNT_ROWS_SHOW_MORE"),
				],
			"DEFAULT" => "1",
		),
	);
}
