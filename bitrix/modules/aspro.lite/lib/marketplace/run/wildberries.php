<?php

namespace Aspro\Lite\Marketplace\Run;

use Aspro\Lite\Marketplace\Maps\Wildberries as Map;
use Aspro\Lite\Marketplace\Adapters\Wildberries as Adapter;
use Aspro\Lite\Marketplace\Config\Wildberries as Config;
use Aspro\Lite\Marketplace\Finder;
use Aspro\Lite\Marketplace\Traits\Summary;


class Wildberries
{
    const MAX_RESULT_BARCODES = 10;

    use Summary;

    protected $iblockId = null;

    /** @var Map|null */
    protected $map = null;

    /** @var Adapter|null */
    protected $adapver = null;

    /** @var Finder|null */
    protected $finder = null;

    /** @var array ���������� �������� */
    protected $statusEnums = [];
    /** @var array ������ (��������) ��� ������������� �������� ������ � ���������� �������� */
    private $itemsToSync = [];

    /** @var array ������ ��� ���������� ��� ���������� ������� */
    private $itemsToUpdate = [];

    /** @var array ������ ��� ���������� ���. (��� ��������� � ������� ���� WB_NM_ID) */
    private $itemsToPriceUpdate = [];

    /** @var array ������ ��� ���������� ��������. (��� ��������� � ������� ���� WB_CHRT_ID) */
    private $itemsToStoreUpdate = [];

    /** @var array ������ �������� ������� � ��������������� �������� �������� */
    private $scopeElements = [];

    /** @var array ������ ������ �� �������� �� ������� ��� ������� �������� */
    private $scopeElementStores = [];

    /** @var array ������ ���������� ���������� � ������� */
    private $scopeBarcodes = [];

    /** @var array ������ ���������� ����������� ��� ��������� */
    private $scopeElementBarcodes = [];

    /** @var string ������������� ���� �� ����� � ������ */
    private $logFolder = '';

    /** @var string �������� ����� � ���������� */
    private $logFileName = '';

    /** @var string ������������� ���� �� ����� */
    private $rLogFilePath = '';

    /** @var string ���������� ���� �� ����� */
    private $aLogFilePath = '';

    /** @var null|int Id ���������� �������� ������������� ������� */
    private $lastSentElementId = null;

    /** @var null|string VendoreProp ���������� �������� ������������� ������� */
    private $curVendoreProp = null;

    /** @var int ������������ ����� ���������� */
    private $maxExecutionTime = 0;

    /** @var int ������ ���������� */
    private $startExecTime = 0;

    /** @var array Helper. ������ �������� �� mapping, ��� ������������ ������������� � ���� ������ */
    private $mapValues = [];

    /** @var array Helper. ������ �������� �� mapping �� �������, ��� ������������ ������������� � ���� ������ */
    private $mapStoreValues = [];

    /** @var bool ��������� ���� */
    private $needUploadPrices = false;

    /** @var bool ��������� ������� */
    private $needUploadStores = false;

    /** @var bool ��������� �������� */
    private $needUploadImages = false;


    public function __construct($iblockId)
    {
        $this->iblockId = $iblockId;

        $this->map = new Map($iblockId);
        $this->mapValues = $this->map->getValues(false, 'WB_PROPERTY');
        $this->mapStoreValues = $this->map->getStoreValues();

        $this->adapver = new Adapter();
        $this->adapver->loadMapping($this->map->getMapStructure());

        $this->finder = new Finder($iblockId);
    }

    /**
     * ���������� ����� �������
     *
     * @param array $options �����
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->finder->setOptions([
            'checkPermission' => $options['checkPermission'],
            'isAvailable' => $options['isAvailable'],
            'serverName' => $options['serverName'],
            'isAllResult' => $options['isAllResult'],
            'maxResult' => 50,
            'filter' => $options['filter']
        ]);

        $this->logFolder = $options['logFolder'] ? $_SERVER['DOCUMENT_ROOT'] . $options['logFolder'] : null;
        $this->logFileName = $options['logFileName'] ?? null;
        $this->startExecTime = $options['startExecTime'] ?? 0;
        $this->maxExecutionTime = $options['maxExecutionTime'] ?? 0;
        $this->needUploadPrices = $options['needUploadPrices'] ?? false;
        $this->needUploadStores = $options['needUploadStores'] ?? false;
        $this->needUploadImages = $options['needUploadImages'] ?? false;
        $this->curVendoreProp = $options['curVendoreProp'] ?? null;
    }

    /**
     * ��������� �������
     *
     * @return array
     */
    public function export(): array
    {
        $finalExport = true;
        $eachElementId = null;

        $this->scopeElements = $this->finder->getElements();
        $this->scopeElementStores = $this->finder->getElementStores();

        foreach ($this->scopeElements as $element) {
            $eachElementId = $element['ID'];
            $finalExport = false;

            $this->addItem($element);
        }

        $this->syncElementsWithCreated();

        $this->sendToUpdatePrices();

        $this->sendToUpdateStores();

        $this->sendToUpdate();

        if ($this->adapver->hasErrors()) {
            $this->addErrors($this->adapver->getErrors());
        }

        $this->writeResults();

        $this->writeErrors();
        
        if($finalExport){
            $this->getElementsWithErrors();
            $this->clearOldLog();
        }

        return [
            'finalExport' => $finalExport,
            'lastElementId' => $this->lastSentElementId ?: $eachElementId,
            'curVendoreProp' => $this->curVendoreProp,
        ];
    }

    /**
     * �������� ������� � ������ ������ �� ��������
     */
    protected function addItem($element)
    {
        $element = \Bitrix\Main\Text\Encoding::convertEncoding($element, LANG_CHARSET, 'UTF-8');

        $this->checkAndSetBarcode($element);

        $item = $this->adapver->getBuildItem($element);

        if(!$item) {
            return null;
        }

        if($element['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_PRODUCT) {
            if (
                !$element[Config::PROPERTY_NM_ID]
                || !$element[Config::PROPERTY_IMT_ID]
                || !$element[Config::PROPERTY_CHRT_ID]
            ) {
                $vendorCodeProperty = $this->getBxPropertyCode($element, Adapter::REQUIRED_FIELD_VENDOR_CODE);
                if($element[$vendorCodeProperty]) {
                    $this->itemsToSync[$element['ID']] = $element[$vendorCodeProperty];
                }
            }
        }

        $this->itemsToUpdate[$element['ID']] = $item;

        $this->addItemForUpdatePrice($element['ID']);

        $this->addItemForUpdateStore($element['ID']);
    }

    /**
     * ������������� � "��������� ��������".
     * ����� ����������� �� ��������
     * ���� ���� ��������� �������� ��� ��������:
     *  - ��������� �������� ������� WB_NM_ID, WB_IMT_ID, WB_ID, WB_CHRT_ID
     *  - ��������� ������ � ������ �� ���������� ���� � ��������
     *
     * @return void
     */
    public function syncElementsWithCreated()
    {
        if (!$this->itemsToSync) {
            return;
        }

        $payload = $this->getFilterByVendor($this->itemsToSync);
        $cards = $this->adapver->getServiceItems(
            array_values($this->itemsToSync)
        );

        foreach ($cards as $nomenclature) {
            $elementId = (int)$payload['mapping'][$nomenclature['vendorCode']];

            if(!$nomenclature || !$elementId || !isset($this->itemsToSync[$elementId])) {
                continue;
            }

            if($this->scopeElements[$elementId]['TYPE'] === \Bitrix\Catalog\ProductTable::TYPE_PRODUCT) {
                $variation = $nomenclature['sizes'][0] ?? null;

                if(!$variation){
                    continue;
                }

                \CIBlockElement::SetPropertyValuesEx($elementId, false, [
                    Config::PROPERTY_IMT_ID => $nomenclature['imtID'],
                    Config::PROPERTY_NM_ID => $nomenclature['nmID'],
                    Config::PROPERTY_CHRT_ID => $variation['chrtID'],
                    Config::PROPERTY_STATUS => $this->getStatusEnumId(Config::STATUS_SUCCESS),
                    Config::PROPERTY_ERROR_TEXT => [
                        'VALUE' => [
                            'TYPE' => 'TEXT',
                            'TEXT' => ''
                        ]
                    ]
                ]);

                $this->scopeElements[$elementId][Config::PROPERTY_IMT_ID] = $nomenclature['imtID'];
                $this->scopeElements[$elementId][Config::PROPERTY_NM_ID] = $nomenclature['nmID'];
                $this->scopeElements[$elementId][Config::PROPERTY_CHRT_ID] = $variation['chrtID'];

                $this->updateBuildItemToUpdate($elementId);

                $this->addItemForUpdatePrice($elementId);

                $this->addItemForUpdateStore($elementId);
            }

            unset($this->itemsToSync[$elementId]);
        }
        
    }

    /**
     * ����� ���������� � ��������� � ��������� "��������".
     * ����� ����������� �� ��������
     * ���� ���� ��������:
     *  - ��������� �������� ������ � ���
     *  - ����� � ����� ��� ����� �� ���� ��������� � ����������
     */
    public function getElementsWithErrors()
    {
        $cards = $this->adapver->getErrorServiceItems();

        if(is_array($cards) && count($cards)>0 && $this->curVendoreProp){
            $vendoreCodes = array_column($cards, 'vendorCode');
            $arItemsWErrors = [];
            $vendorCodeProperty = 'PROPERTY_' . $this->curVendoreProp;
            $errorProperty = 'PROPERTY_' . Config::PROPERTY_ERROR_TEXT;
            $arFilter = [
                'IBLOCK_ID' => $this->iblockId,
                $vendorCodeProperty => $vendoreCodes
            ];

            $dbItemsWErrors = \CIBlockElement::GetList(['ID' => 'ASC'], $arFilter, false, false, ["ID", "IBLOCK_ID", "TYPE", $errorProperty, $vendorCodeProperty]);
            while($arItem = $dbItemsWErrors->fetch()){
                $arItemsWErrors[$arItem[$vendorCodeProperty.'_VALUE']] = $arItem;
            }

            foreach ($cards as $nomenclature) {
                $curErrorElement = $arItemsWErrors[$nomenclature['vendorCode']];
                $elementId = (int)$curErrorElement['ID'];
                $errorText = is_array($nomenclature['errors']) ? implode(PHP_EOL, $nomenclature['errors']) : ''; 

                if($elementId){
                    if((int)$curErrorElement['TYPE'] === \Bitrix\Catalog\ProductTable::TYPE_PRODUCT) {
                        \CIBlockElement::SetPropertyValuesEx($elementId, false, array_filter([
                            Config::PROPERTY_STATUS => $this->getStatusEnumId(Config::STATUS_WARNING),
                            Config::PROPERTY_ERROR_TEXT => [
                                'VALUE' => [
                                    'TYPE' => 'TEXT',
                                    'TEXT' => $curErrorElement[$errorProperty.'_VALUE']['TEXT'] . PHP_EOL . $this->encoding($errorText)
                                ]
                            ],
                        ]));
                    }
                }

                $this->addError('[ID:' . $elementId .'][vendorCode:'. $nomenclature['vendorCode'] .']'. PHP_EOL . $errorText);
            }

            $this->writeErrors();
        }
    }

    /**
     * ������������� ���� �� �����
     *
     * @return string
     */
    public function getRLogPath(): string
    {
        if ($this->rLogFilePath) {
            return $this->rLogFilePath;
        }

        $this->checkLogFolder();

        return $this->rLogFilePath;
    }

    /**
     * ���������� ���� �� �����
     *
     * @return string
     */
    public function getALogPath(): string
    {
        if ($this->aLogFilePath) {
            return $this->aLogFilePath;
        }

        $this->checkLogFolder();

        return $this->aLogFilePath;
    }

    /**
     * �������� ���������� ��� �������� �������� �������� �� mapping
     *
     * @param $element
     * @param $propertyCode
     * @return string|null
     */
    protected function getBxPropertyCode($element, $propertyCode): ?string
    {
        return $this->mapValues[$element['IBLOCK_SECTION_ID']]['PROPERTIES'][$propertyCode]['BX_PROPERTY'] ?? null;
    }

    /**
     * �������� ������ �������� ������� ����� ���������� �� ����������
     *
     * @param $elementId
     * @return void
     */
    protected function updateBuildItemToUpdate($elementId)
    {
        if(!isset($this->scopeElements[$elementId])) {
            return;
        }

        $element = \Bitrix\Main\Text\Encoding::convertEncoding($this->scopeElements[$elementId], LANG_CHARSET, 'UTF-8');

        $this->itemsToUpdate[$elementId] = $this->adapver->getBuildItem($element);
    }

    /**
     * �������� ������� � ������ �� ���������� ����
     * ������ �������� � ����������� WB_NM_ID
     *
     * @param $elementId
     */
    protected function addItemForUpdatePrice($elementId)
    {
        $element = $this->scopeElements[$elementId];
        
        if (!$element) {
            return;
        }

        if ($element[Config::PROPERTY_NM_ID]) {
            $nmId = (int)$element[Config::PROPERTY_NM_ID];
            $priceProperty = $this->getBxPropertyCode($element, Adapter::REQUIRED_FIELD_PRICE);
            if ($priceProperty || $this->itemsToPriceUpdate[$nmId]) {
                
                $this->itemsToPriceUpdate[$nmId] = [
                    'nmId' => $nmId,
                    'price' => (int)$element[$priceProperty],
                ];
            }
        }
    }

    /**
     * �������� ������� � ������ �� ���������� ��������
     * ������ �������� � ����������� WB_CHRT_ID
     *
     * @param $elementId
     */
    protected function addItemForUpdateStore($elementId)
    {
        $element = $this->scopeElements[$elementId];

        if (!$element) {
            return;
        }

        if ($element[Config::PROPERTY_CHRT_ID]) {
            $barcodeProperty = $this->getBxPropertyCode($element, Adapter::REQUIRED_FIELD_BARCODE);
            $barcode = $element[$barcodeProperty] ?? null;

            if (!$this->mapStoreValues || !$barcode) {
                return;
            }

            $warehouseId = null;
            $resultValues = [
                $barcode => [],
            ];
            foreach ($this->mapStoreValues as $mapStore) {
                if ($warehouseId !== $mapStore['WB_STORE_ID']) {
                    $warehouseId = $mapStore['WB_STORE_ID'];
                }

                if (!$resultValues[$barcode][$warehouseId]) {
                    $resultValues[$barcode][$warehouseId] = [
                        'barcode' => $barcode,
                        'stock' => 0,
                        'warehouseId' => $warehouseId,
                    ];
                }

                $resultValues[$barcode][$warehouseId]['stock'] += (int)$this->scopeElementStores[$elementId][$mapStore['BX_STORE_ID']]['AMOUNT'];
            }

            $this->itemsToStoreUpdate = array_merge($this->itemsToStoreUpdate, array_values($resultValues[$barcode]));
        }
    }

    /**
     *  �������� �������� �������� ��������,
     *  �� ������ ������� ������� ������ � �������� ����������, ��������, ����������
     */
    protected function writeResults()
    {
        if ($this->adapver->hasResults()) {
            foreach ($this->adapver->getResults() as $result) {
                $elementId = (int)$result['ELEMENT_ID'];

                if ($result['ELEMENT_ID']) {
                    \CIBlockElement::SetPropertyValuesEx($elementId, false, array_filter([
                        Config::PROPERTY_STATUS => $this->getStatusEnumId($result['STATUS_CODE']),
                        Config::PROPERTY_ERROR_TEXT => [
                            'VALUE' => [
                                'TYPE' => 'TEXT',
                                'TEXT' => $result['WARNINGS'] ? $this->encoding($result['WARNINGS']) : ''
                            ]
                        ],
                        Config::PROPERTY_BARCODE =>
                            isset($this->scopeElementBarcodes[$elementId]) && $this->scopeElementBarcodes[$elementId]
                                ? $this->scopeElementBarcodes[$elementId]
                                : null,
                    ]));
                }
            }
        }
    }

    /**
     * �������� ����� �����. ������������ �����
     *
     * @return bool
     */
    protected function checkLogFolder(): bool
    {
        if (!$this->logFolder || !$this->logFileName) {
            return false;
        }

        if (!file_exists($this->logFolder)) {
            mkdir($this->logFolder, 0777, true);
        }

        $dirToLog = $this->logFolder . '/' . $this->logFileName;

        $this->aLogFilePath = $dirToLog;

        $this->rLogFilePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dirToLog);

        return true;
    }

    /**
     * �������� ����� ���������� �������
     *
     * @return bool
     */
    protected function checkExecutionTime(): bool
    {
        if ($this->maxExecutionTime > 0 && (getmicrotime() - $this->startExecTime) >= $this->maxExecutionTime) {
            return false;
        }

        return true;
    }


    /**
     * ��������� ������� ��������� � �������� � ��������� �������� ��� ��� ���������,
     * ��� ������� ��� �������� ��������� �������� WB_BARCODE
     *
     * @param $elementId
     * @return void
     */
    protected function checkAndSetBarcode(&$element)
    {
        $barcodeProperty = $this->getBxPropertyCode($element, Adapter::REQUIRED_FIELD_BARCODE);

        if(
            $barcodeProperty
            && $barcodeProperty === Config::PROPERTY_BARCODE
            && !$element[$barcodeProperty]
        ) {
            $barcode = $this->getBarcode();
            $element[$barcodeProperty] = $barcode;
            $this->scopeElementBarcodes[(int)$element['ID']] = $barcode;
        }
    }

    /**
     * ��������� ������ ��� ���������� ��� ��������
     *
     * @return bool
     */
    protected function sendToUpdate(): bool
    {
        $itemsToUpdate = $itemsToCreate = [];
        $payloadUpdate = $payloadCreate = [];

        foreach ($this->itemsToUpdate as $elementId => $item) {
            $itemId = $this->scopeElements[$elementId][Config::PROPERTY_IMT_ID] ?? null;

            if ($itemId) {
                $itemsToUpdate[] = $item;
                $payloadUpdate[] = ['ELEMENT_ID' => $elementId];
            } else {
                $itemsToCreate[] = $item;
                $payloadCreate = [
                    'ELEMENT_ID' => $elementId,
                    'ELEMENT_NAME' => $this->utf($this->scopeElements[$elementId]['NAME'])
                ];
                $this->adapver->createServiceItem($item, $payloadCreate);
            }

            $this->lastSentElementId = $elementId;
            $this->curVendoreProp = $this->getBxPropertyCode($this->scopeElements[$elementId], Adapter::REQUIRED_FIELD_VENDOR_CODE);
        }        

        if(count($itemsToUpdate) > 0){
            $this->adapver->updateServiceItems($itemsToUpdate, $payloadUpdate);
        }
        // if(count($itemsToCreate) > 0){
        //     $this->adapver->createServiceItems($itemsToCreate, $payloadCreate);
        // }

        if($this->needUploadImages){
            foreach ($this->itemsToUpdate as $elementId => $item) {    
                $imagesProp = $this->getBxPropertyCode($this->scopeElements[$elementId], Adapter::REQUIRED_FIELD_IMAGES);
                $vendorCodeProperty = $this->getBxPropertyCode($this->scopeElements[$elementId], Adapter::REQUIRED_FIELD_VENDOR_CODE);
                if($imagesProp && $this->scopeElements[$elementId][$imagesProp]){
                    $this->adapver->updateMedia($this->scopeElements[$elementId][$vendorCodeProperty], $this->scopeElements[$elementId][$imagesProp]);
                }
            }
        }        

        return true;
    }

    /**
     * ��������� ������ �� ���������� ���
     *
     * @return bool
     */
    protected function sendToUpdatePrices(): bool
    {
        if ($this->needUploadPrices && $this->itemsToPriceUpdate) {
            $this->adapver->updateServicePrices(array_values($this->itemsToPriceUpdate));
        }

        return true;
    }

    /**
     * ��������� ������ �� ���������� ��������
     *
     * @return bool
     */
    protected function sendToUpdateStores(): bool
    {
        if ($this->needUploadStores && $this->itemsToStoreUpdate) {
            $this->adapver->updateServiceStocks(array_values($this->itemsToStoreUpdate));
        }

        return true;
    }

    /**
     * Helper. �� ������� ������ itemsToSync ��������� ������ �� ��������
     *
     * $isSplit = false  (API WB �������� ��������)
     * � ���� ��������� ��������
     * [
     *      [
     *          'column' => 'nomenclatures.vendorCode',
     *          'search' => '�101240455'
     *      ],
     *      [
     *          'column' => 'nomenclatures.vendorCode',
     *          'search' => 'B101240455'
     *      ],
     *      ...
     * ]
     *
     * $isSplit = true
     * �������� ����� �����������
     * [
     *      [
     *          'column' => 'nomenclatures.vendorCode',
     *          'search' => '�101240455|B101240455'
     *          ],
     * ]
     *
     * @param array $vendorCodeValues
     * @param boolean $isSplit �� ��������
     * @return array
     */
    protected function getFilterByVendor(array $vendorCodeValues): array
    {
        if (!$vendorCodeValues) {
            return [];
        }

        $mapping = [];

        foreach ($vendorCodeValues as $elementId => $vendorCode) {
            $mapping[$vendorCode] = $elementId;
        }       

        return [
            'mapping' => $mapping,
        ];
    }

    /**
     * ENUM_ID �������
     *
     * @param $statusCode
     * @return mixed|null
     */
    protected function getStatusEnumId($statusCode)
    {
        $statusEnums = $this->getStatusEnums();

        return $statusEnums[$statusCode] ?? null;
    }

    /**
     * ���������� ��������
     *
     * @return array
     */
    protected function getStatusEnums(): array
    {
        if ($this->statusEnums) {
            return $this->statusEnums;
        }

        $rs = \CIBlockPropertyEnum::GetList([], [
            'IBLOCK_ID' => $this->iblockId,
            'CODE' => Config::PROPERTY_STATUS
        ]);

        while ($enum = $rs->GetNext()) {
            $this->statusEnums[$enum['XML_ID']] = $enum['ID'];
        }

        return $this->statusEnums;
    }

    /**
     * �������� ��������� ��������
     *
     * @return string|int
     */
    protected function getBarcode()
    {
        if($this->scopeBarcodes) {
            return array_shift($this->scopeBarcodes);
        }

        $this->scopeBarcodes = $this->adapver->getServiceBarcodes(self::MAX_RESULT_BARCODES);

        return array_shift($this->scopeBarcodes);
    }

    /**
     * ������ ������ � ��� ����
     *
     * @return void
     */
    protected function writeErrors()
    {
        if(!$this->hasErrors()) {
            return;
        }

        $errorSummary = PHP_EOL . '-----------------------------------------------' . PHP_EOL;

        $errorSummary .= $this->getErrorSummary(PHP_EOL);

        $this->clearErrors();

        $this->writeLog($errorSummary);
    }

    protected function writeLog($errorText): bool
    {
        if (file_put_contents($this->getALogPath(), self::encoding($errorText), FILE_APPEND) === false) {
            $this->addError('Failed to create error collection file on path <br /> ' + $this->getALogPath());

            return false;
        }

        return true;
    }

    protected function clearOldLog(): bool
    {
        $arFiles = glob($this->logFolder."/*.txt");
        $maxLogDays = (float)\Bitrix\Main\Config\Option::get(Config::MODULE, Config::OPTION_LOG_TIME, "14");
        $maxLogSec = $maxLogDays > 0 ? $maxLogDays * 86400 : 14 * 86400;

        if($arFiles){
            foreach($arFiles as $file){
                if(file_exists($file) && !is_dir($file)){
                    if(time() - filemtime($file) >= $maxLogSec){ // 14 days
                        @unlink($file);
                    }
                }
            }
        }
        
        return true;
    }

    public function encoding($value)
    {
        return \Bitrix\Main\Text\Encoding::convertEncoding($value, 'UTF-8', LANG_CHARSET);
    }

    public function utf($value)
    {
        return \Bitrix\Main\Text\Encoding::convertEncoding($value, LANG_CHARSET, 'UTF-8');
    }
}