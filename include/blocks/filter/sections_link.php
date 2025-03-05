<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?$arOptions = $arConfig['PARAMS'];?>

<div class="head-block-wrapper dropdown-select">
    <div class="head-block-item visible-767 bordered rounded-4 dropdown-select__title">
        <span><?=($arOptions['SECTION'] ? $arOptions['SECTION']['NAME'] : GetMessage($arOptions['ALL_ITEMS_LANG']));?></span>
        <?=TSolution::showIconSvg("down menu-arrow bg-opacity-theme-target fill-theme-target", SITE_TEMPLATE_PATH.'/images/svg/Triangle_down.svg', '', '', true, false);?>
    </div>
    <div class="head-block bordered rounded-4 dropdown-select__list">
        <div class="line-block font_14 line-block--40">
            <?if ($arOptions['SECTION']):?>
                <a class="line-block__item head-block__item dark_link" href="<?=$arOptions['BASE_URL'];?>">
            <?else:?>
                <div class="line-block__item head-block__item head-block__item--active color-theme dark_link">
            <?endif;?>
                    <span class="head-block__item-text"><?=GetMessage($arOptions['ALL_ITEMS_LANG']);?></span>
            <?if ($arOptions['SECTION']):?>
                </a>
            <?else:?>
                </div>
            <?endif;?>
            <?foreach ($arOptions['ITEMS'] as $arItem):
                $bSelected = ($arOptions['SECTION'] && $arItem['ID'] == $arOptions['SECTION']['ID']);?>
                <?if ($bSelected):?>
                    <div class="line-block__item head-block__item head-block__item--active color-theme dark_link">
                <?else:?>
                    <a class="line-block__item head-block__item dark_link" href="<?=$arItem['SECTION_PAGE_URL'];?>"
                    >
                <?endif;?>
                        <span class="head-block__item-text"><?=$arItem['NAME'];?></span>
                <?if ($bSelected):?>
                    </div>
                <?else:?>
                    </a>
                <?endif;?>
            <?endforeach;?>
        </div>
    </div>
</div>