<?php

namespace Aspro\Lite\Marketplace\Ajax\Export_ozon;

use \Bitrix\Main\Application,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Web\Json;

use \Aspro\Lite\Marketplace\Summary;

\Bitrix\Main\Loader::includeModule('iblock');

class Props
{
    private $iblockId = null;
    private $props = [];

    protected $summary = null; // summary info

    public function __construct($iblockId, array $additionalProps = [])
    {
        $this->iblockId = $iblockId;
        
        $this->init($additionalProps);

        $this->summary = new Summary();
    }

    private function init(array $props)
    {
        $this->props = array_merge([
            [
                'CODE' => 'CML2_ARTICLE',
                'NAME' => Loc::getMessage('PROP_CML2_ARTICLE_TITLE'),
                'PROPERTY_TYPE' => 'S'
            ],
            [
                'CODE' => 'MORE_PHOTO',
                'NAME' => Loc::getMessage('PROP_MORE_PHOTO_TITLE'),
                'PROPERTY_TYPE' => 'F',
                'MULTIPLE' => 'Y'
            ],
            [
                'CODE' => 'BRAND',
                'NAME' => Loc::getMessage('PROP_BRAND_TITLE'),
                'PROPERTY_TYPE' => 'S'
            ],
            [
                'CODE' => 'OZON_FBO',
                'NAME' => Loc::getMessage('PROP_OZON_FBO_TITLE'),
                'PROPERTY_TYPE' => 'S'
            ],
            [
                'CODE' => 'OZON_FBS',
                'NAME' => Loc::getMessage('PROP_OZON_FBS_TITLE'),
                'PROPERTY_TYPE' => 'S'
            ],
        ], $props);
    }

    public function create()
    {
        foreach ($this->props as $prop) {
            $this->processProp($prop);
        }
    }

    private function processProp(array $prop)
    {
        $this->summary->beginGroup($prop['CODE'], $prop['NAME']);
        
        if ($this->isExists($prop)) {
            $this->summary->add(Loc::getMessage('PROP_EXISTS', ['#CODE#' => $prop['CODE']]));
            return;
        }
        if ($this->addProp($prop)) {
            $this->summary->add(Loc::getMessage('PROP_ADDED', ['#CODE#' => $prop['CODE']]));
        }

        $this->summary->endGroup();
    }

    private function isExists($prop)
    {
        $prop = \CIBlockProperty::GetList([],[
            'IBLOCK_ID' => $this->iblockId,
            'CODE' => $prop['CODE']
        ])->SelectedRowsCount();

        return $prop;
    }
    
    private function addProp($prop)
    {
        $fields = array_merge([
            'ACTIVE' => 'Y',
            'SORT' => '100',
            'IBLOCK_ID' => $this->iblockId,
        ], $prop);

        $property = new \CIBlockProperty;
        $id = $property->Add($fields);

        return $id;
    }

    public function checkAll()
    {
        $this->summary->beginGroup('check');

        foreach ($this->props as $prop) {
            $this->checkProp($prop);
        }
    }
    
    private function checkProp($prop)
    {
        if (!$this->isExists($prop)) {
            $this->summary->add(Loc::getMessage('PROP_DOESNT_EXISTS', [
                '#PROP#' => $prop['NAME'],
                '#CODE#' => $prop['CODE'],
            ]));
        }
    }

    public function checkSummary()
    {
        return $this->summary->hasItems();
    }

    public function showSummary($delimiter = '')
    {
        echo $this->summary->getGroupsSummary($delimiter);
    }

    public function getSummary($delimiter = '')
    {
        return $this->summary->getGroupsSummary($delimiter);
    }
}
