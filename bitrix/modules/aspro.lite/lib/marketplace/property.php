<?php

namespace Aspro\Lite\Marketplace;

use \Bitrix\Main\Localization\Loc,
    \Bitrix\Iblock\PropertyTable,
    \Bitrix\Highloadblock as HL,
    \Bitrix\Main\Loader;
use \Aspro\Lite\Marketplace\Helper;

class Property
{
    private $info = null;
    private $values = [];

    public function __construct(string $propertyCode, ?string $iblockId = '')
    {
        if (Loader::includeModule('iblock')) {
            if (!$iblockId) {
                if ($arInfo = Helper::getSkuInfo($iblockId)) {
                    $iblockId = $arInfo['IBLOCK_ID'];
                }
            }
            $this->info = \CIBlockProperty::GetByID($propertyCode, $iblockId)->Fetch();
        }
    }

    public function isCorrectType()
    {
        return $this->isHighloadType() || $this->isListType() || $this->isElementType();
    }

    public function isHighloadType()
    {
        return $this->info['PROPERTY_TYPE'] === PropertyTable::TYPE_STRING && $this->info['USER_TYPE'] === 'directory' && $this->info['USER_TYPE_SETTINGS']['TABLE_NAME'];
    }

    public function isListType()
    {
        return $this->info['PROPERTY_TYPE'] === PropertyTable::TYPE_LIST;
    }

    public function isElementType()
    {
        return $this->info['PROPERTY_TYPE'] === PropertyTable::TYPE_ELEMENT && $this->info['IBLOCK_ID'];
    }

    public function getValues()
    {
        switch ($this->info['PROPERTY_TYPE']) {
            case $this->isListType():
                $this->getValuesFromTypeList();
                break;
            case $this->isElementType():
                $this->getValuesFromTypeElement();
                break;
            case $this->isHighloadType():
                $this->getValuesFromTypeHighload();
                break;
        }

        return $this->values;
    }

    private function getValuesFromTypeList()
    {
        $rsValues = \CIBlockProperty::GetPropertyEnum($this->info['ID']);
        while ($arValue = $rsValues->Fetch()) {
            $this->values[] = $this->formatValue($arValue);
        }
    }

    private function formatValue($arValues, $arMapping = [])
    {
        $arDefaultMapping = [
            'ID' => 'ID',
            'VALUE' => 'VALUE'
        ];
        $arMergedMapping = array_merge($arDefaultMapping, $arMapping);

        $result = [];
        foreach ($arMergedMapping as $key => $value) {
            $result[$key] = $arValues[$value];
        }

        return $result;
    }

    private function getValuesFromTypeElement()
    {
        $rsValues = \Bitrix\Iblock\ElementTable::getList([
            'order' => ['NAME' => 'ASC'],
            'filter' => ['IBLOCK_ID' => $this->info['LINK_IBLOCK_ID']],
            'select' => ['ID', 'VALUE' => 'NAME'],
            'cache' => ['ttl' => 86400]
        ]);
        while ($arValue = $rsValues->Fetch()) {
            $this->values[] = $this->formatValue($arValue);
        }
    }

    private function getValuesFromTypeHighload()
    {
        try {
            $this->prepareHighload();

            $arConfig = [];
            $rsValues = $this->info['HL']['ENTITY_CLASS']::getList($arConfig);
            while ($arValue = $rsValues->fetch()) {
                $this->values[] = $this->formatValue($arValue, ['VALUE' => 'UF_NAME', 'ID' => 'UF_XML_ID']);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function prepareHighload()
    {
        if (Loader::includeModule('highloadblock')) {
            $this->info['HL'] = HL\HighloadBlockTable::getList([
                'filter' => ['=TABLE_NAME' => $this->info['USER_TYPE_SETTINGS']['TABLE_NAME']]
            ])->fetch();

            if (!$this->info['HL']) {
                throw new \Exception('hl block not found');
            }

            $entity = HL\HighloadBlockTable::compileEntity($this->info['HL']);
            $this->info['HL']['ENTITY_CLASS'] = $entity->getDataClass();
        }
    }

    public function isSameEntityAs(Property $obj): bool
    {
        if ($this->isHighloadType() && $obj->isHighloadType()) {
            return $this->info['HL']['TABLE_NAME'] === $obj->info['HL']['TABLE_NAME'];
        }

        if ($this->isElementType() && $obj->isElementType()) {
            return $this->info['IBLOCK_ID'] === $obj->info['IBLOCK_ID'];
        }

        return false;
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return null;
    }
}