<?
namespace Aspro\Lite\Functions;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Type\Collection;
use Bitrix\Main\Config\Option;
use Bitrix\Iblock;

use CLite as Solution,
	\Aspro\Functions\CAsproLite as SolutionFunctions;

Loc::loadMessages(__FILE__);

if(!class_exists('CSKUTemplate'))
{
	/**
	 * Class for show sku items
	 */
	class CSKUTemplate{
		/**
		 * @var string MODULE_ID solution
		 */
		const MODULE_ID = Solution::moduleID;

		public $props = [];
		public $items = [];
		public $linkedProp = [];
		public $linkCodeProp = '';
		
		/**
		 * Show html sku tree props
		 * @param array $arProps 
		 * @param array $arOptions
		 * [
		 *     HIDE_WITH_ONE_VALUE => true|false
		 * ] 
		 */
		public static function showSkuPropsHtml(array $arProps = [], array $arOptions = [])
		{
			$arDefaultOptions = [
				'HIDE_WITH_ONE_VALUE' => true
			];
			$arMergedOptions = array_merge($arDefaultOptions, $arOptions);

			$bShowProps = false;

			if ($arMergedOptions['HIDE_WITH_ONE_VALUE']) {
				foreach ($arProps as $code => $arProp) {
					if (count($arProp['VALUES']) > 1) {
						$bShowProps = true;
					}
				}
			} else {
				$bShowProps = true;
			}

			if ($bShowProps) {
				foreach ($arProps as $code => $arProp) {
					SolutionFunctions::showBlockHtml([
						'TYPE' => 'SKU',
						'FILE' => 'sku/properties_in_'.$arProp['SHOW_MODE'].'.php',
						'PARAMS' => $arProp
					]);
				}
			}
		}
	}
}?>