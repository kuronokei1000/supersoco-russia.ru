<?
$arItems = $arConfig['PARAMS']["ITEMS"];
//var_dump($arItems);
?>
<?if($arItems):?>
    <div class="bottom-icons-panel swipeignore">
        <div class="bottom-icons-panel__content <?=count($arItems) > 5 ? '' : 'flexbox--justify-beetwen' ?>">
            <?foreach($arItems as $arItem):?>
                
                <?if(strlen($arItem['DISPLAY_URL'])):?>
                    <a href="<?=htmlspecialcharsbx($arItem['DISPLAY_URL'])?>"
                <?else:?>
                    <span
                <?endif;?>
                    class="bottom-icons-panel__content-link fill-theme-parent<?=($arItem['PROPERTY_TYPE_VALUE'] ? ' bottom-icons-panel__content-link--'.$arItem['PROPERTY_TYPE_XML_ID'] : '')?><?=($arItem['IS_SELECTED'] ? ' bottom-icons-panel__content-link--active' : ' dark_link')?><?=($arItem['IS_BASKET'] ? ' basket' : '')?> bottom-icons-panel__content-link--with-counter" 
                    title="<?=htmlspecialcharsbx($arItem['DISPLAY_TITLE'])?>"
                    <?=($arItem['IS_REGION'] ? ' data-event="jqm" data-name="city_chooser" data-param-form_id="city_chooser"' : '')?>
                >
                    <?if($arItem['PROPERTY_IMG_VALUE'] || $arItem['SVG_FROM_SPRITE']):?>
                        <span class="icon-block-with-counter__inner fill-theme-hover fill-theme-target<?=($arItem['IS_SHOW_TEXT'] ? ' bottom-icons-panel__content-picture-wrapper--mb-3' : '')?><?=($arItem['IS_BASKET'] ? ' js-basket-block'.(!$arItem['COUNTER_COUNT'] ? ' header-cart__inner--empty' : '') : '')?>">
                            <?if(!$arItem['PROPERTY_IMG_VALUE'] && $arItem['SVG_FROM_SPRITE']):?>
                                <?=\TSolution::showSpriteIconSvg($arItem['SVG_FROM_SPRITE']['PATH'], "cat_icons fill-use-svg-888", ['WIDTH' => $arItem['SVG_FROM_SPRITE']['WIDTH'],'HEIGHT' => $arItem['SVG_FROM_SPRITE']['HEIGHT']]);?>
                            <?else:?>
                                <?
                                $arImg = \CFile::ResizeImageGet($arItem['PROPERTY_IMG_VALUE'], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);?>
                                <?if(is_array($arImg)):?>
                                    <?if(strpos($arImg["src"], ".svg") !== false):?>
                                        <?=\TSolution::showIconSvg("cat_icons light-ignore", $arImg["src"]);?>
                                    <?else:?>
                                        <img class="bottom-icons-panel__content-picture lazyload" src="<?=$arImg["src"]?>" data-src="<?=$arImg["src"]?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
                                    <?endif;?>
                                <?endif;?>
                            <?endif;?>
                            <?if($arItem['IS_BASKET']):?>
                                <span class="header-cart__count bg-more-theme count<?=(!$arItem['COUNTER_COUNT'] ? ' empted' : '')?>"><?=$arItem['COUNTER_COUNT']?></span>
                            <?elseif($arItem['IS_COUNTER']):?>
                                <span class="<?= $arItem['WRAP_COUNTER_CLASS']; ?>">
                                    <span class="icon-count bg-more-theme count<?= $arItem['COUNTER_CLASS']; ?>"><?=$arItem['COUNTER_COUNT']?></span>
                                </span>
                            <?endif;?>
                        </span>
                    <?endif;?>
                    <?if($arItem['IS_SHOW_TEXT']):?>
                        <span class="bottom-icons-panel__content-text font_10 bottom-icons-panel__content-link--display--block"><?=$arItem['NAME'];?></span>
                    <?endif;?>
                <?if(strlen($arItem['DISPLAY_URL'])):?>
                    </a>
                <?else:?>
                    </span>
                <?endif;?>
            <?endforeach;?>
        </div>
    </div>
<?endif;?>