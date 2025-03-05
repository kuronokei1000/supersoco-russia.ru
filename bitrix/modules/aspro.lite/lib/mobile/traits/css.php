<?
namespace Aspro\Lite\Mobile\Traits;

use \Bitrix\Main\Config\Option;

use CLite as Solution;

trait Css {
    public static function addCommonStyles()
    {
        global $APPLICATION;
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/block/mobile-header.min.css');
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/block/mobile-menu.min.css');
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/block/footer.min.css');

        self::addStylesFromSolutionTemplate();
        self::addCssByCondition();
    }
    
    public static function addStylesFromSolutionTemplate()
    {
        global $APPLICATION, $arTheme;
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/colored.min.css', true);
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/buttons.min.css');
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/dropdown-select.min.css');
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/form.min.css');
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/social-icons.min.css');
        
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/blocks/popup.min.css');
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/blocks/dark-light-theme.min.css');
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/blocks/line-block.min.css');
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/blocks/flexbox.min.css', true);
        $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/blocks/sticky.min.css');

        $APPLICATION->AddHeadString('<link href="'.$APPLICATION->oAsset->getFullAssetPath(self::$solutionPath.'/css/print.css').'" data-template-style="true" rel="stylesheet" media="print">');

        if ($arTheme['BOTTOM_ICONS_PANEL'] === 'Y') {
            $APPLICATION->SetAdditionalCSS(self::$solutionPath.'/css/bottom-icons-panel.min.css');
        }
    }
    
    public static function addCssByCondition()
    {
        global $APPLICATION;
        if (Solution::IsMainPage()) {
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/conditional/index-page.css');
		}
    }

    public static function autoloadCss()
    {
		$arBlocks = glob($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/css/autoload/*.css');

		foreach($arBlocks as $blockPath) {
			if (strpos($blockPath, '.min.css') === false && strpos($blockPath, '__') === false) {
				$currentPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $blockPath);
                $minFile = str_replace('.css', '.min.css', $currentPath);
                if (file_exists($_SERVER['DOCUMENT_ROOT'].$minFile)) {
                    $currentPath = $minFile;
                }
				$GLOBALS['APPLICATION']->SetAdditionalCSS($currentPath);
			}
		}
	}
}
?>