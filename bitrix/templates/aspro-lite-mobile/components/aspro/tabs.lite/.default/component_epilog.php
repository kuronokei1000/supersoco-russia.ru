<?$arScripts = ['index_tabs']?>
<?if (count($arResult["TABS"]) > 1) {
    $arScripts[] = 'chip';
    $arScripts[] = 'tabs';
}?>
<?TSolution\Extensions::init($arScripts);?>