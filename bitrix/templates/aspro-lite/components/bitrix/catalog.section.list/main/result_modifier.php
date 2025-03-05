<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
if (is_array($arParams['THEME'])) {
    if ($arParams['BORDERED'] == 'FROM_THEME') {
        $arParams['BORDERED'] = $arParams['THEME']['BORDERED'] ?? $arParams['BORDERED'];
    }
    if ($arParams['ELEMENTS_IN_ROW'] == 'FROM_THEME') {
        $arParams['ELEMENTS_IN_ROW'] = $arParams['THEME']['ELEMENTS_IN_ROW'] ?? $arParams['ELEMENTS_IN_ROW'];
    }
    if ($arParams['LINES_COUNT'] == 'FROM_THEME') {
        $arParams['LINES_COUNT'] = $arParams['THEME']['LINES_COUNT'] ?? $arParams['LINES_COUNT'];
    }
    if ($arParams['IMAGES'] == 'FROM_THEME') {
        $arParams['IMAGES'] = $arParams['THEME']['IMAGES'] ?? $arParams['IMAGES'];
    }
}
if ($arResult['SECTIONS']) {
    if ($arParams['LINES_COUNT'] && $arParams['LINES_COUNT'] != 'ALL' && $arParams['ELEMENTS_IN_ROW']) {
        array_splice($arResult['SECTIONS'], $arParams['LINES_COUNT'] * $arParams['ELEMENTS_IN_ROW']);
    }
}
?>