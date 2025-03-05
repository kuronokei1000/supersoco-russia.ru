<?php

namespace Aspro\Lite\Marketplace\Run\Export_ozon;

use Aspro\Lite\Marketplace\Maps\Ozon as Map;
use Aspro\Lite\Marketplace\Adapters\Ozon as Adapter;
use Aspro\Lite\Marketplace\Config\Ozon as Config;
use Aspro\Lite\Marketplace\Finder;
use Aspro\Lite\Marketplace\Traits\Summary;


class Main
{
    use Summary;

    const SECTIONS_STAGE = 'sections';
    const ITEMS_STAGE = 'items';

    protected $iblockId = null;
    protected $clientId = null;
    protected $priceType = 1; // BASE

    protected $stage = self::SECTIONS_STAGE;
    protected $lastId = null;
    protected $profileId = 0;
    protected $strActFileName = '';

    private $nextInfo = [];

    public function __construct($iblockId, $clientId)
    {
        $this->iblockId = $iblockId;
        $this->clientId = Config::getClientId();

        if ($clientId) {
            $this->clientId = $clientId;
        }
    }

    /**
     * Stored options for run export
     *
     * @param array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->profileId = $options['profileId'];
        $this->priceType = $options['priceType'];
        $this->strActFileName = urlencode($options['strActFileName']);

        if ($options['lastId']) {
            $this->lastId = $options['lastId'];
        }

        if ($options['stage']) {
            $this->stage = $options['stage'] ;
        }
    }

    /**
     * Execute import to IBlock
     *
     * @return array
     */
    public function export(): array
    {
        $finalExport = true;

        /*
        
        if ($this->adapter->hasErrors()) {
            $this->addErrors($this->adapter->getErrors());
        }

        // $this->writeResults();
        $this->writeErrors();
        */

        $this->nextInfo = [
            'stage' => $this->stage
        ];

        if ($this->isSectionStage()) {
            $this->nextInfo = array_merge($this->nextInfo, $this->importSections());
        }
        
        if ($this->isItemsStage()) {
            $this->nextInfo = array_merge($this->nextInfo, $this->importItems());
        }

        $this->nextInfo['finalExport'] = is_null($this->nextInfo['lastId']);

        return $this->nextInfo;
    }

    private function isSectionStage()
    {
        return $this->nextInfo['stage'] === self::SECTIONS_STAGE;
    }

    protected function importSections():array
    {
        $sections = new Sections($this->iblockId, $this->clientId, $this->lastId);
        $sections->import();

        $arNextInfo = $sections->getNextInfo();

        if (!$arNextInfo['lastId']) {
            $arNextInfo['stage'] = self::ITEMS_STAGE;
            $this->lastId = null;
        }

        return $arNextInfo;
    }

    private function isItemsStage()
    {
        return $this->nextInfo['stage'] === self::ITEMS_STAGE;
    }

    protected function importItems():array
    {
        $items = new Goods($this->iblockId, $this->clientId, $this->lastId, $this->priceType);
        $items->import();

        $arNextInfo = $items->getNextInfo();
        $arNextInfo['stage'] = self::ITEMS_STAGE;
        
        return $arNextInfo;
    }

    public function redirect()
    {
        // echo md5(uniqid(""));
        $urlParams = "CUR_ELEMENT_ID=".$this->nextInfo['lastId']."&ACT_FILE=".$this->strActFileName."&ACTION=EXPORT&PROFILE_ID=".$this->profileId."&stage=".$this->nextInfo['stage'];
        $fullUrl = $GLOBALS['APPLICATION']->GetCurPage().'?lang='.LANGUAGE_ID.'&'.$urlParams.'&'.bitrix_sessid_get();
        ?>

        <h3>
            <?=GetMessage("IMPORT_STAGE", [
                "#STAGE#" => GetMessage("IMPORT_".$this->nextInfo['stage'])
            ])?>
        </h3>
        <?=GetMessage("PROCESSED_ITEMS", [
            "#PROCESSED#" => $this->nextInfo['processed'], 
            "#STAGE#" => GetMessage("IMPORT_".$this->nextInfo['stage'])
        ]);?>
        <br><br>
        
        <?/*
        <?echo GetMessage("CES_AUTO_REFRESH");?><br>
        <a href="<?=$fullUrl; ?>"><?echo GetMessage("CES_AUTO_REFRESH_STEP");?></a><br>
        */?>

        <script type="text/javascript">function DoNext() {window.location="<?=$fullUrl; ?>";}setTimeout('DoNext()', 1000);</script>

        <?
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_after.php");
        die();
    }
}