<?
namespace Aspro\Lite\Itemaction;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Main\SystemException,
	CLite as Solution,
	CLiteCache as SolutionCache;

Loc::loadMessages(__FILE__);

class Compare {
	protected static $iblocks = [];
	protected static $siteId = '';
	protected static $bModified = true;

	public static function getSiteId() :string {
		return self::$siteId ?: SITE_ID;
	}

	public static function setSiteId(string $siteId) {
		self::$bModified = true;
		self::$siteId = trim($siteId);
	}

	public static function getIblocks() :array {
		if (!static::$iblocks) {
			$catalogIblockId = Option::get(
				Solution::moduleID,
				'CATALOG_IBLOCK_ID',
				SolutionCache::$arIBlocks[self::getSiteId()]['aspro_'.Solution::solutionName.'_catalog']['aspro_'.Solution::solutionName.'_catalog'][0],
				self::getSiteId()
			);
			if ($catalogIblockId) {
				static::$iblocks[] = $catalogIblockId;
			}
		}

		return static::$iblocks;
	}

	public static function setIblocks(array $iblocks) {
		self::$bModified = true;
		self::$iblocks = [];

		foreach ($iblocks as $iblockId) {
			$iblockId = intval($iblockId);
			if ($iblockId > 0) {
				self::$iblocks[] = $iblockId;
			}
		}
	}

	public static function getItems() :array {
		static $result;

		if (self::$bModified) {
			$result = [];

			if (
				$_SESSION['CATALOG_COMPARE_LIST'] &&
				is_array($_SESSION['CATALOG_COMPARE_LIST'])
			) {
				foreach (self::getIblocks() as $iblockId) {
					if (
						is_array($_SESSION['CATALOG_COMPARE_LIST'][$iblockId]) &&
						is_array($_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS']) &&
						$_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS']
					) {
						foreach (array_keys($_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS']) as $itemId) {
							$result[$itemId] = $itemId;
						}
					}
				}
			}

			self::$bModified = false;
		}

		return $result;
	}

	public static function addItem(int $id) {
		if ($id <= 0) {
			throw new SystemException(Loc::getMessage('ITEMACTION_COMPARE_ERROR_ITEM_ID'));
		}

		$arItem = self::getElement($id);
		if (!$arItem) {
			throw new SystemException(Loc::getMessage('ITEMACTION_COMPARE_ERROR_ITEM'));
		}

		$iblockId = $arItem['IBLOCK_ID'];
		self::modifyItem($id, $iblockId, $arItem);

		if (!is_array($_SESSION['CATALOG_COMPARE_LIST'])) {
			$_SESSION['CATALOG_COMPARE_LIST'] = [];
		}

		if (!is_array($_SESSION['CATALOG_COMPARE_LIST'][$iblockId])) {
			$_SESSION['CATALOG_COMPARE_LIST'][$iblockId] = [];
		}

		if (!is_array($_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS'])) {
			$_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS'] = [];
		}

		$_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS'][$id] = $arItem;

		self::$bModified = true;
	}

	public static function removeItem(int $id) {
		if ($id <= 0) {
			throw new SystemException(Loc::getMessage('ITEMACTION_COMPARE_ERROR_ITEM_ID'));
		}

		$arItem = self::getElement($id);
		if (!$arItem) {
			throw new SystemException(Loc::getMessage('ITEMACTION_COMPARE_ERROR_ITEM'));
		}

		$iblockId = $arItem['IBLOCK_ID'];
		self::modifyItem($id, $iblockId, $arItem);

		if (!is_array($_SESSION['CATALOG_COMPARE_LIST'])) {
			$_SESSION['CATALOG_COMPARE_LIST'] = [];
		}

		if (!is_array($_SESSION['CATALOG_COMPARE_LIST'][$iblockId])) {
			$_SESSION['CATALOG_COMPARE_LIST'][$iblockId] = [
				'ITEMS' => [],
			];
		}

		if (!is_array($_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS'])) {
			$_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS'] = [];
		}

		unset($_SESSION['CATALOG_COMPARE_LIST'][$iblockId]['ITEMS'][$id]);

		self::$bModified = true;
	}

	public static function getTitle() {
		return Loc::getMessage('ITEMACTION_COMPARE_TITLE');
	}

	protected static function getElement(int $id) :array {
		Loader::includeModule('iblock');

		$arItem = \CIBlockElement::GetByID($id)->Fetch();

		return $arItem ?: [];
	}

	protected static function modifyItem(int &$id, int &$iblockId, array &$arItem) {
		if (Solution::isSaleMode()) {
			Loader::includeModule('catalog');

			$mxResult = \CCatalogSku::GetProductInfo($id);
			if (is_array($mxResult)) {
				$iblockId = $mxResult['IBLOCK_ID'];
			}
		} else {
			if ($id && $iblockId) {
				$arElement = SolutionCache::CIBlockElement_GetList(
					[
						'CACHE' => [
							'MULTI' => 'N',
							'TAG' => SolutionCache::GetIBlockCacheTag($iblockId),
						]
					],
					[
						'IBLOCK_ID' => $iblockId,
						'ID' => $id,
					],
					false, 
					false, 
					[
						'ID',
						'IBLOCK_ID',
						'PROPERTY_CML2_LINK',
					]
				);
				if ($arElement) {
					if (isset($arElement['PROPERTY_CML2_LINK_VALUE'])) {
						$id = $arElement['PROPERTY_CML2_LINK_VALUE'];
						$arItem = self::getElement($id);
						$iblockId = $arItem['IBLOCK_ID'];
					}
				}
			}
		}
	}
}
