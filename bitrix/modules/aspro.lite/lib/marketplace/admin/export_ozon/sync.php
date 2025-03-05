<?php
/**
 * @global CMain $APPLICATION
 */
define('STOP_STATISTICS', true);
define('BX_SECURITY_SHOW_MESSAGE', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

use
    \Bitrix\Main\Application,
    \Bitrix\Main\Localization\Loc,
    \Aspro\Lite\Marketplace\Config\Ozon as Config;

$request = Application::getInstance()->getContext()->getRequest();

if (!check_bitrix_sessid() || !$GLOBALS['USER']->isAdmin()) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}

$clientId = Config::getClientId();
if ($request->get('GET_CLIENT_ID_FROM_MODULE') !== 'Y') {
    $clientId = $request->get('CLIENT_ID');
}

$token = Config::getApiKey();
if ($request->get('GET_API_KEY_FROM_MODULE') !== 'Y') {
    $token = $request->get('API_KEY');
}

$iblockId = (int)$request->get('IBLOCK_ID');
$sessid = str_replace('sessid=', '', bitrix_sessid_get());

if (!$clientId) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    ShowError(GetMessage('AS_ERROR_CLIENT_ID'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}
if (!$token) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    ShowError(GetMessage('AS_ERROR_API_KEY'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}

$sync = new \Aspro\Lite\Marketplace\Ajax\Export_ozon\Main($clientId, $token);
$sync->execute();

$APPLICATION->SetTitle(Loc::getMessage('AS_MAPPING_SECTIONS_AND_ITEMS_SETUP'));
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$aTabs = [
    [
        'DIV' => 'wb-setting-map',
        'TAB' => Loc::getMessage('AS_MAPPING_SETUP_STATUS'),
        'TITLE' => Loc::getMessage('AS_MAPPING_SETUP_STATUS'),
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

    <?$sync->showSummary()?>

    <?$tabControl->EndTab();
    // $tabControl->Buttons(array());
    // $tabControl->End();
    ?>
</form>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/epilog_admin.php') ?>