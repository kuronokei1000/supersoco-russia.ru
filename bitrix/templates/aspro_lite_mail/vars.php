<?
Bitrix\Main\Loader::includeModule('aspro.lite');
$siteId = Bitrix\Main\Mail\EventMessageThemeCompiler::getInstance()->getSiteId();
extract(Aspro\Lite\Sender\Preset\Template::getVars($siteId));
