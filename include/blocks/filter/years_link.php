<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();

//options from \Aspro\Functions\CAsproLite::showBlockHtml

$arOptions = $arConfig['PARAMS'];
?>
<div class="head-block-wrapper">
    <div class="tabs">
        <ul class="nav nav-tabs">
            <?if($arOptions["ARCHIVE_TAB"]):?>
                <li class="head-block__item dark_link">
                    <a href="<?=$GLOBALS['APPLICATION']->GetCurPageParam('', array('check_dates', 'is_aspro_mobile'));?>">
            <?else:?>
                <li class="head-block__item head-block__item--active active dark_link">
            <?endif;?>
                    <span><?=$arOptions['ALL_ITEMS_LANG'];?></span>
            <?if($arOptions["ARCHIVE_TAB"]):?>
                    </a>
                </li>
                <?if($arOptions["SHOW_ARCHICE"]):?>
                    <li class="head-block__item dark_link head-block__item--active active">
                        <span><?=$arOptions['ARCHIVE_ITEMS_LANG'];?></span>
                    </li>
                <?endif;?>
            <?else:?>
                </li>
                <?if($arOptions["SHOW_ARCHICE"]):?>
                    <li class="head-block__item dark_link">
                        <a href="<?=$GLOBALS['APPLICATION']->GetCurPageParam('check_dates=N', array('check_dates', 'is_aspro_mobile'));?>">
                            <?=$arOptions['ARCHIVE_ITEMS_LANG'];?>
                        </a>
                    </li>
                <?endif;?>
            <?endif;?>
        </ul>
    </div>
</div>