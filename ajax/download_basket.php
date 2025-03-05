<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$arParams = isset($_REQUEST['params']) && is_array($_REQUEST['params']) ? $_REQUEST['params'] : array();

include __DIR__.'/../include/comp_basket_file_wrapper.php';
