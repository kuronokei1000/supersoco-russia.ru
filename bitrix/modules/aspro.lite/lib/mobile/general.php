<?

namespace Aspro\Lite\Mobile;

use CLite as Solution;
use CLiteRegionality as SolutionRegionality;
use \Aspro\Lite\PWA;
use \Aspro\Functions\CAsproLite as SolutionFunctions;
use \Bitrix\Main\Config\Option;



class General
{
    static $solutionPath = '/bitrix/templates/'.Solution::templateName;
    use Traits\Js, Traits\Css, Traits\Extension, Traits\UIKit;

    public static function start()
    {
        if (!defined('ASPRO_USE_ONENDBUFFERCONTENT_HANDLER')) {
			define('ASPRO_USE_ONENDBUFFERCONTENT_HANDLER', 'Y');
		}

        global $APPLICATION, $arRegion, $arTheme;

        $APPLICATION->AddHeadString('<script>BX.message('.\CUtil::PhpToJSObject($GLOBALS['MESS'], false).')</script>', true);

        // get site options
        $arTheme = Solution::GetFrontParametrsValues(SITE_ID);

        // get current region from regionality module
        if ($arTheme['USE_REGIONALITY'] == 'Y') {
            $arRegion = SolutionRegionality::getCurrentRegion();
        }

        self::setJSOptions();

        self::addPageProperties();
        self::showMeta();

        self::addFavicon();

        // PWA manifest
        PWA::showMeta(SITE_ID);

        self::addLazyLoad();

        Solution::setCssVariables($arTheme);
        
        self::addCommonJs();
        self::addCommonStyles();

        self::addCustomJSCss();

        self::addExtensions();

        self::autoloadCss();
        self::autoloadJs();

        // info/more
        self::initExtensions();

        // need for solution class and variables
        include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php');
    }

    public static function addPageProperties()
    {
        global $APPLICATION, $arTheme;
        $APPLICATION->SetPageProperty("viewport", "initial-scale=1.0, width=device-width, viewport-fit=cover");
        $APPLICATION->SetPageProperty("HandheldFriendly", "true");
        $APPLICATION->SetPageProperty("apple-mobile-web-app-capable", "yes");
        $APPLICATION->SetPageProperty("apple-mobile-web-app-status-bar-style", "black");

        // change default logo color
        if (
            $arTheme['THEME_VIEW_COLOR'] === 'DARK' ||
            ($arTheme['THEME_VIEW_COLOR'] === 'DEFAULT' &&
                isset($_COOKIE['prefers-color-scheme']) &&
                $_COOKIE['prefers-color-scheme'] === 'dark'
            )
        ) {
            $APPLICATION->SetPageProperty('HEADER_LOGO', 'light');
            $APPLICATION->SetPageProperty('HEADER_FIXED_LOGO', 'light');
            $APPLICATION->SetPageProperty('HEADER_MOBILE_LOGO', 'light');
        }
    }

    public static function addLazyLoad()
    {
        global $APPLICATION, $arTheme;

        // global flag for OnEndBufferContentHandler
        $GLOBALS['_USE_LAZY_LOAD_LITE_'] = $arTheme['USE_LAZY_LOAD'] === 'Y';

        if ($arTheme['USE_LAZY_LOAD'] === 'Y' && !Solution::checkMask(Option::get(Solution::moduleID, 'LAZY_LOAD_EXCEPTIONS', ''))) {
            $APPLICATION->AddHeadString('<script>window.lazySizesConfig = window.lazySizesConfig || {};lazySizesConfig.loadMode = 1;lazySizesConfig.expand = 200;lazySizesConfig.expFactor = 1;lazySizesConfig.hFac = 0.1;window.lazySizesConfig.loadHidden = false;</script>');
            $APPLICATION->AddHeadString('<script src="' . SITE_TEMPLATE_PATH . '/vendor/js/lazysizes.min.js" data-skip-moving="true" defer=""></script>');
            $APPLICATION->AddHeadString('<script src="' . SITE_TEMPLATE_PATH . '/vendor/js/ls.unveilhooks.min.js" data-skip-moving="true" defer=""></script>');
        }
    }

    public static function addFavicon()
    {
        global $APPLICATION, $arTheme;

        if (strlen($arTheme['FAVICON_IMAGE'])) {
            $file_ext = pathinfo($arTheme['FAVICON_IMAGE'], PATHINFO_EXTENSION);
            $fav_ext = $file_ext ? $file_ext : 'ico';
            $fav_type = '';

            switch ($fav_ext) {
                case 'ico':
                    $fav_type = 'image/x-icon';
                    break;
                case 'svg':
                    $fav_type = 'image/svg+xml';
                    break;
                case 'png':
                    $fav_type = 'image/png';
                    break;
                case 'jpg':
                    $fav_type = 'image/jpeg';
                    break;
                case 'gif':
                    $fav_type = 'image/gif';
                    break;
                case 'bmp':
                    $fav_type = 'image/bmp';
                    break;
            }

            $APPLICATION->AddHeadString('<link rel="shortcut icon" href="' . $arTheme['FAVICON_IMAGE'] . '" type="' . $fav_type . '" />', true);
        }

        if (strlen($arTheme['APPLE_TOUCH_ICON_IMAGE'])) {
            $APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="180x180" href="' . $arTheme['APPLE_TOUCH_ICON_IMAGE'] . '" />', true);
        }
    }

    public static function showMeta()
    {
        global $APPLICATION;

        $APPLICATION->ShowMeta("viewport");
        $APPLICATION->ShowMeta("HandheldFriendly");
        $APPLICATION->ShowMeta("apple-mobile-web-app-capable");
        $APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");
        $APPLICATION->ShowHead();
    }
    
    public static function showPageTypeFromSolution($type = '')
    {
        global $APPLICATION;

        $file = $type.'_'.$GLOBALS['arTheme'][strtoupper($type)]['VALUE'].'.php';

        if ($type === 'header_mobile') {
            $blockOptions = array(
                'PARAM_NAME' => 'HEADER_TOGGLE_SEARCH',
                'BLOCK_TYPE' => 'SEARCH',
                'VISIBLE' => true,
                'WRAPPER' => 'header-search',
                'TYPE' => 'LINE',
            );
            SolutionFunctions::showHeaderBlock($blockOptions);
        }

        if ($type === 'page_title') {
            $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/block/page_title.css');
        }
        
        if ($type === 'footer') {
            $file = $type.'_'.$GLOBALS['arTheme']['FOOTER_TYPE']['VALUE'].'.php';
        }
        include_once($_SERVER['DOCUMENT_ROOT'].self::$solutionPath.'/page_blocks/'.$file);
    }
    
    public static function addCustomJSCss()
    {
        global $APPLICATION;
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom.js', true);
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/custom.css', true);
    }

    public static function getConditionClass()
    {
        global $APPLICATION, $arTheme;

        $arClasses = [];

		$arClasses[] = $APPLICATION->AddBufferContent(array('CLite', 'showPageClass'));
        $arClasses[] = 'mmenu_'.($arTheme['HEADER_MOBILE_MENU_OPEN'] == 1 ? 'leftside' : 'dropdown');
        $arClasses[] = 'mfixed_'.strtolower($arTheme['HEADER_MOBILE_FIXED']);
		$arClasses[] = 'mfixed_view_'.strtolower($arTheme['HEADER_MOBILE_SHOW']);
        // $arClasses[] = 'bottom-icons-panel_'.strtolower($arTheme['BOTTOM_ICONS_PANEL']);

        /* default|light|dark theme */
		$arClasses[] = 'theme-'.strtolower($arTheme['THEME_VIEW_COLOR']);

		return implode(' ', $arClasses);
    }
}
