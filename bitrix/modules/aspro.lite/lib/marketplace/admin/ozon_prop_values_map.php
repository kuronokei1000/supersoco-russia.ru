<?php
/**
 * @global CMain $APPLICATION
 */
define('STOP_STATISTICS', true);
define('BX_SECURITY_SHOW_MESSAGE', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

// $APPLICATION->RestartBuffer();

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/ozon.php');

use
    \Bitrix\Main\Application,
    \Bitrix\Main\Localization\Loc,
    \Aspro\Lite\Marketplace\Ajax\Ozon as AjaxHelper,
    \Aspro\Lite\Marketplace\Property,
    \Aspro\Lite\Marketplace\Maps\Ozon as OzonMap;

$request = Application::getInstance()->getContext()->getRequest();

$url = $request->getHeader('origin').$request->getRequestedPage();
$postData = \Bitrix\Main\Web\Json::encode($request->getPostList()->getValues());

if (!check_bitrix_sessid() || !$GLOBALS['USER']->isAdmin()) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}

$clientId = $request->get('CLIENT_ID');
$token = $request->get('API_KEY');
$bxPropertyCode = $request->get('bxCode');
$bxSkuPropertyCode = $request->get('bxSkuCode');
$ozonPropertyId = $request->get('ozonId');
$ozonCategoryId = $request->get('ozonCategoryId');

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

if (
    (!$bxPropertyCode && is_null($bxSkuPropertyCode)) 
    || (!$bxPropertyCode && !$bxSkuPropertyCode)
    ) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    ShowError(GetMessage('AS_ERROR_BITRIX_PROPERTY_CODE'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}
if (!$ozonPropertyId) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    ShowError(GetMessage('AS_ERROR_OZON_PROPERTY_ID'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}
if (!$ozonCategoryId) {
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
    ShowError(GetMessage('AS_ERROR_OZON_PROPERTY_ID'));
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
    die();
}

$ajaxHelper = new AjaxHelper($clientId, $token);

if (
    (!$obPropValues = $ajaxHelper->getPropValues($ozonCategoryId, $ozonPropertyId)->getAll()) && !$request->get('action')
    || $request->get('action') === 'sync'
) {?>
    <?if ($request->get('action') === 'sync') {
        $ajaxHelper->removePropValues($ozonCategoryId, $ozonId);
    }?>
    <p class="errortext"><?=GetMessage("GET_PROPERTY_VALUES_WARNING");?></p>
    <div id="progress"><?=GetMessage("GET_PROPERTY_VALUES");?> <span id="progress_value">0</span></div>
    <script>
        ;(function requestData(step = 1, last_value_id = 0) {
            const propId = '<?=$request->get("ozonId")?>'
            const customLimitProps = ['33', '121', '88', '95', '20034'];
            let limitStep = 100;

            if(customLimitProps.includes(propId)) {
                limitStep = 4;
            }
            if (step > limitStep) return;

            const url = '<?=$url;?>';
            const postData = <?=$postData;?>

            delete postData.action;

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: Object.assign({}, postData, {
                    action: 'getPropValues',
                    last_value_id: last_value_id,
                    step: step,
                    controller: 'ozon'
                }),
                success: (data) => {
                    const $value = document.getElementById('progress_value');
                    $value.textContent = parseInt($value.textContent) + data.result.length;

                    if (data.has_next) {
                        requestData(++step, data.last_value_id);
                    } else {
                        setTimeout(() => {
                            top.BX.closeWait();
                            top.BX.WindowManager.Get().Close();
    
                            const obDetailWindow = new BX.CAdminDialog({
                                'content_url': url + '?bxpublic=Y',
                                'content_post': postData,
                                'width': 510, 
                                'height': 500,
                                'resizable': false
                            });
                            obDetailWindow.Show();
                        }, 1000)
                    }
                }
            });
        })()
    </script>
    <?
    die();
}

if ($ajaxHelper->checkRequest($request)) {
    $APPLICATION->RestartBuffer();

    switch ($request->get('action')) {
        case 'getPropValues':
            echo \Bitrix\Main\Web\Json::encode($ajaxHelper->addPropValues($ozonCategoryId, $ozonId, $request->get('last_value_id'), $request->get('step')));
            break;
        case 'searchValue':
            echo \Bitrix\Main\Web\Json::encode($ajaxHelper->searchValues($obPropValues, $request->get('value')));
            break;
    }

    die();
}

$arTitleProps = [];
if ($arProps = $ajaxHelper->getCategoryProperties($ozonCategoryId)) {
    if (!$ozonProperty = reset(array_filter($arProps, function($prop) use ($ozonPropertyId) { return $prop['code'] == $ozonPropertyId;}))) {
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
        ShowError(GetMessage('AS_ERROR_OZON_PROPERTY_ID'));
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
        die();
    }
    $arTitleProps[] = Loc::getMessage('MATCHING_PROPERTY_VALUES_OZON_TITLE', [
        '#OZON_PROPERTY#' => $ozonProperty['name'],
    ]);
}

$bxProperty = null;
if ($bxPropertyCode) {
    $bxProperty = new Property($bxPropertyCode, $iblockId);
    
    if (!$bxProperty->info) {
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
        ShowError(GetMessage('AS_ERROR_BITRIX_PROPERTY_CODE_WRONG'));
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
        die();
    }

    if (!$bxProperty->isCorrectType()) {
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
        ShowError(GetMessage('AS_ERROR_BITRIX_PROPERTY_CODE_TYPE'));
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
        die();
    }
}

if ($bxProperty) {
    $bxPropertyValues = $bxProperty->getValues();
    $arAllProperties = [
        [
            'VALUE' => Loc::getMessage('PROP_VALUES_FROM_CATALOG'),
            'ITEMS' => $bxPropertyValues
        ]
    ];
    $arTitleProps[] = Loc::getMessage('MATCHING_PROPERTY_VALUES_CATALOG_TITLE', [
        '#BITRIX_PROPERTY#' => $bxProperty->info['NAME'],
    ]);
}

$multiple = '';
if ($bxSkuPropertyCode) {
    $bxSkuProperty = new Property($bxSkuPropertyCode);
    if (!$bxSkuProperty->info) {
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
        ShowError(GetMessage('AS_ERROR_BITRIX_SKU_PROPERTY_CODE_WRONG'));
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
        die();
    }
    if (!$bxSkuProperty->isCorrectType()) {
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
        ShowError(GetMessage('AS_ERROR_BITRIX_SKU_PROPERTY_CODE_TYPE'));
        require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
        die();
    }

    $bxSkuPropertyValues = $bxSkuProperty->getValues();

    if (!$bxProperty || ($bxProperty && !$bxSkuProperty->isSameEntityAs($bxProperty))) {
        $arAllProperties[] = [
            'VALUE' => Loc::getMessage('PROP_VALUES_FROM_SKU'),
            'ITEMS' => $bxSkuPropertyValues
        ];

        $multiple = 'multiple';
        $arTitleProps[] = Loc::getMessage('MATCHING_PROPERTY_VALUES_SKU_TITLE', [
            '#BITRIX_SKU_PROPERTY#' => $bxSkuProperty->info['NAME'],
        ]);
    }
}

$adapter = $ajaxHelper->adapter;
$map = new OzonMap($iblockId, $adapter);

if ($request->getRequestMethod() == 'POST' && (!empty($request->get('SaveMap')))) {
    if ($map->addPropValues($request->get('MAP'))) {
        $map->saveValuesMap();
    }
    ?>
    <script type="text/javascript">
        top.BX.closeWait();
        top.BX.WindowManager.Get().Close();
    </script>
    <?
    die();
}

$APPLICATION->SetTitle(Loc::getMessage('AS_MAPPING_PROP_VALUES_SETUP'));
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$aTabs = [
    [
        'DIV' => 'wb-setting-map',
        'TAB' => Loc::getMessage('AS_MAPPING_SETUP_TAB_1'),
        'TITLE' => implode('<br/>', $arTitleProps)
    ],
];

$mapStructure = $map->getValuesStructure($ozonPropertyId);?>
<form name="wb_form" method="POST" action="<?= $APPLICATION->GetCurPage(); ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">
    <input type="hidden" name="bxpublic" value="Y">
    <input type="hidden" name="Update" value="Y"/>
    <input type="hidden" name="IBLOCK_ID" value="<? echo $iblockId; ?>"/>
    <input type="hidden" name="API_KEY" value="<? echo $token; ?>"/>
    <input type="hidden" name="CLIENT_ID" value="<? echo $clientId; ?>"/>
    <input type="hidden" name="bxCode" value="<? echo $bxPropertyCode; ?>"/>
    <input type="hidden" name="bxSkuCode" value="<? echo $bxSkuPropertyCode; ?>"/>
    <input type="hidden" name="ozonId" value="<? echo $ozonPropertyId; ?>"/>
    <input type="hidden" name="ozonCategoryId" value="<? echo $ozonCategoryId; ?>"/>
    <? echo bitrix_sessid_post(); ?>
    <input type="hidden" name="SaveMap" value="true">

    <?php
    $tabControl = new \CAdminTabControl('tabControl', $aTabs, true, true);

    $tabControl->BeginNextTab();?>

    <?
    $arPropValues = reset($obPropValues);
    $arValues = $ajaxHelper->getFieldValues($arPropValues);
    ?>
    
    <table class="mpm__table props-table">
        <tr>
            <td>
                <div class="mpm-wrapper">
                    <input type="hidden" name="MAP[category]" value="<?=$ozonCategoryId;?>"/>
                    <input type="hidden" name="MAP[property]" value="<?=$ozonPropertyId;?>"/>

                    <?if ($mapStructure):?>
                        <?foreach ($mapStructure  as $keyMap => $valueMap):?>
                            <input type="hidden" name="MAP[values][<?=$keyMap;?>]" value="<?=implode(',', (array)$valueMap);?>"/>
                        <?endforeach;?>
                    <?endif;?>

                    <table class="mpm__table-properties" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th><?= Loc::getMessage('AS_OZON_PROPERTY_VALUES') ?></th>
                                <th><?= Loc::getMessage('AS_BX_PROPERTY_VALUES') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?if (count($arValues) > 100):?>
                                <?if ($mapStructure):?>
                                    <?foreach($mapStructure as $ozonKey => $bxKey):?>
                                        <tr>
                                            <td class="mpm__td-property-name">
                                                <div class="mpm__wrapper mpm__wrapper--flex">
                                                    <select class="select2-ajax-props">
                                                        <option value="<?=$ozonKey;?>"><?=$ajaxHelper->getTextById($obPropValues, $ozonKey);?></option>
                                                    </select>
                                                    <span title="<?=GetMessage('REMOVE_PROPERTY_VALUES');?>" class="mpm__remove remove-values"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mpm__wrapper mpm__wrapper--flex">
                                                    <select class="select2-props" <?=$multiple;?> name="MAP[values][<?=$ozonKey;?>]<?=($multiple ? '[]' : '');?>" data-name="MAP[values][#ID#]<?=($multiple ? '[]' : '');?>">
                                                        <?if (!$multiple):?><option value="">-</option><?endif;?>
                                                        <?foreach ($arAllProperties  as $arGroupProperties):?>
                                                            <?if ($multiple):?><optgroup label="<?=$arGroupProperties['VALUE']?>"><?endif;?>
                                                            <?foreach ($arGroupProperties['ITEMS'] as $arBxPropertyValue):?>
                                                                <?
                                                                if (is_array($bxKey)) {
                                                                    $selected = in_array($arBxPropertyValue['ID'], $bxKey) ? 'selected' : '';
                                                                } else {
                                                                    $selected = $bxKey == $arBxPropertyValue['ID'] ? 'selected' : '';
                                                                }
                                                                ?>
                                                                <option value="<?=$arBxPropertyValue['ID'];?>" <?=$selected;?>><?=$arBxPropertyValue['VALUE'];?></option>
                                                            <?endforeach;?>
                                                            <?if ($multiple):?></optgroup><?endif;?>
                                                        <?endforeach;?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    <?endforeach;?>
                                <?endif;?>
                                <tr>
                                    <td colspan="2">
                                        <template>
                                           <tr>
                                                <td class="mpm__td-property-name">
                                                    <div class="mpm__wrapper mpm__wrapper--flex">
                                                        <select class="select2-ajax-props">
                                                            <option value="">-</option>
                                                        </select>
                                                        <span title="<?=GetMessage('REMOVE_PROPERTY_VALUES');?>" class="mpm__remove remove-values"></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mpm__wrapper mpm__wrapper--flex">
                                                        <select class="select2-props" <?=$multiple;?> data-name="MAP[values][#ID#]<?=($multiple ? '[]' : '');?>">
                                                            <?if (!$multiple):?><option value="">-</option><?endif;?>
                                                            <?foreach ($arAllProperties  as $arGroupProperties):?>
                                                                <?if ($multiple):?><optgroup label="<?=$arGroupProperties['VALUE']?>"><?endif;?>
                                                                <?foreach ($arGroupProperties['ITEMS'] as $arBxPropertyValue):?>
                                                                    <option value="<?=$arBxPropertyValue['ID'];?>"><?=$arBxPropertyValue['VALUE'];?></option>
                                                                <?endforeach;?>
                                                                <?if ($multiple):?></optgroup><?endif;?>
                                                            <?endforeach;?>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <span class="add_more"><?=GetMessage('ADD_MORE_ROW')?></span>
                                    </td>
                                </tr>
                            <?else:?>
                                <?\Bitrix\Main\Type\Collection::sortByColumn($arValues, 'text')?>
                                <?foreach ($arValues  as $arPropertyValue):?>
                                    <tr>
                                        <td class="mpm__td-property-name">
                                            <?=$arPropertyValue['text'];?>
                                        </td>
                                        <td>
                                            <select class="select2-props" <?=$multiple;?> name="MAP[values][<?=$arPropertyValue['id']?>]<?=($multiple ? '[]' : '');?>">
                                                <?if (!$multiple):?><option value="">-</option><?endif;?>
                                                <?foreach ($arAllProperties  as $arGroupProperties):?>
                                                    <?if ($multiple):?><optgroup label="<?=$arGroupProperties['VALUE']?>"><?endif;?>
                                                        <?foreach ($arGroupProperties['ITEMS'] as $arBxPropertyValue):?>
                                                            <?
                                                            $selected = '';
                                                            if ($mapStructure) {
                                                                $mapValue = $mapStructure[$arPropertyValue['id']];
                                                                if (is_array($mapValue)) {
                                                                    $selected = in_array($arBxPropertyValue['ID'], $mapValue) ? 'selected' : '';
                                                                } else {
                                                                    $selected = $mapValue == $arBxPropertyValue['ID'] ? 'selected' : '';
                                                                }
                                                            }
                                                            ?>
                                                            <option value="<?=$arBxPropertyValue['ID'];?>" <?=$selected;?>><?=$arBxPropertyValue['VALUE'];?></option>
                                                        <?endforeach;?>
                                                    <?if ($multiple):?></optgroup><?endif;?>
                                                <?endforeach;?>
                                            </select>
                                        </td>
                                    </tr>
                                <?endforeach;?>
                            <?endif;?>
                        </tbody>
                    </table>
                </div>
                </td>
            </tr>
        </table>
            
    <script>
        ;(() => {
            const url = '<?=$url;?>';
            const postData = <?=$postData;?>
            
            function init(){
                $('.select2-props').select2({
                    dropdownParent: '.mpm-wrapper',
                    minimumResultsForSearch: Infinity,
                    multiple: '<?=(boolean)$multiple;?>'
                }).on('select2:unselect', (event) => {
                    const $target = event.target
                    if (!$target.value) {
                        const name = $target.name.replace('[]', '');
                        const $hiddenMapInput = document.querySelector(`input[name='${name}']`)
                        if ($hiddenMapInput) {
                            $hiddenMapInput.value = '';
                        }
                    }
                });
                $('.select2-ajax-props').select2({
                    minimumInputLength: 1,
                    dropdownParent: '.mpm-wrapper',
                    language: {
                        searching: function () {
                            return "<?=GetMessage('AS_SELECT2_SEARCHING');?>"
                        },
                        inputTooShort: function () {
                            return "<?=GetMessage('AS_SELECT2_TOO_SHORT_1');?>"
                        },
                        noResults: function () {
                            return "<?=GetMessage('AS_SELECT2_ERROR_LOADING');?>"
                        },
                        errorLoading: function () {
                            return "<?=GetMessage('AS_SELECT2_ERROR_LOADING');?>"
                        }
                    },
                    ajax: {
                        url: url + '?bxpublic=Y',
                        delay: 250,
                        dataType: 'json',
                        type: 'POST',
                        data: (params) => {
                            console.log(params);
                            return Object.assign({}, postData, {
                                action: 'searchValue',
                                value: params.term,
                                controller: 'ozon'
                            });
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                    },
                }).on('select2:select', (event) => {
                    const id = event.params.data.id
                    const select = event.target.closest('tr').querySelector('.select2-props')
                    const name = select.dataset.name
    
                    if (id) {
                        select.setAttribute('name', name.replace('#ID#', id))
                    } else {
                        select.removeAttribute('name')
                    }
                });
            }

            function addRow(target = document.querySelector('.add_more')) {
                if (target) {
                    const $parentTr = target.closest('tr')
                    const $parentTable = target.closest('tbody')
                    const $template = target.previousElementSibling.content.cloneNode(true)
    
                    $parentTable.insertBefore($template, $parentTr);
                }
                init();
            }
            addRow()

            document.querySelector('.add_more')?.addEventListener('click', e => {
                addRow(e.target)
            })
            document.querySelector('.props-table').addEventListener('click', e => {
                if (e.target.classList.contains('remove-values')) {
                    const select = e.target.closest('tr').querySelector('.select2-props')
                    // select.removeAttribute('name');
                    select.value = '';

                    const selectAjax = e.target.closest('tr').querySelector('.select2-ajax-props')
                    const event = new Event('change');
                    
                    selectAjax.value = null
                    selectAjax.dispatchEvent(event);
                }
            })
            init();
        })()
    </script>

    <style>
        .adm-detail-content .add_more {
            cursor: pointer;
            color: #2675d7;
        }
    </style>

    <?php
    $tabControl->EndTab();
    $tabControl->Buttons(array());
    // $tabControl->End();
    ?>
</form>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/epilog_admin.php') ?>