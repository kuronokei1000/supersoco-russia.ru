<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();

//options from \Aspro\Functions\CAsproLite::showBlockHtml
$arOptions = $arConfig['PARAMS'];
?>
<?if(
    $arOptions &&
    is_array($arOptions)
):?>
    <?
    $sMoreText = \Bitrix\Main\Config\Option::get(\TSolution::moduleID, 'EXPRESSION_READ_MORE_OFFERS_DEFAULT', GetMessage('TO_ALL'), SITE_ID);

    $arOptions['COLOR'] = $arOptions['COLOR'] === 'dark' ? : 'light';
    $arOptions['TEXT'] = is_array($arOptions['TEXT']) ? $arOptions['TEXT'] : array();
    $arOptions['BUTTONS'] = is_array($arOptions['BUTTONS']) ? $arOptions['BUTTONS'] : array();
    $arOptions['ATTR'] = is_array($arOptions['ATTR']) ? $arOptions['ATTR'] : array();
    ?>
    <div class="banners-big banners-big--detail swipeignore banners-big--nothigh banners-big--normal banners-big--adaptive-1">
        <div class="maxwidth-banner ">
            <div class="banners-big__wrapper">
                <div class="banners-big__item-wrapper">
                    <div class="box banners-big__item banners-big__depend-height banners-big__depend-padding banners-big__item--<?=$arOptions['COLOR']?>" style="background-image: url(<?=$arOptions['PICTURES']['BG']['SRC']?>); opacity: 1;" data-color="<?=$arOptions['COLOR']?>">
                        <div class="maxwidth-theme pos-static">
                            <div class="banners-big__inner">
                                <div class="banners-big__text banners-big__text--wide1  banners-big__depend-height animated delay06 duration08 fadeInUp">
                                    <?if($arOptions['TEXT']['TOP']):?>
                                        <div class="banners-big__top-text banners-big__top-text--small"><?=$arOptions['TEXT']['TOP']?></div>
                                    <?endif;?>

                                    <div class="banners-big__title banners-big__title--small">
                                        <h1 class="switcher-title" id="pagetitle"><?=htmlspecialcharsback($arOptions['TITLE'])?></h1>
                                    </div>

                                    <?if($arOptions['TEXT']['PREVIEW']):?>
                                        <div class="banners-big__text-wrapper ">
                                            <div class="banners-big__text-block banners-big__text-block--small banners-big__text-block--margin-top-more">
                                                <?if($arOptions['TEXT']['PREVIEW']['TYPE'] == 'text'):?>
                                                    <p><?=$arOptions['TEXT']['PREVIEW']['VALUE']?></p>
                                                <?else:?>
                                                    <?=$arOptions['TEXT']['PREVIEW']['VALUE']?>
                                                <?endif;?>
                                            </div>
                                        </div>
                                    <?endif;?>
                                    
                                    <?if($arOptions['BUTTONS']):?>
                                        <div class="banners-big__buttons ">
                                            <?foreach($arOptions['BUTTONS'] as $arButton):?>
                                                <?
                                                if(!is_array($arButton)){
                                                    continue;
                                                }
                                                $arButton['TITLE'] = strlen($arButton['TITLE']) ? $arButton['TITLE'] : $sMoreText;
                                                $arButton['CLASS'] = $arButton['CLASS'] ?? 'btn btn-default';
                                                $arButton['ATTR'] = is_array($arButton['ATTR']) ? $arButton['ATTR'] : array();
                                                ?>
                                                <div class="banners-big__buttons-item">
                                                    <? if( $arButton['TYPE'] === 'link' && $arButton['LINK'] ): ?>
                                                        <a 
                                                            href="<?= $arButton['LINK']; ?>"
                                                            class="btn-lg <?= $arButton['CLASS']; ?>"
                                                            <?= !empty($arButton['ATTR']) ? implode(' ', $arButton['ATTR']) : null; ?>
                                                        ><?= $arButton['TITLE']; ?></a>
                                                    <? else: ?>
                                                        <span
                                                            class="btn-lg <?= $arButton['CLASS']; ?>" 
                                                            <?= !empty($arButton['ATTR']) ? implode(' ', $arButton['ATTR']) : null; ?>
                                                        ><?= $arButton['TITLE']; ?></span>
                                                    <? endif; ?>
                                                </div>
                                            <?endforeach;?>
                                        </div>
                                    <?endif;?>
                                </div>
                                <div class="banners-big__img-wrapper banners-big__depend-height banners-big__img-wrapper--back-right animated delay09 duration08 fadeInUp">
                                    <img class="plaxy banners-big__img" src="<?=$arOptions['PICTURES']['IMG']['SRC']?>" alt="<?=htmlspecialchars($arOptions['ATTR']['ALT'])?>" title="<?=htmlspecialchars($arOptions['ATTR']['TITLE'])?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>