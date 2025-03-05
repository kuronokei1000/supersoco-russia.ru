<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?$arOptions = $arConfig['PARAMS'];?>
<div class="drag-scroll header__top-sections-inner scroll-header-tags">
    <div class="drag-scroll__content-wrap">
        <div class="drag-scroll__content line-block line-block--gap line-block--gap-32">
            <?/* add draggable="false" to link for work in firefox*/?>
            <?foreach ($arOptions['SECTIONS'] as $arSection):?>
                <a class="line-block__item banner-light-text light-opacity-hover dark_link no-shrinked" href="<?=$arSection["UF_MEGA_MENU_LINK"]?>" draggable="false">
                    <span class="font_15"><?=$arSection["NAME"]?></span>
                </a>
            <?endforeach;?>
        </div>
    </div>
</div>
<?
$arExtensions = ['drag_scroll'];
TSolution\Extensions::init($arExtensions);
?>
<script>new JSDragScroll('.scroll-header-tags');</script>