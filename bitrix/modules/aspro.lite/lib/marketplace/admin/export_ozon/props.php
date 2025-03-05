<?php
/**
 * @global CMain $APPLICATION
 */
define('STOP_STATISTICS', true);
define('BX_SECURITY_SHOW_MESSAGE', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

use \Bitrix\Main\Application,
    \Bitrix\Main\Localization\Loc;

use Aspro\Lite\Marketplace\Ajax\Export_ozon\Props;

$request = Application::getInstance()->getContext()->getRequest();

if (!check_bitrix_sessid() || !$GLOBALS['USER']->isAdmin()) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}

$iblockId = (int)$request->get('IBLOCK_ID');
$sessid = str_replace('sessid=', '', bitrix_sessid_get());

if (!$iblockId) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    ShowError(GetMessage('AS_ERROR_IBLOCK_ID'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}

$props = new Props($iblockId);
$props->create();

$APPLICATION->SetTitle(Loc::getMessage('AS_PROPERTIES'));
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$aTabs = [
    [
        'DIV' => 'wb-setting-map',
        'TAB' => Loc::getMessage('AS_PROPERTIES_TITLE'),
        'TITLE' => Loc::getMessage('AS_PROPERTIES_TITLE'),
    ],
];
?>
<form name="wb_form" method="POST" action="<?= $APPLICATION->GetCurPage(); ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">
    <input type="hidden" name="bxpublic" value="Y">
    <input type="hidden" name="Update" value="Y"/>
    <input type="hidden" name="IBLOCK_ID" value="<? echo $iblockId; ?>"/>
    <input type="hidden" name="API_KEY" value="<? echo $token; ?>"/>
    <input type="hidden" name="CLIENT_ID" value="<? echo $clientId; ?>"/>
    <? echo bitrix_sessid_post(); ?>
    <input type="hidden" name="SaveMap" value="true">

    <?php
    $tabControl = new \CAdminTabControl('tabControl', $aTabs, true, true);

    $tabControl->BeginNextTab();?>

    <?$props->showSummary()?>

    <?$tabControl->EndTab();
    // $tabControl->Buttons(array());
    // $tabControl->End();
    ?>
</form>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/epilog_admin.php') ?>