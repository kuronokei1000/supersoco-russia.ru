<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?$arOptions = $arConfig['PARAMS'];?>

<ul class="nav nav-list side-menu">
    <li class="<?=($arOptions["ALL_ARTICLES_ITEM"]['CURRENT'] ? 'active opened child' : '')?><?=($arOptions["ALL_ARTICLES_ITEM"]['CHILD'] ? ' child' : '')?>">
        <span class="bg-opacity-theme-parent-hover link-wrapper">
            <a href="<?=$arOptions["ALL_ARTICLES_ITEM"]['LINK'];?>" class="dark_link top-level-link rounded-x font_short link-with-flag color-theme-parent-all <?=($arOptions["ALL_ARTICLES_ITEM"]['CURRENT'] && $arOptions["ALL_ARTICLES_ITEM"]["IS_PARENT"] ? ' link--active' : '')?>">
                <span class="side-menu__link-text"><?=$arOptions["ALL_ARTICLES_ITEM"]['TEXT'];?></span>
                <?if ($arOptions["ALL_ARTICLES_ITEM"]['ELEMENT_COUNT']):?>
                    <span class="side-menu__link-count color_999 color-theme-target"><?=$arOptions["ALL_ARTICLES_ITEM"]['ELEMENT_COUNT'];?></span>
                <?endif;?>
            </a>
        </span>
        <div class="submenu-wrapper">
            <ul class="submenu">
                <?foreach ($arOptions['SECTIONS'] as $arSection):?>
                    <?if (isset($arSection['TEXT']) && $arSection['TEXT']):?>
                        <li class="<?=($arSection['CURRENT'] ? 'active opened' : '')?><?=($arSection['CHILD'] ? ' child' : '')?>">
                            <span class="bg-opacity-theme-parent-hover link-wrapper">
                                <a href="<?=$arSection['LINK'];?>" class="dark_link top-level-link rounded-x font_short link-with-flag color-theme-parent-all <?=($arSection['CURRENT'] ? ' link--active' : '')?>">
                                    <span class="side-menu__link-text"><?=$arSection['TEXT'];?></span>
                                    <?if ($arSection['ELEMENT_COUNT']):?>
                                        <span class="side-menu__link-count color_999 color-theme-target"><?=$arSection['ELEMENT_COUNT'];?></span>
                                    <?endif;?>
                                    <?if ($arSection['CHILD']):?>
                                        <?=TSolution::showIconSvg("down menu-arrow bg-opacity-theme-target fill-theme-target", SITE_TEMPLATE_PATH.'/images/svg/Triangle_down.svg', '', '', true, false);?>
                                    <?endif;?>
                                </a>
                                <?if ($arSection['CHILD']):?>
                                    <span class="toggle_block"></span>
                                <?endif;?>
                            </span>
                            <?if ($arSection['CHILD']):?>
                                <div class="submenu-wrapper">
                                    <ul class="submenu">
                                        <?foreach ($arSection['CHILD'] as $arChild):?>
                                            <li class="<?=($arChild['CURRENT'] ? 'active' : '')?>">
                                                <span class="bg-opacity-theme-parent-hover link-wrapper">
                                                    <a href="<?=$arChild['LINK'];?>" class="dark_link font_short sublink color-theme-parent-all<?=($arChild['CURRENT'] ? ' link--active' : '')?>">
                                                        <?=$arChild['TEXT'];?>
                                                    </a>
                                                </span>
                                            </li>
                                        <?endforeach;?>
                                    </ul>
                                </div>
                            <?endif;?>
                        </li>
                    <?endif;?>
                <?endforeach;?>
            </ul>
        </div>   
    </li>
</ul>