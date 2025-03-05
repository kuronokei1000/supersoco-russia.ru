<?php
/**
 * @var string $alias
 * @var array $data
 * @var array $listSites
 * @var array $listIblockTypes
 * @var array $listIblocks
 * @var array $listIblockSections
 */

use Aspro\Smartseo,
    Aspro\Smartseo\Admin\Helper,
    Bitrix\Main\Localization\Loc;

$isVisibleTopInfo = $_COOKIE['SMARTSEO_VISIBLE_TOP_INFO'] === 'Y';
?>
<div class="aspro-smartseo__form-detail__info">
  <div class="aspro-smartseo__form-detail__info__row">
    <div class="aspro-smartseo__form-detail__info__col">
      <table>
        <? if ($data['ID']) : ?>
            <tr>
              <td>
                <span><?= Loc::getMessage('SMARTSEO__FORM_ENTITY__ID') ?>:</span>
              </td>
              <td>
                <span><?= $data['ID'] ?></span>
              </td>
            </tr>
        <? endif ?>
      </table>
    </div>
    <div class="aspro-smartseo__form-detail__info__col"></div>

    <? if ($data) : ?>
        <div class="aspro-smartseo__form-detail__info__arraow-wrapper">
          <div page-role="action-expand-info"
               class="adm-detail-title-setting aspro-smartseo__form-detail__info__arrow-expand <?= $isVisibleTopInfo ? 'adm-detail-title-setting-active'
              : '' ?>">
            <span class="adm-detail-title-setting-btn adm-detail-title-expand"></span>
          </div>
        </div>
    <? endif ?>

  </div>

  <? if ($data) : ?>
      <div class="aspro-smartseo__form-detail__info__wrapper" page-role="container-expand-info" style="<?= !$isVisibleTopInfo ? 'display: none' : '' ?>">
        <div class="aspro-smartseo__form-detail__separator"></div>
        <div class="aspro-smartseo__form-detail__info__row">
          <div class="aspro-smartseo__form-detail__info__col">
            <table>
              <? if ($data['DATE_CREATE'] && $data['DATE_CREATE'] instanceof \Bitrix\Main\Type\DateTime) : ?>
                  <tr>
                    <td>
                      <span><?= Loc::getMessage('SMARTSEO__FORM_ENTITY__DATE_CREATE') ?>: </span>
                    </td>
                    <td>
                      <span><?= $data['DATE_CREATE']->toString() ?></span>
                    </td>
                  </tr>
              <? endif ?>
              <? if ($data['DATE_CHANGE'] && $data['DATE_CHANGE'] instanceof \Bitrix\Main\Type\DateTime) : ?>
                  <tr>
                    <td>
                      <span><?= Loc::getMessage('SMARTSEO__FORM_ENTITY__DATE_CHANGE') ?>: </span>
                    </td>
                    <td>
                      <span><?= $data['DATE_CHANGE']->toString() ?></span>
                    </td>
                  </tr>
              <? endif ?>
            </table>
          </div>
        </div>
      </div>

  <? endif ?>
</div>