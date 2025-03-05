<?php

namespace Aspro\Lite\Marketplace\Config;

use \Bitrix\Main\Localization\Loc;

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

\Bitrix\Main\Loader::includeModule('iblock');

class Ozon extends Base
{
    const OPTION_API_KEY = 'MP_OZON_API_KEY';
    const OPTION_CLIENT_ID = 'MP_OZON_CLIENT_ID';
    const OPTION_LOG_TIME = 'MP_OZON_LOG_TIME';

    /** @var string Системные свойства инфоблока */
    const PROPERTY_STATUS = 'WB_STATUS';
    const PROPERTY_ERROR_TEXT = 'WB_ERROR_TEXT';
    const PROPERTY_IMT_ID = 'WB_IMT_ID';
    const PROPERTY_NM_ID = 'WB_NM_ID';
    const PROPERTY_BARCODE = 'WB_BARCODE';
    const PROPERTY_CHRT_ID = 'WB_CHRT_ID';

    /** @var string Успешно загружен на сервис */
    const STATUS_SUCCESS = 'SUCCESS';
    /** @var string Не загружен, товар не прошел валидацию по полям на сайте или со стороны сервиса */
    const STATUS_ERROR = 'ERROR';
    /** @var string Товар загружен, но есть предупреждения со стороны сервиса */
    const STATUS_WARNING = 'WARNING';

    static protected $scopeIblockSystemProperties = [];

    /**
     * Справочник статусов
     *
     * @return array
     */
    static public function getStatusEnums(int $iblockId): array
    {
        $rs = \CIBlockPropertyEnum::GetList([], [
            'IBLOCK_ID' => $iblockId,
            'CODE' => self::PROPERTY_STATUS
        ]);

        $result = [];
        while ($enum = $rs->GetNext()) {
            $result[$enum['XML_ID']] = [
                'ENUM_ID' => $enum['ID'],
                'VALUE' => $enum['VALUE']
            ];
        }

        return $result;
    }

    /**
     * Получить системные свойства из элемента каталога
     *
     * @param int $iblockId
     * @param int $elementId
     * @return array
     */
    static public function getElementSystemProperties(int $iblockId, int $elementId): array
    {
        $properties = [];
        foreach (self::getSystemPropertyCodes() as $propertyCode) {
            $rs = \CIBlockElement::GetProperty($iblockId, $elementId, ['SORT' => 'ASC'], ['CODE' => $propertyCode]);

            while ($row = $rs->Fetch()) {
                $properties[$propertyCode] = $row;
            }
        }

        return $properties;
    }

    static public function getIblockSystemProperties(int $iblockId): array
    {
        if (isset(self::$scopeIblockSystemProperties[$iblockId]) && self::$scopeIblockSystemProperties[$iblockId]) {
            return self::$scopeIblockSystemProperties[$iblockId];
        }

        $rs = \CIBlockProperty::GetList(['sort' => 'asc', 'name' => 'asc'], [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockId,
        ]);

        $properties = [];
        while ($property = $rs->GetNext()) {
            if (in_array($property['CODE'], self::getSystemPropertyCodes())) {
                $properties[$property['CODE']] = $property;
            }
        }

        self::$scopeIblockSystemProperties[$iblockId] = $properties;

        return $properties;
    }

    static public function existsIblockSystemProperties(int $iblockId): bool
    {
        $properties = self::getIblockSystemProperties($iblockId);

        if (count($properties) === count(self::getSystemPropertyCodes())) {
            return true;
        }

        return false;
    }

    static public function getSystemPropertyCodes(): array
    {
        return [
            self::PROPERTY_IMT_ID,
            self::PROPERTY_NM_ID,
            self::PROPERTY_BARCODE,
            self::PROPERTY_ERROR_TEXT,
            self::PROPERTY_STATUS,
            self::PROPERTY_CHRT_ID,
        ];
    }

    static public function createIblockSystemProperties(int $iblockId)
    {
        $properties = self::getIblockSystemProperties($iblockId);

        $iblockProperty = new \CIBlockProperty;

        if(!isset($properties[self::PROPERTY_BARCODE])) {
            $iblockProperty->Add([
                'NAME' => strip_tags(Loc::getMessage('AS_FORM_LABEL_WB_BARCODE')),
                'ACTIVE' => 'Y',
                'SORT' => 1000,
                'CODE' => self::PROPERTY_BARCODE,
                'PROPERTY_TYPE' => 'S',
                'IBLOCK_ID' => $iblockId,
            ]);
        }

        if(!isset($properties[self::PROPERTY_IMT_ID])) {
            $iblockProperty->Add([
                'NAME' => strip_tags(Loc::getMessage('AS_FORM_LABEL_WB_IMT_ID')),
                'ACTIVE' => 'Y',
                'SORT' => 1000,
                'CODE' => self::PROPERTY_IMT_ID,
                'PROPERTY_TYPE' => 'S',
                'IBLOCK_ID' => $iblockId,
            ]);
        }

        if(!isset($properties[self::PROPERTY_NM_ID])) {
            $iblockProperty->Add([
                'NAME' => strip_tags(Loc::getMessage('AS_FORM_LABEL_WB_NM_ID')),
                'ACTIVE' => 'Y',
                'SORT' => 1000,
                'CODE' => self::PROPERTY_NM_ID,
                'PROPERTY_TYPE' => 'S',
                'IBLOCK_ID' => $iblockId,
            ]);
        }

        if(!isset($properties[self::PROPERTY_CHRT_ID])) {
            $iblockProperty->Add([
                'NAME' => strip_tags(Loc::getMessage('AS_FORM_LABEL_WB_CHRT_ID')),
                'ACTIVE' => 'Y',
                'SORT' => 1000,
                'CODE' => self::PROPERTY_CHRT_ID,
                'PROPERTY_TYPE' => 'S',
                'IBLOCK_ID' => $iblockId,
            ]);
        }

        if(!isset($properties[self::PROPERTY_STATUS])) {
            $iblockProperty->Add([
                'NAME' => strip_tags(Loc::getMessage('AS_FORM_LABEL_WB_STATUS')),
                'ACTIVE' => 'Y',
                'SORT' => 1000,
                'CODE' => self::PROPERTY_STATUS,
                'PROPERTY_TYPE' => 'L',
                'IBLOCK_ID' => $iblockId,
                'VALUES' => [
                    [
                        'VALUE' => Loc::getMessage('AS_STATUS_VALUE_1'),
                        'XML_ID' => 'SUCCESS',
                        'DEF' => 'N',
                        'SORT' =>  500
                    ],
                    [
                        'VALUE' => Loc::getMessage('AS_STATUS_VALUE_2'),
                        'XML_ID' => 'ERROR',
                        'DEF' => 'N',
                        'SORT' =>  500
                    ],
                    [
                        'VALUE' => Loc::getMessage('AS_STATUS_VALUE_3'),
                        'XML_ID' => 'WARNING',
                        'DEF' => 'N',
                        'SORT' =>  500
                    ]
                ]
            ]);
        }

        if(!isset($properties[self::PROPERTY_ERROR_TEXT])) {
            $iblockProperty->Add([
                'NAME' => strip_tags(Loc::getMessage('AS_FORM_LABEL_WB_ERROR_TEXT')),
                'ACTIVE' => 'Y',
                'SORT' => 1000,
                'CODE' => self::PROPERTY_ERROR_TEXT,
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'HTML',
                'IBLOCK_ID' => $iblockId,

            ]);
        }
    }

    static public function getApiKey(): ?string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE, self::OPTION_API_KEY) ?? null;
    }

    static public function getClientId(): ?string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE, self::OPTION_CLIENT_ID) ?? null;
    }
}