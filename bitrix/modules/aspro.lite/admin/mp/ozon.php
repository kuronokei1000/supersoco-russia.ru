<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

use \Bitrix\Main\Config\Option,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Application,
    \Aspro\Lite\Marketplace\Config\Ozon as Config;

$request = Application::getInstance()->getContext()->getRequest();

if (!$request->isAjaxRequest()) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
}

$moduleClass = 'CLite';
$moduleID = 'aspro.lite';

\Bitrix\Main\Loader::includeModule($moduleID);

$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage("ASPRO_MAX_PAGE_TITLE"));
$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/" . $moduleID . "/style.css");

$RIGHT = $APPLICATION->GetGroupRight($moduleID);

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

if ($RIGHT < 'R') {
    echo CAdminMessage::ShowMessage('No rights for viewing');

    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');

    return;
}
?>
<?
$request = Application::getInstance()->getContext()->getRequest();

if ($request->getRequestMethod() == 'POST' && $request->get('Save') === 'true' && $RIGHT >= 'W' && check_bitrix_sessid()) {
    $formGeneralData = $request->get('GENERAL_SETTINGS');

    if ($formGeneralData) {
        Option::set(Config::MODULE, Config::OPTION_API_KEY, $formGeneralData['API_KEY']);
        Option::set(Config::MODULE, Config::OPTION_CLIENT_ID, $formGeneralData['CLIENT_ID']);
        Option::set(Config::MODULE, Config::OPTION_LOG_TIME, $formGeneralData['LOG_TIME']);
    }
}

?>

<?php
$aTabs = [
    [
        'DIV' => 'ozon-setting',
        'TAB' => Loc::getMessage('AS_SETTING_TAB'),
        'TITLE' => Loc::getMessage('AS_SETTING_TAB_TITLE')
    ],
];

$tabControl = new \CAdminTabControl('tabControl', $aTabs, true, true);
?>
    <form
        method="post"
        enctype="multipart/form-data"
        action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>"
    >
        <?= bitrix_sessid_post(); ?>
        <input type="hidden" name="Save" value="true">

        <? $tabControl->Begin(); ?>
        <? $tabControl->BeginNextTab(); ?>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?= Loc::getMessage('AS_FORM_LABEL_API_KEY') ?>:
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <input
                    name="GENERAL_SETTINGS[API_KEY]"
                    value="<?= Option::get(Config::MODULE, Config::OPTION_API_KEY) ?>"
                    style="width: 80%"
                    type="text"
                >
                <a href="https://seller.ozon.ru/app/settings/api-keys" target="_blank"
                   style="margin-left: 10px">
                    <?= Loc::getMessage('AS_LINK_API_KEY_INFO') ?>
                </a>
            </td>
        </tr>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?= Loc::getMessage('AS_FORM_LABEL_CLIENT_ID') ?>:
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <input
                    name="GENERAL_SETTINGS[CLIENT_ID]"
                    value="<?= Option::get(Config::MODULE, Config::OPTION_CLIENT_ID) ?>"
                    style="width: 80%"
                    type="text"
                >
            </td>
        </tr>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?= Loc::getMessage('AS_FORM_LABEL_LOG_TIME') ?>:
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <input
                    name="GENERAL_SETTINGS[LOG_TIME]"
                    value="<?= Option::get(Config::MODULE, Config::OPTION_LOG_TIME, "14") ?>"
                    style="width: 80%"
                    type="text"
                >
            </td>
        </tr>
        <?php
        $tabControl->EndTab();
        $tabControl->Buttons(array());
        $tabControl->End();
        ?>
    </form>
<?
if (!$request->isAjaxRequest()) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
}
?>