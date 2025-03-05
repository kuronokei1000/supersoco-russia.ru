<?
use \Bitrix\Main\Localization\Loc;

$bTab = isset($tabCode) && $tabCode === 'buy';
?>
<?//show buy block?>
<?if($arParams["SHOW_BUY"] === 'Y'):?>
    <?
        if (!isset($html_buy)) {
            ob_start();
            $APPLICATION->IncludeFile($templateData['INCLUDE_FOLDER_PATH']."/index_howbuy.php", array(), array("MODE" => "html", "NAME" => GetMessage('T_BUY')));
            $html_buy = trim(ob_get_clean());
        }
    ?>
    <?if($bTab):?>
        <?if(!isset($bShow_buy)):?>
            <?$bShow_buy = true;?>
        <?else:?>
            <div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="buy">
                <?= $html_buy; ?>
            </div>
        <?endif;?>
    <?else:?>
        <div class="detail-block ordered-block buy">
            <h3 class="switcher-title"><?=$arParams["T_BUY"]?></h3>
            <?= $html_buy; ?>
        </div>
    <?endif;?>
<?endif;?>