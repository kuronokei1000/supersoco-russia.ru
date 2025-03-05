<?php

namespace Aspro\Lite\Marketplace\Config;

class Base
{
    const MODULE = 'aspro.lite';

    public static function isIblockModule(): bool
    {
        return \Bitrix\Main\Loader::includeModule('iblock');
    }

    public static function isCatalogModule(): bool
    {
        return \Bitrix\Main\Loader::includeModule('catalog');
    }

    public static function isSaleModule(): bool
    {
        return \Bitrix\Main\Loader::includeModule('sale');
    }
}