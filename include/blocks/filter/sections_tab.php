<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?$arOptions = $arConfig['PARAMS'];?>

<div class="tabs arrow_scroll tabs--in-section">
    <ul class="nav nav-tabs font_14 font_weight--600">
        <li class="bordered rounded-4 active">
            <a href="#all" data-toggle="tab" data-filter="all"><?=GetMessage($arOptions['ALL_ITEMS_LANG']);?></a>
        </li>
        <?foreach ($arOptions['ITEMS'] as $arItem):?>
            <li class="bordered rounded-4 ">
                <a href="#s-<?=$arItem['ID']?>" data-toggle="tab" data-filter=".s-<?=$arItem['ID']?>">
                    <?=$arItem['NAME']?>
                </a>
            </li>
        <?endforeach;?>
    </ul>
</div>