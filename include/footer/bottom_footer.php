<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?$APPLICATION->IncludeComponent(
    "aspro:theme.lite", 
    ".default", 
    array(
        'SHOW_TEMPLATE' => 'Y'
    ),
    false, array('HIDE_ICONS' => 'Y')
);?>

<?@include_once('bottom_footer_custom.php');?>