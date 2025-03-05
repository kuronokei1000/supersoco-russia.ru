<?
use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	throw new SystemException('Error include solution constants');
}

if (!Loader::includeModule(VENDOR_MODULE_ID)) {
	throw new SystemException('Error include module '.VENDOR_MODULE_ID);
}

$component = new CBitrixComponent;
$component->initComponent('bitrix:catalog.section');

TSolution\Extensions::register();

include $_SERVER['DOCUMENT_ROOT'].$component->__path.'/ajax.php';
