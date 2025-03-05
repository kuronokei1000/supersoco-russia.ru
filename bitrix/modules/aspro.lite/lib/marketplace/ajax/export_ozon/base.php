<?php

namespace Aspro\Lite\Marketplace\Ajax\Export_ozon;

use \Bitrix\Main\Application,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Web\Json;

use \Aspro\Lite\Marketplace\Summary;

abstract class Base extends \Aspro\Lite\Marketplace\Ajax\Ozon
{
    protected $type = ''; // check in request

    protected $entity = ''; // ORM Table

    protected $summary = null; // summary info

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->type = strtolower((new \ReflectionClass($this))->getShortName());
        $this->request = Application::getInstance()->getContext()->getRequest();

        $this->summary = new Summary();
    }

    public function import()
    {
        $this->checkTable();
        $this->execute();
    }

    abstract public function checkTable();

    private function execute()
    {
        $this->progress();
        $this->action();
    }

    private function progress()
    {
        if (
            (!$this->getValues()->getAll() && !$this->request->get('action'))
            || ($this->request->get('action') === 'sync' && $this->request->get('stage') === $this->type)
        ) {
            if ($this->request->get('action') === 'sync') {
                $this->removeValues();
            }

            $this->html();

            die();
        }

        $this->getSummary();
    }

    public function getValues($arConfig = [])
    {
        $filter = [
            'CLIENT_ID' => $this->adapter->getServiceClientId()
        ];

        if (!$arConfig['filter']) {
            $arConfig['filter'] = $filter;
        } else {
            $arConfig['filter'] += $filter;
        }

        $arResult = $this->entity::getList($arConfig)->fetchCollection();
        return $arResult;
    }

    abstract protected function html();

    private function getSummary()
    {
        $this->summary->beginGroup($this->type, Loc::getMessage('STATUS_'.$this->type.'_IMPORT'));
        $this->summary();
    }
    abstract protected function summary();

    abstract protected function action();

    public function checkRequest($request): bool
    {
        if (!parent::checkRequest($request)) {
            return false;
        }

        if ($this->equalType()) {
            return true;
        }

        return false;
    }

    private function equalType()
    {
        return $this->request->get('type') === $this->type;
    }

    protected function setValues($arValues)
    {
        $arResult = $this->entity::add($arValues);
        return $arResult;
    }

    public function removeValues()
    {
        $arValues = $this->getValues();

        foreach ($arValues as $arValue) {
            $arValue->delete();
        }

        return true;
    }

    public function showSummary()
    {
        echo $this->summary->getGroupSummary($this->type);
    }
}
