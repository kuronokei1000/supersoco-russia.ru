<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?
    $arDefaultOptions = [
        'FONT' => 14,
    ];
    $arOptions = array_merge($arDefaultOptions, $arConfig['PARAMS']);

    $bHasCurrentItem = (isset($arOptions['VALUE']) && $arOptions['VALUE']);
?>
<div class="line-block__item sku-props__inner" <?=$arOptions['STYLE']?> data-id="<?=$arOptions['ID']?>">
    <div class="sku-props__item">
        <?if (isset($arOptions['NAME']) && $arOptions['NAME']):?>
            <div class="sku-props__title color_666">
                <?=$arOptions['NAME']?>
                <?if (TSolution::GetFrontParametrValue('SHOW_HINTS') !== 'N' && $arOptions['HINT']):?>
                    <div class="hint hint--down"><span class="hint__icon rounded bg-theme-hover border-theme-hover bordered"><i>?</i></span><div class="tooltip"><?=$arOptions['HINT']?></div></div>
                <?endif;?>
                : <span class="sku-props__js-size"><?=($bHasCurrentItem ? $arOptions['VALUE'] : "&mdash;");?></span>
            </div>
        <?endif;?>
        <div class="line-block line-block--flex-wrap line-block--4 sku-props__values ">
            <?foreach ((array)$arOptions['VALUES'] as $key => $arItem):?>
                <div class="line-block__item" <?=$arItem['STYLE']?>>
                    <div class="sku-props__value font_<?= $arOptions['FONT']; ?> <?=($arItem['CLASS'] === 'active' ? 'sku-props__value--active' : '');?>" data-onevalue="<?=$arItem['ID'];?>" data-title="<?=$arItem['NAME']?>"><?=$arItem['NAME']?></div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>
