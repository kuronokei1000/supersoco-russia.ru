<?
use \Bitrix\Main\Localization\Loc;

$bTab = isset($tabCode) && $tabCode === 'delivery';
?>
<?//show delivery block?>
<?if($arParams["SHOW_DELIVERY"] === 'Y'):?>
    <?
        if (!isset($html_delivery)) {
            ob_start();
            $APPLICATION->IncludeFile($templateData['INCLUDE_FOLDER_PATH']."/index_delivery.php", array(), array("MODE" => "html", "NAME" => GetMessage('T_DELIVERY')));
            $html_delivery = trim(ob_get_clean());
        }
    ?>
    <? if($html_delivery && strpos($html_delivery, 'error') === false): ?>
        <?if($bTab):?>
            <?if(!isset($bShow_delivery)):?>
                <?$bShow_delivery = true;?>
            <?else:?>
                <div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="delivery">
                    <?= $html_delivery; ?>
                </div>
            <?endif;?>
        <?else:?>
            <div class="detail-block ordered-block delivery">
                <h3 class="switcher-title"><?=$arParams["T_DELIVERY"]?></h3>
                <?= $html_delivery; ?>
            </div>
        <?endif;?>
    <? endif; ?>
<?endif;?>