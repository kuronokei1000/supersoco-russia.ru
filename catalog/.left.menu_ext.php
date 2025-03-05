<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$aMenuLinksExt = [];

if ($arMenuParametrs = TSolution::GetDirMenuParametrs(__DIR__)) {
    $iblock_id = \Bitrix\Main\Config\Option::get(
        VENDOR_MODULE_ID,
        'CATALOG_IBLOCK_ID',
        TSolution\Cache::$arIBlocks[SITE_ID][VENDOR_PARTNER_NAME . '_' . VENDOR_SOLUTION_NAME . '_catalog'][VENDOR_PARTNER_NAME . '_' . VENDOR_SOLUTION_NAME . '_catalog'][0]
    );
    $arExtParams = [
        'IBLOCK_ID'      => $iblock_id,
        'MENU_PARAMS'    => $arMenuParametrs,
        'SECTION_FILTER' => [],    // custom filter for sections (through array_merge)
        'SECTION_SELECT' => [],    // custom select for sections (through array_merge)
        'ELEMENT_FILTER' => [],    // custom filter for elements (through array_merge)
        'ELEMENT_SELECT' => [],    // custom select for elements (through array_merge)
        'MENU_TYPE'      => 'catalog',
    ];
    TSolution::getMenuChildsExt($arExtParams, $aMenuLinksExt);
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>