<?use \Bitrix\Main\Localization\Loc;

//show tabs block
$countTab = 0;

foreach ($arTabOrder as $iTab => $tabCode) {
    include $tabCode.'.php';

    $bShowVar = 'bShow_'.$tabCode;
    if (isset($$bShowVar) && $$bShowVar) {
        $countTab++;
    } else {
        unset($arTabOrder[$iTab]);
    }
}?>

<?if($countTab):?>
    <div class="grid-list__item detail-block ordered-block tabs-block">
        <?if($countTab > 1):?>
            <div class="tabs tabs-history arrow_scroll">
                <ul class="nav nav-tabs font_14--to-600 mobile-scrolled mobile-offset">
                    <?$iTab = 0;?>
                    <?foreach($arTabOrder as $tabCode):?>
                        <?$upperTabCode = mb_strtoupper($tabCode);?>
                        <li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#<?=$tabCode?>" data-toggle="tab"><?=$arParams['T_'.$upperTabCode]?></a></li>
                    <?endforeach;?>
                </ul>
            </div>
        <?endif;?>
        <div class="tab-content<?=($iTab < 2 ? ' not_tabs' : '')?>">
            <?$iTab = 0;?>
            <?foreach($arTabOrder as $tabCode):?>
                <?include $tabCode.'.php';?>
            <?endforeach;?>
        </div>
    </div>
<?endif;?>