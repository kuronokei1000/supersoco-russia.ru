<?
namespace Aspro\Lite\Mobile\Traits;

use \Bitrix\Main\Config\Option;

use CLite as Solution;
use \Aspro\Lite\Functions\Extensions;
use \Aspro\Lite\Functions\ExtensionsMobile;

trait Extension {
    public static function addExtensions()
    {
        global $APPLICATION, $arTheme;

        // register js and css libs
        Extensions::register();
        ExtensionsMobile::register();

        Extensions::init(['metrika.goals']);

        if (
            $arTheme['ORDER_VIEW'] === 'Y' ||
            $arTheme['CATALOG_COMPARE'] === 'Y' ||
            $arTheme['SHOW_FAVORITE'] === 'Y'
        ) {
            /* item action */
            Extensions::init('item_action');
        }

        /* basket */
        if (Solution::IsBasketPage()) {
            Extensions::init(['basket', 'stickers']);
            if ($arTheme['SHOW_SHARE_BASKET'] === 'Y') {
                Extensions::init('share');
            }

            if (\Bitrix\Main\Loader::includeModule("currency")) {
                \CJSCore::Init(array('currency'));
                $currencyFormat = \CCurrencyLang::GetFormatDescription(\CSaleLang::GetLangCurrency(SITE_ID));
            }

            if (is_array($currencyFormat)) {?>
                <script>function jsPriceFormat(_number) {
                    BX.Currency.setCurrencyFormat('<?= \CSaleLang::GetLangCurrency(SITE_ID); ?>', <? echo \CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
                    return BX.Currency.currencyFormat(_number, '<?= \CSaleLang::GetLangCurrency(SITE_ID); ?>', true);
                }</script>
            <?}
        }

        /* order */
        if (Solution::IsOrderPage()) {
            Extensions::init(['validate', 'order', 'eye.password']);
        }

        ExtensionsMobile::init('gesture');
    }
}
?>