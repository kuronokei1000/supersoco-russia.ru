<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParams = $arParams;
$arGlobalFilter = ($bAjax ? TSolution::unserialize(urldecode($request->getPost("GLOBAL_FILTER"))) : ($request->getQuery('GLOBAL_FILTER') ? TSolution::unserialize(urldecode($request->getQuery('GLOBAL_FILTER'))) : array()));
// $template = ($bAjax ? $request->getPost("TYPE_TEMPLATE") : $arComponentParams['TYPE_TEMPLATE']);

if ($request['GLOBAL_FILTER']) {
    $GLOBALS[$arComponentParams['FILTER_NAME']] = TSolution::unserialize(urldecode($request['GLOBAL_FILTER']));
}

if (is_array($arGlobalFilter) && $arGlobalFilter) {
    $GLOBALS[$arComponentParams["FILTER_NAME"]] = $arGlobalFilter;
}

if ($bAjax && $request->getPost("FILTER_HIT_PROP")) {
    $arComponentParams["FILTER_HIT_PROP"] = $request->getPost("FILTER_HIT_PROP");
}

/* hide compare link from module options */
if (TSolution::GetFrontParametrValue('CATALOG_COMPARE') === 'N') {
    $arComponentParams["DISPLAY_COMPARE"] = 'N';
}

if ($bAjax && $request['AJAX_POST'] !== 'Y') {
    $arComponentParams['AJAX_REQUEST'] = 'Y';
}

$arRegion = TSolution\Regionality::getCurrentRegion();

$arComponentParams['PRICE_CODE'] = explode(',', TSolution::GetFrontParametrValue('PRICES_TYPE'));
$arComponentParams['USE_PRICE_COUNT'] = 'N';
$arComponentParams["STORES"] = explode(',', TSolution::GetFrontParametrValue('STORES'));

$arComponentParams['ADD_PICT_PROP'] = TSolution::GetFrontParametrValue('GALLERY_PROPERTY_CODE');
$arComponentParams['OFFER_ADD_PICT_PROP'] = TSolution::GetFrontParametrValue('GALLERY_PROPERTY_CODE');
$arComponentParams['TYPE_SKU'] = TSolution::GetFrontParametrValue('CATALOG_PAGE_DETAIL_SKU');

if ($arRegion) {
    if ($arRegion['LIST_PRICES'] && reset($arRegion['LIST_PRICES']) !== 'component') {
        $arComponentParams["PRICE_CODE"] = array_keys($arRegion['LIST_PRICES']);
    }
    if ($arRegion['LIST_STORES'] && reset($arRegion['LIST_STORES']) !== 'component') {
        $arComponentParams["STORES"] = $arRegion['LIST_STORES'];
    }
    $arComponentParams["USE_REGION"] = 'Y';
}

//set params for props from module
TSolution\Functions::replacePropsParams($arComponentParams, ['PROPERTY_CODE' => 'PROPERTY_CODE']);

$arComponentParams["OFFER_TREE_PROPS"] = $arComponentParams['SKU_TREE_PROPS'];
$arComponentParams["OFFERS_PROPERTY_CODE"] = $arComponentParams['SKU_PROPERTY_CODE'];
$arComponentParams["OFFERS_FIELD_CODE"] = ['ID', 'NAME'];
$arComponentParams['SHOW_ALL_WO_SECTION'] = 'Y';

$arComponentParams = array_merge($arComponentParams, [
    'COMPONENT_TEMPLATE' => 'catalog_block',
    'SECTION_ID' => $GLOBALS[$arComponentParams["FILTER_NAME"]]['SECTION_ID'],
]);

$APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    'catalog_block',
    $arComponentParams,
    false, 
    [
        "HIDE_ICONS" => "Y"
    ]
);
