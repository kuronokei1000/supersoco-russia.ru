<?
use \Bitrix\Main\Localization\Loc;

$bTab = isset($tabCode) && $tabCode === 'docs';
?>
<?//show docs block?>
<?if($templateData['DOCUMENTS']):?>
    <?if($bTab):?>
        <?if(!isset($bShow_docs)):?>
            <?$bShow_docs = true;?>
        <?else:?>
            <div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="docs">
                <?$APPLICATION->ShowViewContent('PRODUCT_FILES_INFO')?>
            </div>
        <?endif;?>
    <?else:?>
        <div class="grid-list__item detail-block ordered-block docs">
            <h3 class="switcher-title"><?=$arParams["T_DOCS"]?></h3>
            <?$APPLICATION->ShowViewContent('PRODUCT_FILES_INFO')?>
        </div>
    <?endif;?>
    <?
    TSolution\Extensions::init(['docs']);
    ?>
<?endif;?>