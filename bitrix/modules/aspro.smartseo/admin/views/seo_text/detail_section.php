<?php
/**
 * @var string $alias
 * @var string $aliasProperty
 * @var array $data
 * @var array $dataProperties
 * @var array $listSites
 * @var array $listIblockTypes
 * @var array $listIblocks
 * @var array $listIblockSections
 */

use Aspro\Smartseo,
    Aspro\Smartseo\Admin\Helper,
    Bitrix\Main\Localization\Loc;

global $APPLICATION;

\Bitrix\Main\UI\Extension::load('ui.notification');
\Bitrix\Main\UI\Extension::load('ui.hint');

$APPLICATION->AddHeadScript('/bitrix/js/' . Smartseo\General\Smartseo::MODULE_ID . '/src/core_custom.js');
$APPLICATION->AddHeadScript($this->getPathSelfScripts() . '/detail_section.js');

$elementTitle = '';

if($data['ID'] && $data['NAME']) {
    $elementTitle = htmlspecialchars($data['NAME']);
}

if($data['ID'] && !$data['NAME']) {
    $elementTitle = Loc::getMessage('SMARTSEO_ELEMENT_DEFAULT_NAME', [
        '#ID#' => $data['ID'],
    ]);
}

$pageTitle = Loc::getMessage('SMARTSEO_INDEX__TITLE__SEO_TEXT')
  . ': ' . ($data['ID'] ? $elementTitle : Loc::getMessage('SMARTSEO_PAGE_TITLE'))
  . ': ' . ($data['ID'] ? Loc::getMessage('SMARTSEO_AI__ACTION__EDITING') : Loc::getMessage('SMARTSEO_AI__ACTION__ADDING'));

$APPLICATION->setTitle($pageTitle);

$adminContextMenu = new CAdminUiContextMenu(array_filter([
      [
          'TEXT' => Loc::getMessage('SMARTSEO_MENU_ADD'),
          'TITLE' => Loc::getMessage('SMARTSEO_MENU_ADD'),
          'LINK' => Helper::url('seo_text_section/detail'),
          'ICON' => 'edit',
      ],
      $data ? [
        'TEXT' => Loc::getMessage('SMARTSEO_MENU_DELETE'),
        'TITLE' => Loc::getMessage('SMARTSEO_MENU_DELETE'),
        'LINK' => '',
        'ICON' => 'delete',
        'ONCLICK' => "contextMenuHandler.delete($data[ID])"
      ] : [],
  ]));

$adminTabControl = new CAdminTabControl('seo_text_section_tab_control', [
    [
        'DIV' => 'seotext_main',
        'TAB' => Loc::getMessage('SMARTSEO_TAB_GENERAL_NAME'),
        'TITLE' => Loc::getMessage('SMARTSEO_TAB_GENERAL_TITLE'),
        'ICON' => '',
    ],
  ], true, true);

?>

<div id="seo_text_detail" class="aspro-smartseo__form-detail">
  <div class="aspro-smartseo__form-detail__toolbar">
    <div class="aspro-smartseo__form-detail__col">
      <a href="<?= Helper::url('seo_text/list') ?>" class="ui-btn ui-btn-light-border">
        <?= Loc::getMessage('SMARTSEO_AI__ACTION__BACK_LIST') ?>
      </a>
    </div>
    <div class="aspro-smartseo__form-detail__col">
      <? $adminContextMenu->Show() ?>
    </div>
  </div>

  <? include $this->getViewPath() . 'detail_section/_top_info.php'; ?>

  <div seo-text-form-role="alert" class="ui-alert ui-alert-danger ui-alert-icon-success aspro-ui-form__alert" style="display: none;">
    <span class="ui-alert-message" form-role="alert-body"></span>
  </div>

  <div class="aspro-smartseo__form-detail__body">
    <? $adminTabControl->Begin() ?>

    <? $adminTabControl->BeginNextTab() ?>

    <tr>
      <td colspan="2">
        <? include $this->getViewPath() . 'detail_section/_form.php'; ?>
      </td>
    </tr>

    <tr class="heading" >
      <td colspan="2">
        <?= Loc::getMessage('SMARTSEO_FORM_GROUP_PROPERTIES') ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <? include $this->getViewPath() . 'detail_section/_form_properties.php'; ?>
      </td>
    </tr>

    <? $adminTabControl->Buttons() ?>

    <div class="aspro-smartseo__form-detail__buttons">
      <? if($data) : ?>
        <button seo-text-form-role="save" data-action="save" class="ui-btn ui-btn-success" title="<?= Loc::getMessage('SMARTSEO_AI__BTN__SAVE') ?>">
            <?= Loc::getMessage('SMARTSEO_AI__BTN__SAVE') ?>
        </button>
        <button seo-text-form-role="apply" data-action="update_confirm" class="ui-btn ui-btn-primary-dark" title="<?= Loc::getMessage('SMARTSEO_FORM_HINT_BTN_SAVE_AND_EXECUTE') ?>">
         <?= Loc::getMessage('SMARTSEO_FORM_BTN_SAVE_AND_REFRESH') ?>
        </button>
      <? else: ?>
        <button seo-text-form-role="apply" data-action="update_confirm" class="ui-btn ui-btn-primary-dark" title="<?= Loc::getMessage('SMARTSEO_FORM_HINT_BTN_EXECUTE') ?>">
         <?= Loc::getMessage('SMARTSEO_FORM_BTN_REFRESH') ?>
        </button>
      <? endif ?>
      <a seo-text-form-role="cancel" data-action="cancel" href="<?= Helper::url('seo_text/list') ?>" class="ui-btn ui-btn-light" title="<?= Loc::getMessage('SMARTSEO_AI__BTN__CLOSE') ?>">
        <?= Loc::getMessage('SMARTSEO_AI__BTN__CLOSE') ?>
      </a>
      <? if(!$data) : ?>
      <div class="aspro-smartseo__form-detail__footer__checkbox">
        <div class="aspro-smartseo__form-control">
          <input page-role="save-in-table" id="checkbox_save_in_table" class="adm-designed-checkbox" type="checkbox">
          <label class="adm-designed-checkbox-label" for="checkbox_save_in_table" title="<?= Loc::getMessage('SMARTSEO_FORM_CHECKBOX_SAVE_IN_TABLE') ?>">
            <?= Loc::getMessage('SMARTSEO_FORM_CHECKBOX_SAVE_IN_TABLE') ?>
          </label>
        </div>
      </div>
      <? endif ?>
    </div>

    <? $adminTabControl->End() ?>
  </div>
</div>

<div class="ui-alert ui-alert-danger aspro-ui-util--border1">
  <span class="ui-alert-message"><?= Loc::getMessage('SMARTSEO_MESSAGE_WARNING') ?></span>
</div>

<script>
    var phpObjectSeoTextSection = <?=
    CUtil::PhpToJSObject([
        'aliasProperty' => $aliasProperty,
        'urls' => [
            'FIELD_OPTION_IBLOCK_TYPE' => Helper::url('seo_text_section/get_option_iblock_type', ['sessid' => bitrix_sessid()]),
            'FIELD_OPTION_IBLOCK' => Helper::url('seo_text_section/get_option_iblock', ['sessid' => bitrix_sessid()]),
            'FIELD_OPTION_IBLOCK_SECTIONS' => Helper::url('seo_text_section/get_option_iblock_sections', ['sessid' => bitrix_sessid()]),
            'MENU_SEO_PROPERTY' => Helper::url('seo_text_section/get_menu_seo_property', ['seo_text' => $data['ID'], 'sessid' => bitrix_sessid()]),
            'SAMPLE_SEO_PROPERTY' => Helper::url('seo_text_section/get_sample_seo_property', ['seo_text' => $data['ID'], 'sessid' => bitrix_sessid()]),
            'MENU_ADD_PROPERTY' => Helper::url('seo_text_section/get_menu_section_property', ['seo_text' => $data['ID'], 'sessid' => bitrix_sessid()]),
            'GET_PROPERTY_CONTROL' => Helper::url('seo_text_section/get_section_property_control', ['seo_text' => $data['ID'], 'sessid' => bitrix_sessid()]),
            'DELETE' => Helper::url('seo_text_section/delete', ['sessid' => bitrix_sessid()]),
            'COPY' => Helper::url('seo_text_section/copy', ['sessid' => bitrix_sessid()]),
        ],
    ]);
    ?>

    BX.message({
        SMARTSEO_POPUP_BTN_SAVE_AND_REFRESH: '<?= Loc::getMessage('SMARTSEO_FORM_BTN_REFRESH') ?>',
        SMARTSEO_POPUP_BTN_DELETE: '<?= Loc::getMessage('SMARTSEO_AI__BTN__DELETE') ?>',
        SMARTSEO_POPUP_BTN_CANCEL: '<?= Loc::getMessage('SMARTSEO_AI__BTN__CANCEL') ?>',
        SMARTSEO_POPUP_BTN_CLOSE: '<?= Loc::getMessage('SMARTSEO_AI__BTN__CLOSE') ?>',
        SMARTSEO_POPUP_MESSAGE_DELETE: '<?= Loc::getMessage('SMARTSEO_AI__MESSAGE__DELETE') ?>',
        SMARTSEO_MESSAGE_UPDATE_CONFIRM: '<?= Loc::getMessage('SMARTSEO_MESSAGE_UPDATE_CONFIRM') ?>',
    });
</script>