<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

?>

<div class="bx-sbb-empty-cart-container">
    <div class="bx-sbb-empty-cart-image"></div>
    <div class="bx-sbb-empty-cart-text"><?=Loc::getMessage("SBB_EMPTY_BASKET_TITLE")?></div>

    <?php if (!empty($arParams['EMPTY_BASKET_HINT_PATH'])) : ?>
        <div class="bx-sbb-empty-cart-desc">
            <a href="/catalog/electromotobikes/">
                <button class="btn btn-warning">Вперед за покупками</button>
            </a>
        </div>
    <?php endif; ?>
</div>