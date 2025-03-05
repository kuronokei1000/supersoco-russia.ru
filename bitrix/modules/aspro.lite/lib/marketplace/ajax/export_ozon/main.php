<?php

namespace Aspro\Lite\Marketplace\Ajax\Export_ozon;

use \Bitrix\Main\Application;

class Main
{
    private $config = [];
    private $entities = [];

    public function __construct(...$args)
    {
        $this->config = $args;
    }

    public function execute()
    {
        $this->importGoods();
        $this->importSections();
    }
    
    private function importGoods()
    {
        $goods = new Goods(...$this->config);
        $goods->import();

        $this->entities[] = $goods;
    }
    
    private function importSections()
    {
        $sections = new Sections(...$this->config);
        $sections->import();

        $this->entities[] = $sections;
    }

    public function showSummary() {
        foreach ($this->entities as $entity) {
            $entity->showSummary();
        }
    }
}