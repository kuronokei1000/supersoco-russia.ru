<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?//$arOptions from \Aspro\Functions\CAsproLite::showBlockHtml?>
<a class="back-url font_short stroke-dark-light-block dark_link" href="<?=$arOptions["URL"]?>">
    <span class="back-url-icon">
        <?=\TSolution::showSpriteIconSvg( SITE_TEMPLATE_PATH.'/images/svg/arrows.svg#left-18-12', ' stroke-dark-light arrow-all__item-arrow', ['WIDTH' => 18, 'HEIGHT' => 12]);?>
    </span>
    <span class="back-url-text"><?=$arOptions['TEXT']?></span>
</a>