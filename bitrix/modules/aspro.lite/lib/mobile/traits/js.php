<?
namespace Aspro\Lite\Mobile\Traits;

use \Bitrix\Main\Config\Option;

use CLite as Solution;

trait Js {
    public static function setJSOptions()
    {
        global $APPLICATION;
        $MESS['MIN_ORDER_PRICE_TEXT']= Option::get(Solution::moduleID, 'MIN_ORDER_PRICE_TEXT', GetMessage('MIN_ORDER_PRICE_TEXT_EXAMPLE'), SITE_ID);
		$arFrontParametrs = Solution::GetFrontParametrsValues(SITE_ID);
		?>
		<?if ($arFrontParametrs['SHOW_LICENCE'] == 'Y') {
			ob_start();
				include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/licenses_text.php'));
			$license_text = ob_get_contents();
			ob_end_clean();
			$MESS['LICENSES_TEXT'] = $license_text;
		}?>
		<?if ($arFrontParametrs['SHOW_OFFER'] == 'Y') {
			ob_start();
				include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/offer_text.php'));
			$license_text = ob_get_contents();
			ob_end_clean();
			$MESS['OFFER_TEXT'] = $license_text;
		}
        ?><script data-skip-moving="true">
			var solutionName = 'arLiteOptions';
			var arAsproOptions = window[solutionName] = ({});
			var arAsproCounters = {};
			var arBasketItems = {};
            var appAspro = {};
		</script>
		<script src="<?=self::$solutionPath.'/js/setTheme.php?site_id='.SITE_ID.'&site_dir='.SITE_DIR?>" data-skip-moving="true"></script>
        <?$APPLICATION->AddHeadString('<script>BX.message('.\CUtil::PhpToJSObject($MESS, false).')</script>', true);?>
		<script>
        if(arAsproOptions.SITE_ADDRESS){
			arAsproOptions.SITE_ADDRESS = arAsproOptions.SITE_ADDRESS.replace(/'/g, "");
		}
        arAsproOptions['SITE_TEMPLATE_PATH_MOBILE'] = '<?=SITE_TEMPLATE_PATH;?>';
        arAsproOptions.PAGES.FRONT_PAGE = window[solutionName].PAGES.FRONT_PAGE = "<?=Solution::IsMainPage()?>";
		arAsproOptions.PAGES.BASKET_PAGE = window[solutionName].PAGES.BASKET_PAGE = "<?=Solution::IsBasketPage()?>";
		arAsproOptions.PAGES.ORDER_PAGE = window[solutionName].PAGES.ORDER_PAGE = "<?=Solution::IsOrderPage()?>";
		arAsproOptions.PAGES.PERSONAL_PAGE = window[solutionName].PAGES.PERSONAL_PAGE = "<?=Solution::IsPersonalPage()?>";
		arAsproOptions.PAGES.CATALOG_PAGE = window[solutionName].PAGES.CATALOG_PAGE = "<?=Solution::IsCatalogPage()?>";
        </script><?
    }

    public static function addCommonJs()
    {
        global $APPLICATION;
        
        // inline jquery
		$APPLICATION->AddHeadString('<script data-skip-moving="true" src="'.Solution::getJQuerySrc().'"></script>');

        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/general.min.js');

        self::addJsFromSolutionTemplate();
        self::addJsByCondition();
    }

    public static function addJsFromSolutionTemplate()
    {
        global $APPLICATION;
        // $APPLICATION->AddHeadScript(self::$solutionPath.'/js/colored.js');
    }

    public static function addJsByCondition(){
		global $APPLICATION, $arTheme;
        
        if ($arTheme['HEADER_MOBILE_FIXED'] === 'Y' && $arTheme['HEADER_MOBILE_SHOW'] === 'SCROLL_TOP') {
            $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/conditional/headerFixedOnScroll.min.js');
        }

	}

    public static function autoloadJs()
    {
		$arBlocks = glob($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/js/autoload/*.js');

		foreach ($arBlocks as $blockPath) {
			if (strpos($blockPath, '.min.js') === false && strpos($blockPath, '__') === false) {
				$currentPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $blockPath);
                $minFile = str_replace('.js', '.min.js', $currentPath);
                if (file_exists($_SERVER['DOCUMENT_ROOT'].$minFile)) {
                    $currentPath = $minFile;
                }
				$GLOBALS['APPLICATION']->AddHeadScript($currentPath);
			}
		}
	}
}
?>