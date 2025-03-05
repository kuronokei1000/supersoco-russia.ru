<?php

namespace Aspro\Lite\Marketplace\Tabs;

use Aspro\Lite\Marketplace\Config\Wildberries as Config;

class Wildberries
{
    static protected $properties = [];

    static public function eventHandler()
    {
        return [
            'TABSET' => 'Wildberries',
            'GetTabs' => [__CLASS__, 'getTabs'],
            'ShowTab' => [__CLASS__, 'getTabContent'],
            'Action' => [__CLASS__, 'onSave'],
            'Check' => [__CLASS__, 'onBeforeSave'],
        ];
    }

    static public function onSave(array $payload): bool
    {
        return true;
    }

    static public function onBeforeSave(array $payload): bool
    {
        return true;
    }

    static public function getTabs(array $payload): ?array
    {
        $showTab = false;

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        if ($payload['ID'] > 0 && (!isset($request['action']) || $request['action'] != 'copy')) {
            $showTab = true;
            if (\Bitrix\Main\Loader::includeModule('catalog')) {
                if (\CCatalogSku::getInfoByOfferIBlock($payload['IBLOCK']['ID']) !== false) {
                    $showTab = false;
                }
            }
        }

        if (!self::getProperties($payload)) {
            $showTab = false;
        }

        return $showTab ? [
            [
                'DIV' => 'mp-wb-edit1', 'TAB' => 'Wildberries',
                'ICON' => 'sale', 'TITLE' => 'Wildberries',
                'SORT' => 1
            ]
        ] : null;
    }

    static public function getTabContent($tabCode, array $payload, $isForm)
    {
        $properties = self::getProperties($payload);

        if ($tabCode == 'mp-wb-edit1') {
            include __DIR__ . '/view/mp-wb-edit1.php';
        }
    }

    static protected function getProperties($payload): array
    {
        if (self::$properties) {
            return self::$properties;
        }

        $properties = Config::getElementSystemProperties($payload['IBLOCK']['ID'], $payload['ID']);

        self::$properties = $properties;

        return self::$properties;
    }
}