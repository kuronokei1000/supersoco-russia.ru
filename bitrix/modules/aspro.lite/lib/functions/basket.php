<?
namespace Aspro\Lite\Functions;


class Basket
{
    const MODULE_ID = \CLite::moduleID;

    public static function FormatSumm($strPrice, $quantity){
		$strSumm = '';

		if(strlen($strPrice = trim($strPrice))){
			$currency = '';
			$price = floatval(str_replace(' ', '', $strPrice));
			$summ = $price * $quantity;

			$strSumm = str_replace(trim(str_replace($currency, '', $strPrice)), str_replace('.00', '', number_format($summ, 2, '.', ' ')), $strPrice);
		}

		return $strSumm;
	}

    public static function showPriceFormat ()
    {
        if (\Bitrix\Main\Loader::includeModule('currency') && \Bitrix\Main\Loader::includeModule('sale')) {
            \CJSCore::Init(array('currency'));
            $currencyFormat = \CCurrencyLang::GetFormatDescription(\CSaleLang::GetLangCurrency(SITE_ID));
        }
        ?>
        <script type="text/javascript">
            <?if(is_array($currencyFormat)):?>
                function jsPriceFormat(_number){
                    BX.Currency.setCurrencyFormat('<?=\CSaleLang::GetLangCurrency(SITE_ID);?>', <? echo \CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
                    return BX.Currency.currencyFormat(_number, '<?=\CSaleLang::GetLangCurrency(SITE_ID);?>', true);
                }
            <?endif;?>
        </script>
    <?}
}
?>