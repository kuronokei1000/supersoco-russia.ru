<?php

namespace Aspro\Lite\Marketplace\Run\Export_ozon;

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\SectionTable;

use Aspro\Lite\Traits\Serialize;

class Goods extends Base
{
    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->entity = '\Aspro\Lite\Marketplace\Models\Ozon\GoodsTable';
    }

    protected function processItems(array $items)
    {
        
        if (!$items) {

            $this->reset();

            /* hack reindex */
            $catalogReindex = new \CCatalogProductAvailable('', 0, 0);
			$catalogReindex->initStep(0, 0, 0);
			$catalogReindex->setParams(array('IBLOCK_ID' => $this->iblockId));
			$catalogReindex->run();
            /* */

            return;
        }

        foreach ($items as $item) {
            $this->processRow($item);

            $this->setLastId($item->getId());
        }
    }

    private function processRow(object $row)
    {
        $items = Serialize::unserialize($row->getValue());

        foreach ($items as $item) { 
            $this->processItem($item);
        }

        $this->setProcessedCount(count($items));
    }

    private function processItem(array $item): int
    {
        $id = 0;

        $fields = [
            'IBLOCK_ID' => $this->iblockId,
            'XML_ID' => $this->getXmlId($item['id']),
            'ACTIVE' => $item['visible'],
            'NAME' => $item['name'],
            'PREVIEW_TEXT_TYPE' => 'html',
            'PREVIEW_TEXT' => $item['props']['PREVIEW_TEXT'],
            'IBLOCK_SECTION_ID' => $this->getSectionIdByXmlId($item['category_id']),
            'CODE' => \CUtil::translit($item['name'], 'ru'),
        ];

        $maxPreviewLen = 400;
        if (strlen($item['props']['PREVIEW_TEXT']) > $maxPreviewLen) {
            $obParser = new \CTextParser;
            $fields['PREVIEW_TEXT'] = $obParser->html_cut($item['props']['PREVIEW_TEXT'], $maxPreviewLen);

            $fields['DETAIL_TEXT_TYPE'] = 'html';
            $fields['DETAIL_TEXT'] = $item['props']['PREVIEW_TEXT'];
        }
        
        if ($element = $this->getExistsItem($item)) {
            $id = $element['ID'];
            $this->updateItem($id, $fields);
        } else {
            $fields['PREVIEW_PICTURE'] = $fields['DETAIL_PICTURE'] = $this->getImageFromItem($item);
            $fields['PROPERTY_VALUES'] = $this->getItemProps($item);

            $id = $this->addItem($fields);
        }

        if ($id) {
            $this->actionProduct($id, $item);
        }

        return $id;
    }

    private function getExistsItem(array $item)
    {
        $filter = [
            'IBLOCK_ID' => $this->iblockId,
            // 'ACTIVE' => 'Y',
            'XML_ID' => $this->getXmlId($item['id'])
        ];

        $element = ElementTable::getList([
            'filter' => $filter
        ])->fetch();

        return $element;
    }

    private function getSectionIdByXmlId($xmlId): int
    {
        $id = 0;
        if ($result = SectionTable::getList([
            'filter' => [
                'IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y',
                'XML_ID' => $this->getXmlId($xmlId)
            ]
        ])->fetch()) {
            $id = $result['ID'];
        }
        return $id;
    }

    private function getItemProps($item)
    {
        $props = [];

        if ($item['offer_id']) {
            $props['CML2_ARTICLE'] = $item['offer_id'];
        }
        
        if ($item['images'] && is_array($item['images'])) {
            // very slow
            if (count($item['images']) > 1) {
                unset($item['images'][0]);
                $images = array_slice($item['images'], 0, 5);
                foreach ($images as $key => $image) {
                    $props['MORE_PHOTO']['n'.$key] = \CFile::MakeFileArray($image['file_name']);
                }
            }
        }
        
        if ($item['props']['BRAND']) {
            $props['BRAND'] = $item['props']['BRAND'];
        }
        
        //fbo ID
        if ($item['fbo']) {
            $props['OZON_FBO'] = $item['fbo'];
        }
        
        //fbs ID
        if ($item['fbs']) {
            $props['OZON_FBS'] = $item['fbs'];
        }

        return $props;
    }

    private function getImageFromItem($item)
    {
        $file = [];

        if ($item['images']) {
            $file  = \CFile::MakeFileArray($item['images'][0]['file_name']);
        }

        return $file;
    }

    private function addItem($fields): int
    {
        // $result = ElementTable::add($fields); // error
        // print_r($result->getErrorMessages());

        $el = new \CIBlockElement;
        $id = $el->add($fields);
        //print_r($el->LAST_ERROR);

        return $id;
    }

    private function updateItem($id, $fields): int
    {
        // $result = ElementTable::update($fields); // error
        // print_r($result->getErrorMessages());

        $el = new \CIBlockElement;
        $id = $el->update($id, $fields);
        //print_r($el->LAST_ERROR);
        
        return $id;
    }

    private function actionProduct($productId, $fields): bool
    {
        $catalogFields = [
            'ID' => $productId,
            'WEIGHT' => $fields['weight'],
            'LENGTH' => $fields['depth'],
            'HEIGHT' => $fields['height'],
            'WIDTH' => $fields['width'],
            'QUANTITY' => $fields['stocks']['present']
        ];

        if ($measureId = $this->getMeasureIdByCode($fields['weight_unit'])) {
            $catalogFields['MEASURE'] = $measureId;
        }
        
        $result = \CCatalogProduct::Add($catalogFields);

        $this->addPrice($productId, $fields['prices']);

        return $result;
    }

    private function getMeasureIdByCode($code)
    {
        $id = 0;

        $measure = \CCatalogMeasure::getList(
            array(),
            array('SYMBOL_INTL' => $code),
            false,
            false,
            array('ID')
        )->fetch();

        if ($measure) {
            $id = $measure['ID'];
        }

        return $id;
    }
    
    private function addPrice($productId, $price): int
    {
        $id = 0;

        $fields = [
            'PRODUCT_ID' => $productId,
            'PRICE' => $price['price'],
            'PRICE_SCALE' => $price['price'],
            'CATALOG_GROUP_ID' => $this->priceTypeId ?: 1, // BASE
            'CURRENCY' => $price['currency_code'] ?: 'RUB', // RUB
        ];
        
        $result = \Bitrix\Catalog\Model\Price::add($fields);
        // echo $result->getErrorMessages();

        if ($result->isSuccess()) {
            $id = $result->getId();
        }

        return $id;
    }
}
