<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?$arOptions = $arConfig['PARAMS'];?>

<div class="tabs arrow_scroll tabs--in-section">
    <ul class="nav nav-tabs font_14 font_weight--600">
        <li class="bordered rounded-4 active">
            <a href="#all" data-toggle="tab" data-filter="all"><?=GetMessage($arOptions['ALL_ITEMS_LANG']);?></a>
        </li>
        <?foreach ($arOptions['ITEMS'] as $value):?>
            <li class="bordered rounded-4 ">
                <a href="#d-<?=$value?>" data-toggle="tab" data-filter=".d-<?=$value?>">
                    <?=$value?>
                </a>
            </li>
        <?endforeach;?>
    </ul>
</div>