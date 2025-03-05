<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?
$arOptions = (array)$arConfig['PARAMS'];

if (!isset($arOptions['MESSAGE']) || !$arOptions['MESSAGE']) return;
?>
<div class="video_body">
    <div class="video_block__error flexbox flexbox--justify-center rounded-4">
        <?=$arOptions['MESSAGE'];?>
    </div>
</div>