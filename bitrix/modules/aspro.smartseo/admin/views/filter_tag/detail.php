<?php
/**
 *  @var string $alias
 *  @var array $data
 *  @var int $filterRuleId
 *  @var array $listFilterCondition
 *  @var array $listTypes
 *  @var array $listIblockSections
 *  @var array $listTags
 *  @var array $listIdenticalProperty
 *  @var string $gridId
 *  @var string $gridTagItems
 */

use Aspro\Smartseo\Admin\Helper,
    Bitrix\Main\Localization\Loc;

global $APPLICATION;

Loc::loadMessages(__FILE__);

$_suffix = $this->getUnique();
?>
<div id="filter_tag_detail_page_<?= $_suffix ?>" class="aspro-smartseo__form-detail aspro-smartseo__form-detail--inner aspro-ui--animate-fade-in">

  <div page-role="alert" style="display: none;">
    <div class="ui-alert ui-alert-danger ui-alert-icon-danger aspro-ui-form__alert">
      <span class="ui-alert-message" form-role="alert-body"></span>
    </div>
  </div>

  <div class="adm-detail-content-item-block">
    <div>
      <? include $this->getViewPath() . '_form.php'; ?>
    </div>
    <?if($data['ID']):?>
      <div>
        <? include $this->getViewPath() . '_tabs_tag_items.php'; ?>
      </div>
    <?endif;?>
  </div>

  <div page-role="button-panel" class="aspro-smartseo__form-detail__wrapper-footer">
    <div class="aspro-smartseo__form-detail__footer__toolbar">
      <div class="aspro-smartseo__form-detail__buttons">
        <button page-role="save" data-action="save" class="ui-btn ui-btn-sm ui-btn-success" title="<?= Loc::getMessage('SMARTSEO_FORM_HINT_SAVE') ?>">
          <?= Loc::getMessage('SMARTSEO_FORM_BTN_SAVE') ?>
        </button>
        <button page-role="apply" data-action="apply" class="ui-btn ui-btn-sm ui-btn-primary-dark" title="<?= Loc::getMessage('SMARTSEO_FORM_HINT_APPLY') ?>">
          <?= Loc::getMessage('SMARTSEO_FORM_BTN_APPLY') ?>
        </button>
        <a page-role="cancel" data-action="cancel" href="" class="ui-btn ui-btn-sm ui-btn-light" title="<?= Loc::getMessage('SMARTSEO_FORM_HINT_CANCEL') ?>">
          <?= Loc::getMessage('SMARTSEO_FORM_BTN_CANCEL') ?>
        </a>
        <div class="aspro-smartseo__form-detail__footer__checkbox">
            <div class="aspro-smartseo__form-control">
              <input page-role="apply-generate-tag-items" id="checkbox_apply_generate_tag_items_<?= $_suffix ?>" class="adm-designed-checkbox" type="checkbox">
              <label class="adm-designed-checkbox-label" for="checkbox_apply_generate_tag_items_<?= $_suffix ?>" title="<?= Loc::getMessage('SMARTSEO_FORM_CHECKBOX_GENERATE_TAG_ITEMS') ?>">
                <?= Loc::getMessage('SMARTSEO_FORM_CHECKBOX_GENERATE_TAG_ITEMS') ?>
              </label>
            </div>
        </div>
      </div>
      <div class="aspro-smartseo__form-detail__footer__info">
        ID: <span page-role="form-label-ID"><?= $data['ID'] ?: '---' ?></span>
      </div>
    </div>
  </div>
</div>
<script>
BX.message({
    SMARTSEO_MESSAGE_SAVE_SUCCESS: '<?= Loc::getMessage('SMARTSEO_MESSAGE_SAVE_SUCCESS') ?>',
    SMARTSEO_DEFAULT_TAB_NAME: '<?= Loc::getMessage('SMARTSEO_FORM_VALUE_CONDITION_DEFAULT') ?>',
    SMARTSEO_MESSAGE_COUNT_CREATED_TAG_ITEMS: '<?= Loc::getMessage('SMARTSEO_MESSAGE_COUNT_CREATED_TAG_ITEMS') ?>',
});

BX.ajax.loadScriptAjax(
  [
    '<?= $this->getPathModuleScripts() ?>/filter_tag_detail.js',
  ],
  function() {
    new FilterTagDetailPage('filter_tag_detail_page_<?= $_suffix ?>', <?=
      CUtil::PhpToJSObject([
        'alias' => $alias,
        'selectors' => [
            'PARENT_GRID_ID' => $gridId,
            'PARENT_TAB_CONTROL' => 'tabs_tags',
            'FORM' => '#form_tag_' . $_suffix,
            'ITEMS_GRID_ID' => $data['ID'] ? $gridTagItems['GRID_ID'] : 0,
        ],
        'urls' => [
          'MENU_TAG_PROPERTY' => Helper::url('filter_tag/get_menu_tag_property', ['filter_rule' => $filterRuleId]),
          'SAMPLE_TAG_PROPERTY' => Helper::url('filter_tag/html_sample_tag_property', ['filter_rule' => $filterRuleId]),
          'IDENTICAL_PROPERTY' => Helper::url('filter_tag/html_identical_property', ['filter_rule' => $filterRuleId]),
        ],
      ])
    ?>);
  }
);
</script>