<?
//options from \Aspro\Functions\CAsproLite::showBlockHtml
$arOptions = $arConfig['PARAMS'];

$itemsCount = $arOptions['TOTAL_COUNT'];
?>
<div class="gallery-big <?= $arOptions['CONFIG']['CONTAINER_CLASS']; ?> grid-list--gap-12"
    data-additional_items='<?= $arOptions['DATA_ITEMS']; ?>'
    <? foreach ($arOptions['CONFIG']['BREAKPOINTS'] as $breakpoint => $count): ?>
        <? $itemsLeft = $itemsCount - $count; ?>
        <? if ($itemsLeft > 0): ?>
            data-<?= $breakpoint; ?>="<?= $itemsLeft; ?>"
        <? endif; ?>
    <? endforeach; ?>
>
    <?foreach($arOptions['ITEMS'] as $arPhoto):?>
        <div class="item fancy-plus <?= $arOptions['CONFIG']['ITEM_CLASS']; ?> grid-list__item">
            <a href="<?=$arPhoto['src']?>" class="flexbox" target="_blank" title="<?=$arPhoto['title']?>">
                <img src="<?=$arPhoto['preview']?>" class="img-responsive inline lazy outer-rounded-x" title="<?=$arPhoto['title']?>" alt="<?=$arPhoto['alt']?>" />
            </a>
        </div>
    <?endforeach;?>
</div>