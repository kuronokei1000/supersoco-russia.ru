<?php

namespace Aspro\Lite\Marketplace\Run\Export_ozon;


abstract class Base
{
    protected $iblockId = null;
    protected $clientId = null;

    protected $entity = null;

    protected $lastId = null; // last processed item
    protected $limit = 1; // count selected items from BD

    protected $type = 'ozon'; // for prefix XML_ID items and sections

    protected $priceTypeId = 1; // BASE

    public function __construct($iblockId, $clientId, $lastId = null, $priceTypeId = 1)
    {
        $this->iblockId = $iblockId;
        $this->clientId = $clientId;

        $this->lastId = $lastId;
        $this->priceTypeId = $priceTypeId;
    }

    public function import()
    {
        $this->action();
    }

    protected function action()
    {
        $items = $this->getItems([
            'filter' => [
                '>ID' => $this->lastId,
                'CLIENT_ID' => $this->clientId
            ]
        ]);

        $this->processItems($items);
    }

    protected function getItems($arConfig = [])
    {
        $arMergedConfig = array_merge([
            'limit' => $this->limit
        ], $arConfig);

        $arResult = $this->entity::getList($arMergedConfig)->fetchCollection()->getAll();

        return $arResult;
    }

    abstract protected function processItems(array $items);

    protected function reset()
    {
        $this->setLastId(null);
        $this->setProcessedCount(0);
    }

    protected function setLastId($value)
    {
        $this->lastId = $value;
    }

    protected function getLastId()
    {
        return $this->lastId;
    }

    protected function setProcessedCount($count)
    {
        if (!$_SESSION[$this->entity]) {
            $_SESSION[$this->entity] = 0;
        }
        if (!$count) {
            $_SESSION[$this->entity] = 0;
        } else {
            $_SESSION[$this->entity] += $count;
        }
    }

    protected function getProcessedCount()
    {
        return (int)$_SESSION[$this->entity];
    }

    protected function getXmlId($xmlId)
    {
        return $this->type . '_' . $xmlId;
    }

    public function getNextInfo()
    {
        return [
            'lastId' => $this->getLastId(),
            'processed' => $this->getProcessedCount()
        ];
    }
}
