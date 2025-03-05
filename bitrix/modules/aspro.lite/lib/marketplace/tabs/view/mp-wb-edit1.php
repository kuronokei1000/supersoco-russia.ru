<?php
/**
 * @var string $tabCode
 * @var array $payload
 * @var array $properties
 */

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/wildberries.php');

use \Bitrix\Main\Localization\Loc;
use Aspro\Lite\Marketplace\Config\Wildberries as Config;

?>

<?php
$duplicateProperties = [];

foreach ($properties as $propertyCode => $property) {
    $duplicateProperties[] = 'tr_PROPERTY_' . $property['ID'];
}

$statuses = Config::getStatusEnums($payload['IBLOCK']['ID']);

?>
<?
$property = $properties[Config::PROPERTY_STATUS];
?>
<tr id="tr_CPROPERTY_<?= $property['ID'] ?>">
    <td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">
        <?= Loc::getMessage('AS_FORM_LABEL_WB_STATUS') ?>:
    </td>
    <td width="60%" class="adm-detail-content-cell-r">
        <table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">
            <tbody>
            <tr>
                <td>
                    <select
                        name="PROP[<?= $property['ID'] ?>][]"
                    >
                        <option>-</option>
                        <? foreach ($statuses as $status) : ?>
                        <option
                            value="<?= $status['ENUM_ID'] ?>"
                            <?= $property['VALUE'] == $status['ENUM_ID'] ? 'selected' : '' ?>
                        >
                            <?=  $status['VALUE'] ?>
                        </option>
                        <? endforeach ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<?
$property = $properties[Config::PROPERTY_ERROR_TEXT];
?>

<tr id="tr_CPROPERTY_<?= $property['ID'] ?>">
    <td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">
        <?= Loc::getMessage('AS_FORM_LABEL_WB_ERROR_TEXT') ?>:
    </td>
    <td width="60%" class="adm-detail-content-cell-r">
        <table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">
            <tbody>
            <tr>
                <td>
                    <textarea
                        class="typearea"
                        style="width:80%;height:200px;resize:vertical;"
                        name="PROP[<?= $property['ID'] ?>][<?= $property['PROPERTY_VALUE_ID'] ?: 'n0' ?>]"
                    ><?= $property['VALUE']['TEXT'] ?></textarea>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>


<?
$property = $properties[Config::PROPERTY_BARCODE];
?>

<tr id="tr_CPROPERTY_<?= $property['ID'] ?>">
    <td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">
        <?= Loc::getMessage('AS_FORM_LABEL_WB_BARCODE') ?>:
    </td>
    <td width="60%" class="adm-detail-content-cell-r">
        <table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">
            <tbody>
            <tr>
                <td>
                    <input
                        name="PROP[<?= $property['ID'] ?>][<?= $property['PROPERTY_VALUE_ID'] ?: 'n0' ?>]"
                        value="<?= $property['VALUE'] ?>"
                        size="30"
                        type="text"
                    >
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<?
$property = $properties[Config::PROPERTY_IMT_ID];
?>

<tr id="tr_CPROPERTY_<?= $property['ID'] ?>">
    <td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">
        <?= Loc::getMessage('AS_FORM_LABEL_WB_IMT_ID') ?>:
    </td>
    <td width="60%" class="adm-detail-content-cell-r">
        <table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">
            <tbody>
            <tr>
                <td>
                    <input
                        name="PROP[<?= $property['ID'] ?>][<?= $property['PROPERTY_VALUE_ID'] ?: 'n0' ?>]"
                        value="<?= $property['VALUE'] ?>"
                        size="30"
                        type="text"
                        >
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<?
$property = $properties[Config::PROPERTY_NM_ID];
?>

<tr id="tr_CPROPERTY_<?= $property['ID'] ?>">
    <td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">
        <?= Loc::getMessage('AS_FORM_LABEL_WB_NM_ID') ?>:
    </td>
    <td width="60%" class="adm-detail-content-cell-r">
        <table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">
            <tbody>
            <tr>
                <td>
                    <input
                        name="PROP[<?= $property['ID'] ?>][<?= $property['PROPERTY_VALUE_ID'] ?: 'n0' ?>]"
                        value="<?= $property['VALUE'] ?>"
                        size="30"
                        type="text"
                        >
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<?
$property = $properties[Config::PROPERTY_CHRT_ID];
?>

<tr id="tr_CPROPERTY_<?= $property['ID'] ?>">
    <td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">
        <?= Loc::getMessage('AS_FORM_LABEL_WB_CHRT_ID') ?>:
    </td>
    <td width="60%" class="adm-detail-content-cell-r">
        <table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">
            <tbody>
            <tr>
                <td>
                    <input
                        name="PROP[<?= $property['ID'] ?>][<?= $property['PROPERTY_VALUE_ID'] ?: 'n0' ?>]"
                        value="<?= $property['VALUE'] ?>"
                        size="30"
                        type="text"
                    >
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<script>
    (() => {
        let duplicateProperties = <?= CUtil::PhpToJSObject($duplicateProperties)?>;

        duplicateProperties.forEach(code => {
            const elTr = document.querySelector('[id="' + code + '"]');

            if(elTr) {
                elTr.remove();
            }
        })
    })();
</script>