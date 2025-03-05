<?php

namespace Aspro\Lite\Marketplace\Ajax;

use \Bitrix\Main\Application;
use \Aspro\Lite\Marketplace\Adapters\Ozon as Adapters;

use \Aspro\Lite\Marketplace\Models\Ozon\PropValuesTable;

class Ozon
{
    protected $alias = 'ozon';

    protected $request = null;

    public $adapter = null;

    public function __construct($client, $token)
    {
        $this->adapter = new Adapters($client, $token);
    }

    public function checkRequest($request): bool
    {
        if (!$request->isAjaxRequest()) {
            return false;
        }
        if ($request->get('controller') === $this->alias) {
            return true;
        }

        return false;
    }

    public function checkPropsTable()
    {
        if(!PropValuesTable::getEntity()->getConnection()->isTableExists(PropValuesTable::getTableName())){
			// PropValuesTable::getEntity()->createDbTable();
           $err = $GLOBALS['DB']->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/aspro.lite/lib/marketplace/db/{$this->alias}/installPropValues.sql");
           if ($err) {
               $GLOBALS['APPLICATION']->ThrowException(implode("", $err));
           }
		}
    }

    public function getPropValues($categoryId, $propId, $step = 1)
    {
        $arResult = PropValuesTable::getList(array(
            'filter' => array(
            	'=CATEGORY_ID' => $categoryId,
            	'=PROPERTY_ID' => $propId
            ),
            'order' => array('ID' => 'ASC'),
            // 'limit' => 1,
            'select' => array('ID', 'VALUE'),
        ))->fetchCollection();

        return $arResult;
    }
    
    public function removePropValues($categoryId, $propId)
    {
        $arValues = $this->getPropValues($categoryId, $propId);

        foreach ($arValues as $arValue) {
            $arValue->delete();
        }

        return true;
    }
    
    public function appendPropValues($id, $arValues)
    {
        $arResult = PropValuesTable::update($id, $arValues);

        return $arResult;
    }
    
    public function setPropValues($arValues)
    {
        $arResult = PropValuesTable::add($arValues);

        return $arResult;
    }
    
    public function searchValues($obPropValues, $value)
    {
        $arValues = [];
        $value = trim($this->adapter->encoding($value));

        foreach ($obPropValues as $obPropValue) {
            $arValues = array_merge($arValues, $this->getFieldValues($obPropValue));
        }

        $arFilteredValues = array_filter($arValues, function($arValue) use ($value) {
            return strpos(mb_strtolower($arValue['text']), mb_strtolower($value)) !== false;
        });
        
        \Bitrix\Main\Type\Collection::sortByColumn($arFilteredValues, 'text');

        return $arFilteredValues;
    }
    
    public function getTextById($obPropValues, $id): string
    {
        $text = '';
        foreach ($obPropValues as $obPropValue) {
            $arValues = $this->getFieldValues($obPropValue);
            if (($key = array_search($id, array_column($arValues, 'id'))) !== false) {
                $text = $arValues[$key]['text'];
                break;
            }
        }
        return $text;
    }
    
    public function getFieldValues($obProp): array
    {
        return unserialize($obProp->getValue(), ['allowed_classes' => false]);
    }

    public function addPropValues($categoryId, $propId, $last_value_id, $step = 1)
    {
        $arResult = $this->adapter->getServicePropertyValues($categoryId, $propId, $last_value_id, 5000);
        if ($arResult['has_next']) {
            $arResult['last_value_id'] = end($arResult['result'])['id'];
        }

        $arSetValues = [
            'CATEGORY_ID' => $categoryId,
            'PROPERTY_ID' => $propId,
            'STEP' => $step,
            'VALUE' => array_map(function($item){
                return ['id' => $item['id'], 'text' => $item['value']];
            }, $arResult['result']),
        ];

        $this->setPropValues($arSetValues);

        return $arResult;
    }

    public function getCategories(): array
    {
        
        $categories = $this->adapter->getServiceCategories();

        $result = [];

        foreach ($categories as $category) {
            $result[$category['ID']] = [
                'id' => $category['NAME'],
                'data_id' => $category['ID'],
                'text' => $category['FULL_NAME'],
            ];
        }

        return $result;
    }
    
    public function getCategoriesForSelect($searchText): array
    {
        
        $categories = $this->getCategories();

        $searchText = $this->adapter->encoding($searchText);
        $result = array_filter($categories, fn($val) => mb_stripos($val['text'], $searchText) !== false);

         $result =  array_merge([[
            'id' => '',
            'text' => '-'
        ]], $result); 

        return $result;
        // return $this->adapter->encoding($result);
    }

    public function getCategoryProperties($categoryName): array
    {
        $properties = $this->adapter->getServiceCategoryProperties($categoryName);

        $result = [];

        // filter props
        $properties = array_filter($properties, function($prop){
            return in_array($prop['type'], $this->adapter->getIncludePropsType());
        });

        foreach ($properties as $property) {
            $result[] = [
                'code' => $property['code'],
                'name' => $property['name'],
                'dictionaryId' => $property['dictionary_id'],
                'type' => $property['type'],
                'isRequired' => $property['is_required']
            ];
        }

        return $this->adapter->encoding($result);;
    }

    public function getLimits(): array
    {
        return $this->adapter->getLimits();
    }
}