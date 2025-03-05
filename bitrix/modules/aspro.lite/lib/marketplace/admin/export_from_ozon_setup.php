<?
//<title>Wildberries</title>
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global string $ACTION */
/** @global array $arOldSetupVars */
/** @global int $IBLOCK_ID */
/** @global string $SETUP_FILE_NAME */
/** @global string $SETUP_SERVER_NAME */
/** @global mixed $V */
/** @global mixed $XML_DATA */
/** @global string $SETUP_PROFILE_NAME */

use Bitrix\Main,
    Bitrix\Iblock,
    Bitrix\Catalog,
    Bitrix\Main\Web\Json,
    \Bitrix\Main\Localization\Loc,
    \Aspro\Lite\Marketplace\Ajax\Export_ozon,
    \Aspro\Lite\Marketplace\Config\Ozon as Config;

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/catalog/export_setup_templ.php');
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

global $APPLICATION, $USER;

\CJSCore::Init(['jquery']);
\Bitrix\Main\UI\Extension::load("ui.hint");?>
    <script type="text/javascript">
        BX.ready(function() {
            BX.UI.Hint.init(BX('adm-detail-content-item-block'));
        })
    </script>
<?

$arSetupErrors = array();

$strAllowExportPath = COption::GetOptionString("catalog", "export_default_path", "/bitrix/catalog_export/");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!$IBLOCK_ID && $request->get('iblock_id')) {
    $IBLOCK_ID = $request->get('iblock_id');
}

if (($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') && $STEP == 1) {
    if (isset($arOldSetupVars['IBLOCK_ID']))
        $IBLOCK_ID = $arOldSetupVars['IBLOCK_ID'];
    if (isset($arOldSetupVars['SITE_ID']))
        $SITE_ID = $arOldSetupVars['SITE_ID'];
    if (isset($arOldSetupVars['SETUP_FILE_NAME']))
        $SETUP_FILE_NAME = str_replace($strAllowExportPath, '', $arOldSetupVars['SETUP_FILE_NAME']);
    if (isset($arOldSetupVars['COMPANY_NAME']))
        $COMPANY_NAME = $arOldSetupVars['COMPANY_NAME'];
    if (isset($arOldSetupVars['SETUP_PROFILE_NAME']))
        $SETUP_PROFILE_NAME = $arOldSetupVars['SETUP_PROFILE_NAME'];
    if (isset($arOldSetupVars['V']))
        $V = $arOldSetupVars['V'];
    if (isset($arOldSetupVars['XML_DATA'])) {
        $XML_DATA = base64_encode($arOldSetupVars['XML_DATA']);
    }
    if (isset($arOldSetupVars['API_KEY'])) {
        $API_KEY = $arOldSetupVars['API_KEY'];
    }
    if (isset($arOldSetupVars['CLIENT_ID'])) {
        $CLIENT_ID = $arOldSetupVars['CLIENT_ID'];
    }
    if (isset($arOldSetupVars['SETUP_SERVER_NAME']))
        $SETUP_SERVER_NAME = $arOldSetupVars['SETUP_SERVER_NAME'];
    if (isset($arOldSetupVars['USE_HTTPS']))
        $USE_HTTPS = $arOldSetupVars['USE_HTTPS'];
    if (isset($arOldSetupVars['FILTER_AVAILABLE']))
        $filterAvalable = $arOldSetupVars['FILTER_AVAILABLE'];

    if (isset($arOldSetupVars['USE_PRICES_WITH_DISCOUNT']))
        $usePricesWithDiscount = $arOldSetupVars['USE_PRICES_WITH_DISCOUNT'];
    if (isset($arOldSetupVars['NEED_UPLOAD_STORES']))
        $needUploadStores = $arOldSetupVars['NEED_UPLOAD_STORES'];
    if (isset($arOldSetupVars['NEED_UPLOAD_PRICES']))
        $needUploadPrices = $arOldSetupVars['NEED_UPLOAD_PRICES'];
    if (isset($arOldSetupVars['NEED_UPLOAD_PRODUCTS']))
        $needUploadProducts = $arOldSetupVars['NEED_UPLOAD_PRODUCTS'];
        
    if (isset($arOldSetupVars['PRICE_TYPE']))
        $PRICE_TYPE = $arOldSetupVars['PRICE_TYPE'];

    if (isset($arOldSetupVars['DISABLE_REFERERS']))
        $disableReferers = $arOldSetupVars['DISABLE_REFERERS'];
    if (isset($arOldSetupVars['EXPORT_CHARSET']))
        $exportCharset = $arOldSetupVars['EXPORT_CHARSET'];
    if (isset($arOldSetupVars['MAX_EXECUTION_TIME']))
        $maxExecutionTime = $arOldSetupVars['MAX_EXECUTION_TIME'];
    if (isset($arOldSetupVars['CHECK_PERMISSIONS']))
        $checkPermissions = $arOldSetupVars['CHECK_PERMISSIONS'];
    if (isset($arOldSetupVars['CUSTOM_FILTER']))
        $customFilter = $arOldSetupVars['CUSTOM_FILTER'];
    if (isset($arOldSetupVars['GET_API_KEY_FROM_MODULE']))
        $getApiKeyFromModule = $arOldSetupVars['GET_API_KEY_FROM_MODULE'];
    if (isset($arOldSetupVars['GET_CLIENT_ID_FROM_MODULE']))
        $getClientIdFromModule = $arOldSetupVars['GET_CLIENT_ID_FROM_MODULE'];
}

if ($STEP > 1) {
    $IBLOCK_ID = (int)$IBLOCK_ID;
    $rsIBlocks = CIBlock::GetByID($IBLOCK_ID);
    if ($IBLOCK_ID <= 0 || !($arIBlock = $rsIBlocks->Fetch())) {
        $arSetupErrors[] = GetMessage("AS_ERROR_IBLOCK_ID");
    } else {
        $bRightBlock = !CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, "iblock_admin_display");
        if ($bRightBlock) {
            $arSetupErrors[] = str_replace('#IBLOCK_ID#', $IBLOCK_ID, GetMessage("CET_ERROR_IBLOCK_PERM"));
        }
    }

    $SITE_ID = trim($SITE_ID);
    if ($SITE_ID === '') {
        $arSetupErrors[] = GetMessage('BX_CATALOG_EXPORT_YANDEX_ERR_EMPTY_SITE');
    } else {
        $iterator = Main\SiteTable::getList(array(
            'select' => array('LID'),
            'filter' => array('=LID' => $SITE_ID, '=ACTIVE' => 'Y')
        ));
        $site = $iterator->fetch();
        if (empty($site)) {
            $arSetupErrors[] = GetMessage('BX_CATALOG_EXPORT_YANDEX_ERR_BAD_SITE');
        }
    }
   
    $SETUP_SERVER_NAME = (isset($SETUP_SERVER_NAME) ? trim($SETUP_SERVER_NAME) : '');
    $COMPANY_NAME = (isset($COMPANY_NAME) ? trim($COMPANY_NAME) : '');

    $arCatalog = CCatalogSku::GetInfoByIBlock($IBLOCK_ID);
    if (CCatalogSku::TYPE_PRODUCT == $arCatalog['CATALOG_TYPE'] || CCatalogSku::TYPE_FULL == $arCatalog['CATALOG_TYPE']) {
//        if (!isset($XML_DATA) || $XML_DATA == '') {
//            $arSetupErrors[] = GetMessage('YANDEX_ERR_SKU_SETTINGS_ABSENT');
//        }
    }

    if (!isset($USE_HTTPS) || $USE_HTTPS != 'Y')
        $USE_HTTPS = 'N';
    if (isset($_POST['FILTER_AVAILABLE']) && is_string($_POST['FILTER_AVAILABLE']))
        $filterAvalable = $_POST['FILTER_AVAILABLE'];
    if (!isset($filterAvalable) || $filterAvalable != 'Y')
        $filterAvalable = 'N';

    if (isset($_POST['GET_API_KEY_FROM_MODULE']) && is_string($_POST['GET_API_KEY_FROM_MODULE']))
        $getApiKeyFromModule = $_POST['GET_API_KEY_FROM_MODULE'];
    if (!isset($getApiKeyFromModule) || $getApiKeyFromModule != 'Y')
        $getApiKeyFromModule = 'N';
        
    if (isset($_POST['GET_CLIENT_ID_FROM_MODULE']) && is_string($_POST['GET_CLIENT_ID_FROM_MODULE']))
        $getClientIdFromModule = $_POST['GET_CLIENT_ID_FROM_MODULE'];
    if (!isset($getClientIdFromModule) || $getClientIdFromModule != 'Y')
        $getClientIdFromModule = 'N';
    
    /*

    if (isset($_POST['NEED_UPLOAD_PRICES']) && is_string($_POST['NEED_UPLOAD_PRICES']))
        $needUploadPrices = $_POST['NEED_UPLOAD_PRICES'];
    if (!isset($needUploadPrices) || $needUploadPrices != 'Y')
        $needUploadPrices = 'N';

    if (isset($_POST['NEED_UPLOAD_PRODUCTS']) && is_string($_POST['NEED_UPLOAD_PRODUCTS']))
        $needUploadProducts = $_POST['NEED_UPLOAD_PRODUCTS'];
        if (!isset($needUploadProducts) || $needUploadProducts != 'Y')
        $needUploadProducts = 'N';
        */
    if (isset($_POST['PRICE_TYPE']) && is_string($_POST['PRICE_TYPE']))
        $PRICE_TYPE = $_POST['PRICE_TYPE'];

    if (isset($_POST['DISABLE_REFERERS']) && is_string($_POST['DISABLE_REFERERS']))
        $disableReferers = $_POST['DISABLE_REFERERS'];
    if (!isset($disableReferers) || $disableReferers != 'Y')
        $disableReferers = 'N';
    if (isset($_POST['EXPORT_CHARSET']) && is_string($_POST['EXPORT_CHARSET']))
        $exportCharset = $_POST['EXPORT_CHARSET'];
    if (!isset($exportCharset) || $exportCharset !== 'UTF-8')
        $exportCharset = 'windows-1251';
    if (isset($_POST['MAX_EXECUTION_TIME']) && is_string($_POST['MAX_EXECUTION_TIME']))
        $maxExecutionTime = $_POST['MAX_EXECUTION_TIME'];
    $maxExecutionTime = (!isset($maxExecutionTime) ? 0 : (int)$maxExecutionTime);
    if ($maxExecutionTime < 0)
        $maxExecutionTime = 0;

    if ($ACTION == "EXPORT_SETUP" || $ACTION == "EXPORT_EDIT" || $ACTION == "EXPORT_COPY") {
        if (!isset($SETUP_PROFILE_NAME) || $SETUP_PROFILE_NAME == '')
            $arSetupErrors[] = GetMessage("CET_ERROR_NO_PROFILE_NAME");
    }

    if ($getApiKeyFromModule === 'N' && (!isset($API_KEY) || $API_KEY == '')) {
        $arSetupErrors[] = GetMessage("AS_ERROR_API_KEY");
    }
    if ($getApiKeyFromModule === 'Y' && !Config::getApiKey()) {
        $arSetupErrors[] = GetMessage("AS_ERROR_API_KEY");
    }

    if ($getClientIdFromModule === 'N' && (!isset($CLIENT_ID) || $CLIENT_ID == '')) {
        $arSetupErrors[] = GetMessage("AS_ERROR_CLIENT_ID");
    }
    if ($getClientIdFromModule === 'Y' && !Config::getClientId()) {
        $arSetupErrors[] = GetMessage("AS_ERROR_CLIENT_ID");
    }

    if ($IBLOCK_ID) {
        if ($getClientIdFromModule === 'N') {
            $clientId = $CLIENT_ID ?: null;
        } else {
            $clientId = Config::getClientId() ?: null;
        }
        if ($getApiKeyFromModule === 'N') {
            $token = $API_KEY ?: null;
        } else {
            $token = Config::getApiKey() ?: null;
        }

        $token  = $getApiKeyFromModule === 'N' ? $API_KEY : null;

        $items = new Export_ozon\Goods($clientId , $token);
        if (!$items->checkTable() && !$items->getValues()->getAll()) {
            $arSetupErrors[] = GetMessage("EMPTY_ITEMS_OZON");
        }
        $items = new Export_ozon\Sections($clientId , $token);
        if (!$items->checkTable() && !$items->getValues()->getAll()) {
            $arSetupErrors[] = GetMessage("EMPTY_SECTIONS_OZON");
        }

        $props = new Export_ozon\Props($IBLOCK_ID);
        $props->checkAll();

        if ($props->checkSummary()) {
            $arSetupErrors[] = "";
            $arSetupErrors[] = GetMessage("PROP_CONFIGURE_WARNING");
            $arSetupErrors[] = $props->getSummary("\n");
        }
    }

    if (!$PRICE_TYPE) {
        $arSetupErrors[] = GetMessage("AS_PRICE_TYPE_NOT_SELECTED");
    }

    if (!empty($arSetupErrors)) {
        $STEP = 1;
    }
}

$aMenu = array(
    array(
        "TEXT" => GetMessage("CATI_ADM_RETURN_TO_LIST"),
        "TITLE" => GetMessage("CATI_ADM_RETURN_TO_LIST_TITLE"),
        "LINK" => "/bitrix/admin/cat_export_setup.php?lang=" . LANGUAGE_ID,
        "ICON" => "btn_list",
    )
);

$context = new CAdminContextMenu($aMenu);

$context->Show();

if (!empty($arSetupErrors))
    ShowError(implode('<br>', $arSetupErrors));

$actionParams = "";
if ($adminSidePanelHelper->isSidePanel()) {
    $actionParams = "?IFRAME=Y&IFRAME_TYPE=SIDE_SLIDER";
}
?>
<!--suppress JSUnresolvedVariable -->
<form method="post" action="<? echo $APPLICATION->GetCurPage() . $actionParams ?>" name="yandex_setup_form"
      id="yandex_setup_form">
    <?
    $aTabs = array(
        array("DIV" => "yand_edit1", "TAB" => GetMessage("CAT_ADM_MISC_EXP_TAB1"), "ICON" => "store", "TITLE" => GetMessage("CAT_ADM_MISC_EXP_TAB1_TITLE")),
        array("DIV" => "yand_edit2", "TAB" => GetMessage("AS_OZON_CONFIG"), "ICON" => "store", "TITLE" => GetMessage("AS_OZON_CONFIG_TITLE")),
        // array("DIV" => "yand_edit3", "TAB" => GetMessage("AS_PROPERTIES"), "ICON" => "props", "TITLE" => GetMessage("AS_PROPERTIES_TITLE")),
    );

    $tabControl = new CAdminTabControl("tabYandex", $aTabs, false, true);
    $tabControl->Begin();

    $tabControl->BeginNextTab();

    if ($STEP == 1) {
        if (!isset($SITE_ID))
            $SITE_ID = '';
        if (!isset($XML_DATA))
            $XML_DATA = '';
        if (!isset($filterAvalable) || $filterAvalable != 'Y')
            $filterAvalable = 'N';

        /*
        if (!isset($needUploadPrices) || $needUploadPrices != 'N')
            $needUploadPrices = 'Y';

        if (!isset($needUploadProducts) || $needUploadProducts != 'N')
            $needUploadProducts = 'Y';
        */

        if (isset($getApiKeyFromModule) && !$getApiKeyFromModule)
            $getApiKeyFromModule = 'N';
        if (isset($getClientIdFromModule) && !$getClientIdFromModule)
            $getClientIdFromModule = 'N';

        if (!isset($USE_HTTPS) || $USE_HTTPS != 'Y')
            $USE_HTTPS = 'N';
        if (!isset($disableReferers) || $disableReferers != 'Y')
            $disableReferers = 'N';
        if (!isset($exportCharset) || $exportCharset !== 'UTF-8')
            $exportCharset = 'windows-1251';
        if (!isset($SETUP_SERVER_NAME))
            $SETUP_SERVER_NAME = '';
        if (!isset($COMPANY_NAME))
            $COMPANY_NAME = '';
        if (!isset($SETUP_FILE_NAME))
            $SETUP_FILE_NAME = 'yandex_' . mt_rand(0, 999999) . '.php';
        if (!isset($checkPermissions) || $checkPermissions != 'Y')
            $checkPermissions = 'N';

        $siteList = array();
        $iterator = Main\SiteTable::getList(array(
            'select' => array('LID', 'NAME', 'SORT'),
            'filter' => array('=ACTIVE' => 'Y'),
            'order' => array('SORT' => 'ASC')
        ));
        while ($row = $iterator->fetch())
            $siteList[$row['LID']] = $row['NAME'];
        unset($row, $iterator);
        $iblockIds = array();
        $iblockSites = array();
        $iblockMultiSites = array();

        $allIblocks = [];

        $iterator = Catalog\CatalogIblockTable::getList(array(
            'select' => array(
                'IBLOCK_ID',
                'PRODUCT_IBLOCK_ID',
                'IBLOCK_ACTIVE' => 'IBLOCK.ACTIVE',
                'PRODUCT_IBLOCK_ACTIVE' => 'PRODUCT_IBLOCK.ACTIVE'
            ),
            'filter' => array('')
        ));
        while ($row = $iterator->fetch()) {
            $row['PRODUCT_IBLOCK_ID'] = (int)$row['PRODUCT_IBLOCK_ID'];
            $row['IBLOCK_ID'] = (int)$row['IBLOCK_ID'];
            if ($row['PRODUCT_IBLOCK_ID'] > 0) {
                if ($row['PRODUCT_IBLOCK_ACTIVE'] == 'Y') {
                    $iblockIds[$row['PRODUCT_IBLOCK_ID']] = true;
                    $allIblocks[$row['PRODUCT_IBLOCK_ID']]['OFFER_IBLOCK_ID'] = $row['IBLOCK_ID'];
                }
            } else {
                if ($row['IBLOCK_ACTIVE'] == 'Y') {
                    $iblockIds[$row['IBLOCK_ID']] = true;
                    $allIblocks[$row['IBLOCK_ID']]['IBLOCK_ID'] = $row['IBLOCK_ID'];
                }
            }
        }
        unset($row, $iterator);
        if (!empty($iblockIds)) {
            $activeIds = array();
            $iterator = Iblock\IblockSiteTable::getList(array(
                'select' => array('IBLOCK_ID', 'SITE_ID', 'SITE_SORT' => 'SITE.SORT'),
                'filter' => array('@IBLOCK_ID' => array_keys($iblockIds), '=SITE.ACTIVE' => 'Y'),
                'order' => array('IBLOCK_ID' => 'ASC', 'SITE_SORT' => 'ASC')
            ));
            while ($row = $iterator->fetch()) {
                $id = (int)$row['IBLOCK_ID'];

                if (!isset($iblockSites[$id]))
                    $iblockSites[$id] = array(
                        'ID' => $id,
                        'SITES' => array()
                    );
                $iblockSites[$id]['SITES'][] = array(
                    'ID' => $row['SITE_ID'],
                    'NAME' => $siteList[$row['SITE_ID']]
                );

                if (isset($allIblocks[$id]['OFFER_IBLOCK_ID'])) {
                    $iblockSites[$id]['OFFER_IBLOCK_ID'] = $allIblocks[$id]['OFFER_IBLOCK_ID'];
                }

                if (!isset($iblockMultiSites[$id]))
                    $iblockMultiSites[$id] = false;
                else
                    $iblockMultiSites[$id] = true;

                $activeIds[$id] = true;
            }
            unset($id, $row, $iterator);
            if (empty($activeIds)) {
                $iblockIds = array();
                $iblockSites = array();
                $iblockMultiSites = array();
            } else {
                $iblockIds = array_intersect_key($iblockIds, $activeIds);
            }
            unset($activeIds);
        }
        if (empty($iblockIds)) {

        }

        $currentList = array();
        if ($IBLOCK_ID > 0 && isset($iblockIds[$IBLOCK_ID])) {
            $currentList = $iblockSites[$IBLOCK_ID]['SITES'];
            if ($SITE_ID === '') {
                $firstSite = reset($currentList);
                $SITE_ID = $firstSite['ID'];
            }
        }
        ?>
        <tr>
            <td colspan="2">
                <div class="adm-info-message-wrap">
                    <div class="adm-info-message" style="display: block;"><?= Loc::getMessage('AS_OZON_CONFIG_KEY_NOTE') ?></div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= Loc::getMessage('AS_SELECT_IBLOCK') ?></td>
            <td width="60%"><?
                echo GetIBlockDropDownListEx(
                    $IBLOCK_ID, 'IBLOCK_TYPE_ID', 'IBLOCK_ID',
                    array(
                        'ID' => array_keys($iblockIds),
                        'CHECK_PERMISSIONS' => 'Y',
                        'MIN_PERMISSION' => 'U'
                    ),
                    "",
                    "",
                    'class="adm-detail-iblock-types"',
                    'class="adm-detail-iblock-list"'
                );
                ?>
            </td>
        </tr>
        <tr id="tr_SITE_ID" style="display: <?= (count($currentList) > 1 ? 'table-row' : 'none'); ?>;">
            <td width="40%"><?= GetMessage('BX_CATALOG_EXPORT_YANDEX_SITE'); ?></td>
            <td width="60%">
                <select id="SITE_ID" name="SITE_ID">
                    <?
                    foreach ($currentList as $site) {
                        $selected = ($site['ID'] == $SITE_ID ? ' selected' : '');
                        $name = '[' . $site['ID'] . '] ' . $site['NAME'];
                        ?>
                        <option value="<?= htmlspecialcharsbx($site['ID']); ?>"<?= $selected; ?>><?= htmlspecialcharsbx($name); ?></option><?
                    }
                    unset($name, $selected, $site);
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td width="40%"><?= Loc::getMessage('AS_MAPPING_SECTIONS_AND_ITEMS') ?>:</td>
            <td width="60%">
                <?$ssid = explode('=', bitrix_sessid_get());?>
                <script type="text/javascript">
                    function showDetailPopup(config = {}) {
                        if (!obDetailWindow) {

                            var s = BX('IBLOCK_ID');
                            var dat = BX('XML_DATA');

                            const formData = new FormData()
                            formData.append('GET_API_KEY_FROM_MODULE', BX('GET_API_KEY_FROM_MODULE').checked ? 'Y' : 'N')
                            formData.append('API_KEY', BX('API_KEY').value)
                            formData.append('GET_CLIENT_ID_FROM_MODULE', BX('GET_CLIENT_ID_FROM_MODULE').checked ? 'Y' : 'N')
                            formData.append('CLIENT_ID', BX('CLIENT_ID').value)
                            formData.append('XML_DATA', BX.util.urlencode(dat.value))
                            formData.append('<?=$ssid[0]?>', '<?=$ssid[1]?>')

                            for (let i in config) {
                                formData.append(i, config[i])
                            }

                            var obDetailWindow = new BX.CAdminDialog({
                                'content_url': '/bitrix/tools/aspro.lite/marketplace/export_ozon/sync.php?lang=<?=LANGUAGE_ID?>&bxpublic=Y&IBLOCK_ID=' + s[s.selectedIndex].value,
                                'content_post': new URLSearchParams(formData).toString(),
                                'width': 500, 'height': 500,
                                'resizable': false
                            });
                            obDetailWindow.Show();
                        }
                    }

                    function setDetailData(data) {
                        BX('XML_DATA').value = data;
                    }
                </script>
                <input
                    type="button" onclick="showDetailPopup(); return false;"
                    value="<? echo GetMessage('AS_GET_SECTIONS_AND_ITEMS'); ?>">
                <input
                    type="button" onclick="showDetailPopup({action: 'sync', stage: 'goods'}); return false;"
                    value="<? echo GetMessage('AS_SYNC_SECTIONS_AND_ITEMS'); ?>">
                <span data-hint="<?= GetMessage('AS_SYNC_SECTIONS_AND_ITEMS_HINT') ?>"></span>
                <input
                    type="hidden" id="XML_DATA" name="XML_DATA" value="<?= htmlspecialcharsbx($XML_DATA); ?>">
            </td>
        </tr>
        
        <tr>
            <td width="40%"><?= GetMessage('AS_COMMON_PROPERTIES') ?></td>
            <td width="60%">
            <script type="text/javascript">
                    function showPropsPopup() {
                        if (!obDetailWindow) {

                            var s = BX('IBLOCK_ID');

                            const formData = new FormData()
                            formData.append('<?=$ssid[0]?>', '<?=$ssid[1]?>')

                            var obDetailWindow = new BX.CAdminDialog({
                                'content_url': '/bitrix/tools/aspro.lite/marketplace/export_ozon/props.php?lang=<?=LANGUAGE_ID?>&bxpublic=Y&IBLOCK_ID=' + s[s.selectedIndex].value,
                                'content_post': new URLSearchParams(formData).toString(),
                                'width': 500, 'height': 500,
                                'resizable': false
                            });
                            obDetailWindow.Show();
                        }
                    }
                </script>
                <input
                    type="button" onclick="showPropsPopup(); return false;"
                    value="<? echo GetMessage('AS_CONFIGURE'); ?>">
                <span data-hint="<?= GetMessage('AS_COMMON_PROPERTIES_HINT') ?>"></span>
            </td>
        </tr>

        <tr>
            <td width="40%"><? echo GetMessage("AS_PRICE_TYPE"); ?></td>
            <td width="60%">
                <select id="PRICE_TYPE" name="PRICE_TYPE">
                    <option value="">-</option>
                    <?
                    $pricesType = \Bitrix\Catalog\GroupTable::getList(array(
                        'select' => array('ID', 'NAME')
                    ))->fetchAll();
                    foreach ($pricesType as $priceType) {
                        $selected = ($priceType['ID'] == $PRICE_TYPE ? ' selected' : '');
                        $name = '[' . $priceType['ID'] . '] ' . $priceType['NAME'];
                        ?>
                        <option value="<?= htmlspecialcharsbx($priceType['ID']); ?>"<?= $selected; ?>><?= htmlspecialcharsbx($name); ?></option><?
                    }
                    ?>
                </select>
            </td>
        </tr>

        <?
        $maxExecutionTime = (isset($maxExecutionTime) ? (int)$maxExecutionTime : 0);
        ?>
        <tr style="display:none">
            <td width="40%"><?= GetMessage('CAT_MAX_EXECUTION_TIME'); ?></td>
            <td width="60%">
                <input
                    type="text" name="MAX_EXECUTION_TIME" size="40" value="<?= $maxExecutionTime; ?>">
            </td>
        </tr>
        <tr style="display:none">
            <td width="40%" style="padding-top: 0;">&nbsp;</td>
            <td width="60%" style="padding-top: 0;">
                <small><?= GetMessage("CAT_MAX_EXECUTION_TIME_NOTE"); ?></small>
            </td>
        </tr>
        <tr hidden>
            <td width="40%"><? echo GetMessage("CET_SERVER_NAME"); ?></td>
            <td width="60%">
                <input
                    type="text" name="SETUP_SERVER_NAME" value="<?= htmlspecialcharsbx($SETUP_SERVER_NAME); ?>"
                    size="50">
                <input
                    type="button"
                    onclick="this.form['SETUP_SERVER_NAME'].value = window.location.host;"
                    value="<? echo htmlspecialcharsbx(GetMessage('CET_SERVER_NAME_SET_CURRENT')) ?>">
            </td>
        </tr>
        <?
        if ($ACTION == "EXPORT_SETUP" || $ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') {
            ?>
            <tr>
            <td width="40%"><? echo GetMessage("CET_PROFILE_NAME"); ?></td>
            <td width="60%">
                <input
                    type="text" name="SETUP_PROFILE_NAME" value="<? echo htmlspecialcharsbx($SETUP_PROFILE_NAME) ?>"
                    size="30">
            </td>
            </tr><?
        }

        if (($ACTION == "EXPORT_SETUP" || $ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT') && $PROFILE_ID > 0) {
            ?>
            <tr hidden>
            <td width="40%"><? echo GetMessage("CET_PROFILE_LOG_PATH"); ?></td>
            <td width="60%">
                <?
                $UrlToLog = '/upload/mp-export-log/ozon_' . htmlspecialcharsbx($PROFILE_ID);
                $linkToLogs = '/bitrix/admin/fileman_admin.php?lang=' . LANGUAGE_ID . '&path=' . rawurlencode($UrlToLog);
                ?>
                <a href="<?=$linkToLogs?>" target="_blank"><?=$UrlToLog?></a>
            </td>
            </tr><?
        }

    }

    $tabControl->EndTab();

    $tabControl->BeginNextTab();

    
    if ($STEP == 2) {
        $SETUP_FILE_NAME = $strAllowExportPath . $SETUP_FILE_NAME;
        if ($XML_DATA <> '') {
            $XML_DATA = base64_decode($XML_DATA);
        }
        $SETUP_SERVER_NAME = htmlspecialcharsbx($SETUP_SERVER_NAME);
        $_POST['SETUP_SERVER_NAME'] = htmlspecialcharsbx($_POST['SETUP_SERVER_NAME']);
        $_REQUEST['SETUP_SERVER_NAME'] = htmlspecialcharsbx($_REQUEST['SETUP_SERVER_NAME']);

        $FINITE = true;
    }
   ?>
        <tr>
            <td width="40%"><?= GetMessage('AS_FORM_LABEL_API_KEY') ?></td>
            <td width="60%">
                 <input
                    type="text" name="API_KEY" value="<?=$API_KEY;?>"
                    id="API_KEY"
                    <? echo($getApiKeyFromModule !== 'N' ? ' readonly' : ''); ?>
                    size="50">
                <!-- <input
                    type="hidden" name="GET_API_KEY_FROM_MODULE" value="N"> -->
                <input
                    type="checkbox" name="GET_API_KEY_FROM_MODULE" id="GET_API_KEY_FROM_MODULE"
                    onchange="this.checked ? window['API_KEY'].setAttribute('readonly', 'readonly') : window['API_KEY'].removeAttribute('readonly')" 
                    value="Y"<? echo($getApiKeyFromModule !== 'N' ? ' checked' : ''); ?>>
                    <label for="GET_API_KEY_FROM_MODULE"><?=GetMessage('AS_FROM_THEME')?></label>
            </td>
        </tr>
        <tr>
            <td width="40%"><?=GetMessage('AS_FORM_LABEL_CLIENT_ID')?></td>
            <td width="60%">
                 <input
                    type="text" name="CLIENT_ID" value="<?=$CLIENT_ID;?>"
                    id="CLIENT_ID"
                    <? echo($getClientIdFromModule !== 'N' ? ' readonly' : ''); ?>
                    size="50">
                <input
                    type="checkbox" name="GET_CLIENT_ID_FROM_MODULE" id="GET_CLIENT_ID_FROM_MODULE"
                    onchange="this.checked ? window['CLIENT_ID'].setAttribute('readonly', 'readonly') : window['CLIENT_ID'].removeAttribute('readonly')" 
                    value="Y"<? echo($getClientIdFromModule !== 'N' ? ' checked' : ''); ?>>
                    <label for="GET_CLIENT_ID_FROM_MODULE"><?=GetMessage('AS_FROM_THEME')?></label>
            </td>
        </tr>
   <?
    $tabControl->EndTab();

    $tabControl->Buttons();

    ?><? echo bitrix_sessid_post(); ?><?
    if ($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') {
        ?><input type="hidden" name="PROFILE_ID" value="<? echo intval($PROFILE_ID); ?>"><?
    }

    if (2 > $STEP) {
        ?>
        <input
            type="hidden" name="lang" value="<? echo LANGUAGE_ID ?>">
        <input
            type="hidden" name="ACT_FILE" value="<? echo htmlspecialcharsbx($_REQUEST["ACT_FILE"]) ?>">
        <input
            type="hidden" name="ACTION" value="<? echo htmlspecialcharsbx($ACTION) ?>">
        <input
            type="hidden" name="STEP" value="<? echo intval($STEP) + 1 ?>">
        <input
            type="hidden" name="SETUP_FIELDS_LIST"
            value="USE_PRICES_WITH_DISCOUNT,NEED_UPLOAD_STORES,V,IBLOCK_ID,SITE_ID,SETUP_SERVER_NAME,COMPANY_NAME,SETUP_FILE_NAME,XML_DATA,USE_HTTPS,FILTER_AVAILABLE,DISABLE_REFERERS,EXPORT_CHARSET,MAX_EXECUTION_TIME,CHECK_PERMISSIONS,NEED_UPLOAD_PRICES,NEED_UPLOAD_PRODUCTS,CUSTOM_FILTER,GET_API_KEY_FROM_MODULE,API_KEY,GET_CLIENT_ID_FROM_MODULE,CLIENT_ID,PRICE_TYPE">
        <input
            type="submit"
            value="<? echo ($ACTION == "EXPORT") ? GetMessage("CET_EXPORT") : GetMessage("CET_SAVE") ?>">
        <?
    }

    $tabControl->End();
    ?>
</form>