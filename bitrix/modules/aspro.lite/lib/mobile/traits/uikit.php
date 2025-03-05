<?
namespace Aspro\Lite\Mobile\Traits;

use \Bitrix\Main\Config\Option;

use CLite as Solution;
use \Aspro\Lite\Functions\Extensions;
use \Aspro\Lite\Functions\ExtensionsMobile;

trait UIKit {
    public static function initExtensions()
    {
        if (\CSite::InDir(SITE_DIR.'info/more/elements/')) {
            Extensions::init(['tabs']);
            ExtensionsMobile::init(['accordion']);
        }
    }
}
?>