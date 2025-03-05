<?
namespace Aspro\Lite\Agents;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

use CLite as Solution;

Loc::loadMessages(__FILE__);

class Common{
	public static function update($ID, $arFields = []){
		\CAgent::Update($ID, $arFields);
	}
}
