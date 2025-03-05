<?php

namespace Aspro\Lite\Marketplace\Run\Export_ozon;

use \Bitrix\Iblock\SectionTable;

class Sections extends Base
{
    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->entity = '\Aspro\Lite\Marketplace\Models\Ozon\SectionsTable';
        $this->limit = 10;
    }

    protected function processItems(array $items)
    {
        if (!$items) {
            $this->reset();

            /* hack resort */
            \CIBlockSection::ReSort($this->iblockId);
            /* */

            return;
        }

        foreach ($items as $item) {
            $this->processItem($item);

            $this->setLastId($item->getId());
        }

        $this->setProcessedCount(count($items));

        //
    }

    private function processItem(object $item): int
    {
        if ($section = $this->getExistsItem($item)) return $section['ID'];

        $fields = [
            'IBLOCK_ID' => $this->iblockId,
            'XML_ID' => $this->getXmlId($item['OZON_ID']),
            'NAME' => $item['TITLE'],
            'TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime(),
            'CODE' => \CUtil::translit($item['TITLE'], 'ru')
        ];

        $fields = array_merge($fields, $this->processParentItem($item));

        return $this->addItem($fields);
    }

    private function getExistsItem(object $item)
    {
        $filter = [
            'IBLOCK_ID' => $this->iblockId,
            'ACTIVE' => 'Y',
            'XML_ID' => $this->getXmlId($item['OZON_ID'])
        ];

        $section = SectionTable::getList([
            'filter' => $filter
        ])->fetch();

        return $section;
    }

    private function processParentItem(object $item): array
    {
        $result = [];
        if ($item['PARENT_ID']) {
            $itemTable = current($this->getItems([
                'filter' => [
                    'OZON_ID' => $item['PARENT_ID']
                ]
            ]));
            if ($itemTable) {
                $sectionId = $this->processItem($itemTable);
                if ($sectionId) {
                    $result = [
                        'IBLOCK_SECTION_ID' => $sectionId
                    ];
                }
            }
        }
        return $result;
    }

    private function addItem($fields): int
    {
        $id = 0;

        $result = SectionTable::add($fields);
        // print_r($result->getErrorMessages());
        
        if ($result->isSuccess()) {
            $id = $result->getId();
        }

        return $id;
    }
}
