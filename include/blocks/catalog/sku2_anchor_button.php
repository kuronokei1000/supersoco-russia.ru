<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?//$arOptions from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?
$arOptions = $arConfig['PARAMS'];
?>
<span class="choise btn btn-default btn-actions__inner btn-wide <?= $arOptions['BTN_CLASS_MORE']; ?> js-replace-more" data-block="<?= $arOptions['BLOCK']; ?>">
    <?= $arOptions['BTN_NAME']; ?>
</span>