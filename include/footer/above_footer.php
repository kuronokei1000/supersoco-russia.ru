<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php if ($APPLICATION->GetProperty('viewed_show') === 'Y' || $is404): ?>
    <?php $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        [
            "COMPONENT_TEMPLATE"  => "",
            "PATH"                => SITE_DIR . "include/footer/catalog.viewed.php",
            "AREA_FILE_SHOW"      => "file",
            "AREA_FILE_SUFFIX"    => "",
            "AREA_FILE_RECURSIVE" => "Y",
            "EDIT_TEMPLATE"       => "standard.php",
        ],
        false
    ); ?>
<?php endif; ?>

<?php @include_once('above_footer_custom.php'); ?>