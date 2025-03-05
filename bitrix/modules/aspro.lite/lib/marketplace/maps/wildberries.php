<?php

namespace Aspro\Lite\Marketplace\Maps;

use Aspro\Lite\Marketplace\Traits\Summary;
use Aspro\Lite\Marketplace\Adapters\Wildberries as Adapter;

class Wildberries extends Base
{
    use Summary;

    const GROUP_SECTIONS = 'sections';
    const GROUP_STORES = 'stores';

    /** @var Adapter|null  */
    protected $adapter = null;

    public function __construct($iblockId)
    {
        $this->adapter = new Adapter();

        parent::__construct($iblockId);
    }

    /**
     * Привести структуру данные для сохранения
     *
     * @param array $dataSections Массив сопоставлений по разделам и свойствам
     * @param array $dataStores Массив сопоставлений по складам
     * @return void
     */
    public function setPostData(array $dataSections, array $dataStores = []): bool
    {
        $bxProperties = $this->getAllProperties();

        $dataSections = \Bitrix\Main\Text\Encoding::convertEncoding($dataSections, LANG_CHARSET, 'UTF-8');

        $result = [];

        foreach ($dataSections as $postDataSection) {
            $wbSection = $this->getSectionProperties($postDataSection['WB_SECTION'], true);

            if (!$wbSection) {
                $this->addError('Save error, please try again');
                return false;
            }

            $result[self::GROUP_SECTIONS][$postDataSection['BX_SECTION']] = [
                'object' => $wbSection['object'],
                'properties' => [],
            ];

            foreach ($postDataSection['PROPERTIES'] as $postProperty) {
                $wbMapProperty = $wbSection['properties'][$postProperty['WB_PROPERTY']];

                if (!$wbMapProperty) {
                    continue;
                }

                $result[self::GROUP_SECTIONS][$postDataSection['BX_SECTION']]['properties'][$postProperty['WB_PROPERTY']] = array_merge($wbMapProperty, [
                    'wb_property_code' => $postProperty['WB_PROPERTY'],
                    'bx_property_code' => $postProperty['BX_PROPERTY'],
                    'bx_property_label' => $bxProperties[$postProperty['BX_PROPERTY']]['NAME'] ?? '',
                    'default_value' => $postProperty['DEFAULT_VALUE'] ?? null,
                ]);
            }
        }

        if($dataStores) {
            foreach ($dataStores as $postStore) {
                if(!$postStore['BX_STORE_ID'] || !$postStore['WB_STORE_ID']) {
                    continue;
                }

                $result[self::GROUP_STORES][$postStore['BX_STORE_ID']] = [
                    'bx_store_id' =>  $postStore['BX_STORE_ID'],
                    'wb_store_id' => $postStore['WB_STORE_ID']
                ];
            }
        }

        $this->mapToSave = $result;

        return true;
    }

    /**
     * Получить массив данных сопоставлений по разделам и свойствам
     *
     * @param boolean $encoding Нужно кодировать в кодировку сайта
     * @param string $byKey Helper. Символьный код поля которое будет выступать в качестве ключа
     * @return array
     */
    public function getValues(bool $encoding = false, string $byKey = null): array
    {
        $map = $this->getMapStructure();

        if (!isset($map[self::GROUP_SECTIONS]) || !$map[self::GROUP_SECTIONS]) {
            return [];
        }

        $result = [];
        foreach ($map[self::GROUP_SECTIONS] as $sectionId => $value) {
            $result[$sectionId] = [
                'BX_SECTION' => (int)$sectionId,
                'WB_SECTION' => $value['object'],
                'PROPERTIES' => [],
            ];

            $properties = [];
            foreach ($value['properties'] as $property) {
                $resultValue = [
                    'BX_PROPERTY' => $property['bx_property_code'],
                    'WB_PROPERTY' => $property['code'] ?? $property['type'],
                    'DEFAULT_VALUE' => $property['default_value'],
                ];

                $resultKey = $byKey && $resultValue[$byKey] ? $resultValue[$byKey] : null;

                if ($resultKey) {
                    $properties[$resultKey] = $resultValue;
                } else {
                    $properties[] = $resultValue;
                }
            }

            $result[$sectionId]['PROPERTIES'] = $properties;
        }

        if ($encoding) {
            return \Bitrix\Main\Text\Encoding::convertEncoding($result, 'UTF-8', LANG_CHARSET);
        }

        return $result;
    }

    /**
     * Получить массив данных сопоставлений по складам
     *
     * @param string $byKey Helper. Символьный код поля которое будет выступать в качестве ключа
     * @return array
     */
    public function getStoreValues(string $byKey = 'BX_STORE_ID'): array
    {
        $map = $this->getMapStructure();

        if(!isset($map[self::GROUP_STORES]) || !$map[self::GROUP_STORES]) {
            return  [];
        }

        $result = [];
        foreach ($map[self::GROUP_STORES] as $bxStoreId => $value) {
            $resultValue = [
                'BX_STORE_ID' => (int)$value['bx_store_id'],
                'WB_STORE_ID' => (int)$value['wb_store_id'],
            ];

            $resultKey = $byKey && $resultValue[$byKey] ? $resultValue[$byKey] : null;

            $result[$resultKey] = $resultValue;
        }

        return  $result;
    }

    /**
     * Вернуть список свойств сервиса по названию раздела
     *
     * @param string $sectionName Название раздела
     * @param boolean $withSectionInfo Если нужно название корневой категории
     * @return array
     */
    protected function getSectionProperties(string $sectionName, $withSectionInfo = false): array
    {
        return $this->adapter->getServiceCategoryProperties($sectionName, $withSectionInfo);
    }
}