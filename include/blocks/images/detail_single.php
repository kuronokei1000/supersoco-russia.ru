<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

//options from \Aspro\Functions\CAsproLite::showBlockHtml
$arOptions = $arConfig['PARAMS'];

if ($arOptions && is_array($arOptions)) :
    $type = $arOptions['TYPE'] ?: 'wide';
    $bTopContent = $type == 'TOP_CONTENT';
    $bTopOnHead = $type == 'TOP_ON_HEAD'; ?>
<div class="detail-image detail-image--<?=strtolower($type)?>">
    <?php if (!$arOptions['TOP_IMG']): ?>
        <a href="<?=$arOptions['URL']?>" class="fancybox" title="<?=$arOptions['TITLE']?>">
    <?php elseif ($bTopContent): ?>
        <div class="maxwidth-theme">
    <?php endif; ?>

    <?php if ($bTopOnHead): ?>
        <div class="detail-image__fon" style="background: url(<?=$arOptions['URL']?>) center/cover no-repeat;"></div>
    <?php else:
    // 04.07.2024 [Roman Brovin] Добавил loading="lazy"
    // 19.07.2024 [Roman Brovin] Добавил класс lazyload"
    ?>
        <img loading="lazy" src="<?=$arOptions['URL']?>" data-src="<?=$arOptions['URL']?>"
             class="img-responsive outer-rounded-x lazyload" title="<?=$arOptions['TITLE']?>"
             alt="<?=$arOptions['ALT']?>"/>
    <?php endif; ?>

    <?php if (!$arOptions['TOP_IMG']): ?>
        </a>
    <?php elseif ($bTopContent): ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>