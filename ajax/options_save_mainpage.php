<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if( (
		isset($_POST['NAME']) 
			&& $_POST['NAME']
	) && (
		isset($_POST['VALUE']) 
		&& strlen($_POST['VALUE'])
	)
)
{
	if(\Bitrix\Main\Loader::includeModule('aspro.lite'))
	{
		$_SESSION['THEME'][SITE_ID][$_POST['NAME']] = $_POST['VALUE'];
	}
}
