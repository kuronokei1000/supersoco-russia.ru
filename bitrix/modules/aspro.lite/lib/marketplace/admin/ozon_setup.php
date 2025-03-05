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
    \Aspro\Lite\Marketplace\Maps\Ozon as OzonMap,
    \Aspro\Lite\Marketplace\Config\Ozon as Config;

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/catalog/export_setup_templ.php');
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

global $APPLICATION, $USER;

\Bitrix\Main\UI\Extension::load("ui.hint");?>
    <script type="text/javascript">
        BX.ready(function() {
            BX.UI.Hint.init(BX('adm-detail-content-item-block'));
        })
    </script>
<?

$arSetupErrors = array();

$strAllowExportPath = COption::GetOptionString("catalog", "export_default_path", "/bitrix/catalog_export/");

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

    if (empty($arSetupErrors)) {
        $bAllSections = false;
        $arSections = array();
        if (!empty($V) && is_array($V)) {
            foreach ($V as $key => $value) {
                if (trim($value) == "0") {
                    $bAllSections = true;
                    break;
                }
                $value = (int)$value;
                if ($value > 0)
                    $arSections[] = $value;
            }
        }

        if (!$bAllSections && !empty($arSections)) {
            $arCheckSections = array();
            $rsSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $IBLOCK_ID, 'ID' => $arSections), false, array('ID'));
            while ($arOneSection = $rsSections->Fetch()) {
                $arCheckSections[] = $arOneSection['ID'];
            }
            $arSections = $arCheckSections;
        }

        if (!$bAllSections && empty($arSections)) {
            // $arSetupErrors[] = GetMessage("CET_ERROR_NO_GROUPS");
            $V = array();
        }
    }

    if (is_array($V)) {
        $V = array_unique(array_values($V));
        $_REQUEST['V'] = $V;
    }

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
        
    if (isset($_POST['USE_PRICES_WITH_DISCOUNT']) && is_string($_POST['USE_PRICES_WITH_DISCOUNT']))
        $usePricesWithDiscount = $_POST['USE_PRICES_WITH_DISCOUNT'];
    if (!isset($usePricesWithDiscount) || $usePricesWithDiscount != 'Y')
        $usePricesWithDiscount = 'N';

    if (isset($_POST['NEED_UPLOAD_STORES']) && is_string($_POST['NEED_UPLOAD_STORES']))
        $needUploadStores = $_POST['NEED_UPLOAD_STORES'];
    if (!isset($needUploadStores) || $needUploadStores != 'Y')
        $needUploadStores = 'N';

    if (isset($_POST['NEED_UPLOAD_PRICES']) && is_string($_POST['NEED_UPLOAD_PRICES']))
        $needUploadPrices = $_POST['NEED_UPLOAD_PRICES'];
    if (!isset($needUploadPrices) || $needUploadPrices != 'Y')
        $needUploadPrices = 'N';

    if (isset($_POST['NEED_UPLOAD_PRODUCTS']) && is_string($_POST['NEED_UPLOAD_PRODUCTS']))
        $needUploadProducts = $_POST['NEED_UPLOAD_PRODUCTS'];
    if (!isset($needUploadProducts) || $needUploadProducts != 'Y')
        $needUploadProducts = 'N';

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
        $map = new OzonMap($IBLOCK_ID);
        if (!$map->getMapStructure()) {
            $arSetupErrors[] = GetMessage("AS_ERROR_MAP_STRUCTURE");
        }
        if (!$map->getPropsValuesStructure()) {
            $arSetupErrors[] = GetMessage("AS_ERROR_MAP_STRUCTURE_PROP_VALUES");
        }
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

        if (!isset($usePricesWithDiscount) || $usePricesWithDiscount != 'N')
            $usePricesWithDiscount = 'Y';

        if (!isset($needUploadStores) || $needUploadStores != 'N')
            $needUploadStores = 'Y';

        if (!isset($needUploadPrices) || $needUploadPrices != 'N')
            $needUploadPrices = 'Y';

        if (!isset($needUploadProducts) || $needUploadProducts != 'N')
            $needUploadProducts = 'Y';

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
                    "ClearSelected(); changeIblockSites(0); BX('id_ifr').src='/bitrix/tools/catalog_export/yandex_util.php?IBLOCK_ID=0&'+'" . bitrix_sessid_get() . "';",
                    "ClearSelected(); changeIblockSites(this[this.selectedIndex].value); BX('id_ifr').src='/bitrix/tools/catalog_export/yandex_util.php?IBLOCK_ID='+this[this.selectedIndex].value+'&'+'" . bitrix_sessid_get() . "';",
                    'class="adm-detail-iblock-types"',
                    'class="adm-detail-iblock-list"'
                );
                ?>
                <script type="text/javascript">
                    var TreeSelected = [];
                    <?
                        $intCountSelected = 0;
                        if (!empty($V) && is_array($V))
                        {
                        foreach ($V as $oneKey)
                        {
                        ?>TreeSelected[<? echo $intCountSelected ?>] = <? echo (int)$oneKey; ?>;
                    <?
                    $intCountSelected++;
                    }
                    }
                    ?>
                    function ClearSelected() {
                        BX.showWait();
                        TreeSelected = [];
                    }
                </script>
            </td>
        </tr>
        <tr id="tr_SITE_ID" style="display: <?= (count($currentList) > 1 ? 'table-row' : 'none'); ?>;">
            <td width="40%"><?= GetMessage('BX_CATALOG_EXPORT_YANDEX_SITE'); ?></td>
            <td width="60%">
                <script type="text/javascript">
                    function changeIblockSites(iblockId) {
                        var iblockSites = <?=CUtil::PhpToJSObject($iblockSites); ?>,
                            iblockMultiSites = <?=CUtil::PhpToJSObject($iblockMultiSites); ?>,
                            tableRow = null,
                            siteControl = null,
                            i,
                            currentSiteList;

                        const data = {'iblockId': iblockId}
                        if (typeof (iblockSites[iblockId]) !== 'undefined') {
                            if (iblockSites[iblockId]['OFFER_IBLOCK_ID']) {
                                data['offersIblockId'] = iblockSites[iblockId]['OFFER_IBLOCK_ID'];
                            }
                        }
                        customFilterParams['data'] = JSON.stringify(data)
                        customFilterParams['oInput'].value = ''
                        customFilterParams['oCont'].querySelector('div').remove()
                        initAsproLiteCustomFilterControl(customFilterParams);

                        tableRow = BX('tr_SITE_ID');
                        siteControl = BX('SITE_ID');
                        if (!BX.type.isElementNode(tableRow) || !BX.type.isElementNode(siteControl))
                            return;

                        for (i = siteControl.length - 1; i >= 0; i--)
                            siteControl.remove(i);
                        if (typeof (iblockSites[iblockId]) !== 'undefined') {
                            currentSiteList = iblockSites[iblockId]['SITES'];
                            for (i = 0; i < currentSiteList.length; i++) {
                                siteControl.appendChild(BX.create(
                                    'option',
                                    {
                                        props: {value: BX.util.htmlspecialchars(currentSiteList[i].ID)},
                                        html: BX.util.htmlspecialchars('[' + currentSiteList[i].ID + '] ' + currentSiteList[i].NAME)
                                    }
                                ));
                            }
                        }
                        if (siteControl.length > 0)
                            siteControl.selectedIndex = 0;
                        else
                            siteControl.selectedIndex = -1;
                        BX.style(tableRow, 'display', (siteControl.length > 1 ? 'table-row' : 'none'));
                    }
                </script>
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
            <td width="40%" valign="top"><? echo GetMessage("CET_SELECT_GROUP"); ?></td>
            <td width="60%"><?
                if ($intCountSelected) {
                    foreach ($V as $oneKey) {
                        $oneKey = (int)$oneKey;
                        ?>
                        <input type="hidden" value="<? echo $oneKey; ?>" name="V[]" id="oldV<? echo $oneKey; ?>">
                        <?
                    }
                    unset($oneKey);
                }
                ?>
                <div id="tree"></div>
                <script type="text/javascript">
                    BX.showWait();
                    clevel = 0;

                    function delOldV(obj) {
                        if (!!obj) {
                            var intSelKey = BX.util.array_search(obj.value, TreeSelected);
                            if (obj.checked == false) {
                                if (-1 < intSelKey) {
                                    TreeSelected = BX.util.deleteFromArray(TreeSelected, intSelKey);
                                }

                                var objOldVal = BX('oldV' + obj.value);
                                if (!!objOldVal) {
                                    objOldVal.parentNode.removeChild(objOldVal);
                                    objOldVal = null;
                                }
                            } else {
                                if (-1 == intSelKey) {
                                    TreeSelected[TreeSelected.length] = obj.value;
                                }
                            }
                        }
                    }

                    function buildNoMenu() {
                        var buffer;
                        buffer = '<?echo GetMessageJS("CET_FIRST_SELECT_IBLOCK");?>';
                        BX('tree', true).innerHTML = buffer;
                        BX.closeWait();
                    }

                    function buildMenu() {
                        var i,
                            buffer,
                            imgSpace,
                            space;

                        buffer = '<table border="0" cellspacing="0" cellpadding="0">';
                        buffer += '<tr>';
                        buffer += '<td colspan="2" valign="top" align="left"><input type="checkbox" name="V[]" value="0" id="v0"' + (BX.util.in_array(0, TreeSelected) ? ' checked' : '') + ' onclick="delOldV(this);"><label for="v0"><font class="text"><b><?echo CUtil::JSEscape(GetMessage("CET_ALL_GROUPS"));?></b></font></label></td>';
                        buffer += '</tr>';

                        for (i in Tree[0]) {
                            if (!Tree[0][i]) {
                                space = '<input type="checkbox" name="V[]" value="' + i + '" id="V' + i + '"' + (BX.util.in_array(i, TreeSelected) ? ' checked' : '') + ' onclick="delOldV(this);"><label for="V' + i + '"><span class="text">' + Tree[0][i][0] + '</span></label>';
                                imgSpace = '';
                            } else {
                                space = '<input type="checkbox" name="V[]" value="' + i + '"' + (BX.util.in_array(i, TreeSelected) ? ' checked' : '') + ' onclick="delOldV(this);"><a href="javascript: collapse(' + i + ')"><span class="text"><b>' + Tree[0][i][0] + '</b></span></a>';
                                imgSpace = '<img src="/bitrix/images/catalog/load/plus.gif" width="13" height="13" id="img_' + i + '" OnClick="collapse(' + i + ')">';
                            }

                            buffer += '<tr>';
                            buffer += '<td width="20" valign="top" align="center">' + imgSpace + '</td>';
                            buffer += '<td id="node_' + i + '">' + space + '</td>';
                            buffer += '</tr>';
                        }

                        buffer += '</table>';

                        BX('tree', true).innerHTML = buffer;
                        BX.adminPanel.modifyFormElements('yandex_setup_form');
                        BX.closeWait();
                    }

                    function collapse(node) {
                        if (!BX('table_' + node)) {
                            var i,
                                buffer,
                                imgSpace,
                                space;

                            buffer = '<table border="0" id="table_' + node + '" cellspacing="0" cellpadding="0">';

                            for (i in Tree[node]) {
                                if (!Tree[node][i]) {
                                    space = '<input type="checkbox" name="V[]" value="' + i + '" id="V' + i + '"' + (BX.util.in_array(i, TreeSelected) ? ' checked' : '') + ' onclick="delOldV(this);"><label for="V' + i + '"><font class="text">' + Tree[node][i][0] + '</font></label>';
                                    imgSpace = '';
                                } else {
                                    space = '<input type="checkbox" name="V[]" value="' + i + '"' + (BX.util.in_array(i, TreeSelected) ? ' checked' : '') + ' onclick="delOldV(this);"><a href="javascript: collapse(' + i + ')"><font class="text"><b>' + Tree[node][i][0] + '</b></font></a>';
                                    imgSpace = '<img src="/bitrix/images/catalog/load/plus.gif" width="13" height="13" id="img_' + i + '" OnClick="collapse(' + i + ')">';
                                }

                                buffer += '<tr>';
                                buffer += '<td width="20" align="center" valign="top">' + imgSpace + '</td>';
                                buffer += '<td id="node_' + i + '">' + space + '</td>';
                                buffer += '</tr>';
                            }

                            buffer += '</table>';

                            BX('node_' + node).innerHTML += buffer;
                            BX('img_' + node).src = '/bitrix/images/catalog/load/minus.gif';
                        } else {
                            var tbl = BX('table_' + node);
                            tbl.parentNode.removeChild(tbl);
                            BX('img_' + node).src = '/bitrix/images/catalog/load/plus.gif';
                        }
                        BX.adminPanel.modifyFormElements('yandex_setup_form');
                    }
                </script>
                <iframe
                    src="/bitrix/tools/catalog_export/yandex_util.php?IBLOCK_ID=<?= intval($IBLOCK_ID) ?>&<? echo bitrix_sessid_get(); ?>"
                    id="id_ifr" name="ifr" style="display:none"></iframe>
            </td>
        </tr>

         <tr>
            <td width="40%"><?= Loc::getMessage('AS_MAPPING_SECTIONS_AND_PROPERTIES') ?>:</td>
            <td width="60%">
                <?$ssid = explode('=', bitrix_sessid_get());?>
                <script type="text/javascript">
                    function showDetailPopup() {
                        if (!obDetailWindow) {
                            let selectedSectionIds = TreeSelected || [];

                            var s = BX('IBLOCK_ID');
                            var dat = BX('XML_DATA');

                            const formData = new FormData()
                            formData.append('GET_API_KEY_FROM_MODULE', BX('GET_API_KEY_FROM_MODULE').checked ? 'Y' : 'N')
                            formData.append('API_KEY', BX('API_KEY').value)
                            formData.append('GET_CLIENT_ID_FROM_MODULE', BX('GET_CLIENT_ID_FROM_MODULE').checked ? 'Y' : 'N')
                            formData.append('CLIENT_ID', BX('CLIENT_ID').value)
                            formData.append('SECTIONS', JSON.stringify(selectedSectionIds))
                            formData.append('XML_DATA', BX.util.urlencode(dat.value))
                            formData.append('<?=$ssid[0]?>', '<?=$ssid[1]?>')

                            var obDetailWindow = new BX.CAdminDialog({
                                'content_url': '/bitrix/tools/aspro.lite/marketplace/ozon_detail.php?lang=<?=LANGUAGE_ID?>&bxpublic=Y&IBLOCK_ID=' + s[s.selectedIndex].value,
                                'content_post': new URLSearchParams(formData).toString(),
                                'width': 900, 'height': 550,
                                'resizable': true
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
                    value="<? echo GetMessage('CAT_DETAIL_PROPS_RUN'); ?>">
                <input
                    type="hidden" id="XML_DATA" name="XML_DATA" value="<?= htmlspecialcharsbx($XML_DATA); ?>">
            </td>
        </tr>

        <tr>
            <td width="40%"><?= GetMessage('AS_UPLOAD_PRODUCTS') ?></td>
            <td width="60%">
                <input
                    type="hidden" name="NEED_UPLOAD_PRODUCTS" value="N">
                <input
                    type="checkbox" name="NEED_UPLOAD_PRODUCTS"
                    value="Y"<? echo($needUploadProducts == 'Y' ? ' checked' : ''); ?>>
                <span data-hint="<?= GetMessage('AS_UPLOAD_PRODUCTS_TIP') ?>"></span>
            </td>
        </tr>
        
        <tr>
            <td width="40%"><?= GetMessage('AS_UPLOAD_PRICES') ?></td>
            <td width="60%">
                <input
                    type="hidden" name="NEED_UPLOAD_PRICES" value="N">
                <input
                    type="checkbox" name="NEED_UPLOAD_PRICES"
                    value="Y"<? echo($needUploadPrices == 'Y' ? ' checked' : ''); ?>>
                <span data-hint="<?= GetMessage('AS_UPLOAD_WARNING_TIP') ?>"></span>
            </td>
        </tr>
        
        <tr>
            <td width="40%"><?= GetMessage('USE_PRICES_WITH_DISCOUNT') ?></td>
            <td width="60%">
                <input
                    type="hidden" name="USE_PRICES_WITH_DISCOUNT" value="N">
                <input
                    type="checkbox" name="USE_PRICES_WITH_DISCOUNT"
                    value="Y"<? echo($usePricesWithDiscount == 'Y' ? ' checked' : ''); ?>>
            </td>
        </tr>

        <tr>
            <td width="40%"><?= GetMessage('AS_UPLOAD_STORES') ?></td>
            <td width="60%">
                <input
                    type="hidden" name="NEED_UPLOAD_STORES" value="N">
                <input
                    type="checkbox" name="NEED_UPLOAD_STORES"
                    value="Y"<? echo($needUploadStores == 'Y' ? ' checked' : ''); ?>>
                    <span data-hint="<?= GetMessage('AS_UPLOAD_WARNING_TIP') ?>"></span>
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
            <tr>
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
        <tr>
            <td width="40%"></td>
            <td width="60%">
                <input
                    type="button" onclick="getLimits()" value="<?=Loc::getMessage('GET_OZON_LIMITS')?>">
                <script type="text/javascript">
                    function getLimits() {
                        const formData = new FormData()
                        formData.append('GET_API_KEY_FROM_MODULE', BX('GET_API_KEY_FROM_MODULE').checked ? 'Y' : 'N')
                        formData.append('API_KEY', BX('API_KEY').value)
                        formData.append('GET_CLIENT_ID_FROM_MODULE', BX('GET_CLIENT_ID_FROM_MODULE').checked ? 'Y' : 'N')
                        formData.append('CLIENT_ID', BX('CLIENT_ID').value)
                        formData.append('<?=$ssid[0]?>', '<?=$ssid[1]?>')
                        formData.append('action', 'get_limits')

                        BX.ajax({
                            url: '/bitrix/tools/aspro.lite/marketplace/ozon_detail.php?lang=<?=LANGUAGE_ID?>&bxpublic=Y&controller=ozon',
                            method: 'POST',
                            data: new URLSearchParams(formData).toString(),
                            dataType: 'json',
                            onsuccess: function(data){
                                const $doc = document.createElement('div');
                                const $fragment = document.createDocumentFragment();

                                if (data.message) {
                                    $fragment.append(data.message)
                                } else {
                                    for (let type in data) {
                                        const $title = document.createElement('h3')
                                        $title.textContent = BX.message(type);

                                        const $ul = document.createElement('ul')
                                        for (let key in data[type]) {
                                            const $li = document.createElement('li')
                                            $li.textContent = `${BX.message(`${type}_${key}`)} - ${data[type][key]}`;
                                            $ul.appendChild($li)
                                        }

                                        $fragment.appendChild($title)
                                        $fragment.appendChild($ul)
                                    }
                                }
                                $doc.appendChild($fragment)

                                new BX.CDialog({
                                    title: BX.message('OZON_LIMITS'),
                                    content: $doc.outerHTML,
                                    resizable: false,
                                    draggable: true,
                                    height: '300',
                                    width: '700'
                                }).Show();
                            },
                            onfailure: function(data, info) {
                                new BX.CDialog({
                                    title: BX.message('OZON_LIMITS'),
                                    content: info.data,
                                    resizable: false,
                                    draggable: true,
                                    height: '300',
                                    width: '700'
                                }).Show();
                            }
                        })
                    }
                </script>
            </td>
        </tr>
        <tr>
            <td width="40%"><?=GetMessage(("AS_EXPORT_FILTER"))?>:</td>
            <td width="60%">
                <?
                $jsFile = \Aspro\Lite\Property\CustomFilter::getJSFile();
                $ajaxFile = \Aspro\Lite\Property\CustomFilter::getAjaxFile();
                if (!file_exists($_SERVER['DOCUMENT_ROOT'].$jsFile) || !file_exists($_SERVER['DOCUMENT_ROOT'].$ajaxFile)) {
                    $jsFile = $ajaxFile = false;
                } else {
                    $GLOBALS['APPLICATION']->AddHeadScript($jsFile);
                }
                if ($jsFile ) {

                    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
                    if ($request->isPost()) {
                        $customFilter = $request->get('CUSTOM_FILTER');
                    }

                    $customFilterName = "custom_filter";
                    
                    $data = [
                        "iblockId" => $IBLOCK_ID
                    ];
                    if ($IBLOCK_ID) {
                        if (isset($allIblocks[$IBLOCK_ID]['OFFER_IBLOCK_ID'])) {
                            $data['offersIblockId'] = $allIblocks[$IBLOCK_ID]['OFFER_IBLOCK_ID'];
                        }
                    }
                    $customFilterPropertyParams = [
                        'PARENT' => 'DATA_SOURCE',
                        'NAME' => GetMessage('CUSTOM_FILTER_PROP_NAME'),
                        'TYPE' => 'CUSTOM',
                        'AJAX_FILE' => $ajaxFile,
                        'JS_EVENT' => 'initAsproLiteCustomFilterControl',
                        'JS_MESSAGES' => Json::encode([
                            'invalid' => GetMessage('CUSTOM_FILTER_PROP_INVALID')
                        ]),
                        'JS_DATA' => Json::encode($data),
                        '_propId' => $customFilterName,
                        'DEFAULT' => ''
                    ];
                    
                    $customFilterParams = [
                        "oCont" => "",
                        "oInput" => "",
                        "propertyID" => $customFilterName,
                        "propertyParams" => $customFilterPropertyParams,
                        "data" => $customFilterPropertyParams["JS_DATA"]
                    ];?>

                    <div id="custom_filter_container">
                        <input name="CUSTOM_FILTER" id="<?=$customFilterName;?>" value='<?=$customFilter?>' type="hidden">
                    </div>
                    <script>
                        const customFilterParams = <?=CUtil::PhpToJSObject($customFilterParams)?>;
                        customFilterParams['oCont'] = BX('custom_filter_container');
                        customFilterParams['oInput'] = BX('custom_filter');
                        initAsproLiteCustomFilterControl(customFilterParams);
                    </script>

                <?} else {?>
                    Js file doesn't exists
                <?}?>
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
            value="USE_PRICES_WITH_DISCOUNT,NEED_UPLOAD_STORES,V,IBLOCK_ID,SITE_ID,SETUP_SERVER_NAME,COMPANY_NAME,SETUP_FILE_NAME,XML_DATA,USE_HTTPS,FILTER_AVAILABLE,DISABLE_REFERERS,EXPORT_CHARSET,MAX_EXECUTION_TIME,CHECK_PERMISSIONS,NEED_UPLOAD_PRICES,NEED_UPLOAD_PRODUCTS,CUSTOM_FILTER,GET_API_KEY_FROM_MODULE,API_KEY,GET_CLIENT_ID_FROM_MODULE,CLIENT_ID">
        <input
            type="submit"
            value="<? echo ($ACTION == "EXPORT") ? GetMessage("CET_EXPORT") : GetMessage("CET_SAVE") ?>">
        <?
    }

    $tabControl->End();
    ?>
</form>
<script type="text/javascript">
    BX.message({
        OZON_LIMITS: '<?= Loc::getMessage('OZON_LIMITS') ?>',
        daily_create: '<?= Loc::getMessage('daily_create') ?>',
        daily_create_usage: '<?= Loc::getMessage('daily_create_usage') ?>',
        daily_create_limit: '<?= Loc::getMessage('daily_create_limit') ?>',
        daily_create_reset_at: '<?= Loc::getMessage('reset_at') ?>',
        daily_update: '<?= Loc::getMessage('daily_update') ?>',
        daily_update_usage: '<?= Loc::getMessage('daily_update_usage') ?>',
        daily_update_limit: '<?= Loc::getMessage('daily_update_limit') ?>',
        daily_update_reset_at: '<?= Loc::getMessage('reset_at') ?>',
        total: '<?= Loc::getMessage('total') ?>',
        total_usage: '<?= Loc::getMessage('total_usage') ?>',
        total_limit: '<?= Loc::getMessage('total_limit') ?>',
        total_reset_at: '<?= Loc::getMessage('reset_at') ?>',
    });
</script>