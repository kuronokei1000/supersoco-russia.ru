<?
namespace Aspro\Lite\Itemaction;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Main\SystemException,
	CLite as Solution,
	CLiteCache as SolutionCache;

Loc::loadMessages(__FILE__);

class Basket {
	protected static $iblocks = [];
	protected static $siteId = '';
	protected static $userId = '';
	protected static $bModified = true;

	public static function getSiteId() :string {
		return self::$siteId ?: SITE_ID;
	}

	public static function setSiteId(string $siteId) {
		self::$bModified = true;
		self::$siteId = trim($siteId);
	}

	public static function getUserId() :int {
		return self::$userId ?: $GLOBALS['USER']->GetID() ?: 0;
	}

	public static function getFUserId() :int {
		if (!Solution::isSaleMode()) {
			return 0;
		}

		$userId = self::getUserId();
		
		return $userId ? \Bitrix\Sale\Fuser::getIdByUserId($userId) : \Bitrix\Sale\Fuser::getId();
	}

	public static function setUserId(int $userId) {
		self::$bModified = true;
		self::$userId = $userId;
	}

	public static function getItems() :array {
		static $result;

		if (self::$bModified) {
			$result = [
				'BASKET' => [],
				'DELAY' => [],
				'NOT_AVAILABLE' => [],
			];

			if (Solution::isSaleMode()) {
				if(Loader::includeModule('sale')) {
					$basket = \Bitrix\Sale\Basket::loadItemsForFUser(self::getFUserId(), self::getSiteId());
					foreach ($basket as $basketItem) {
						$type = $basketItem->getField('TYPE');
						// if ($type == 1 || $type == 2) {
						// 	continue;
						// }
	
						$productId = $basketItem->getProductId();
	
						if ($basketItem->canBuy()) {
							if ($basketItem->isDelay()) {
								$result['DELAY'][$productId] += $basketItem->getQuantity();
							} else {
								$result['BASKET'][$productId] += $basketItem->getQuantity();
							}
						} else {
							$result['NOT_AVAILABLE'][$productId] += $basketItem->getQuantity();
						}
					}
				}
			} else {
				$siteId = self::getSiteId();
				$userId = self::getUserId();

				if (!is_array($_SESSION[$siteId])) {
					$_SESSION[$siteId] = [];
				}

				if (!is_array($_SESSION[$siteId][$userId])) {
					$_SESSION[$siteId][$userId] = [];
				}

				if (!is_array($_SESSION[$siteId][$userId]['BASKET_ITEMS'])) {
					$_SESSION[$siteId][$userId]['BASKET_ITEMS'] = [];
				}

				foreach ($_SESSION[$siteId][$userId]['BASKET_ITEMS'] as $id => $arItem) {
					$result['BASKET'][$id] = $arItem['QUANTITY'];
				}
			}

			self::$bModified = false;
		}

		return $result;
	}

	public static function addItem(int $id, float $quantity, $arPropsOptions = []) {
		if ($id <= 0) {
			throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_ERROR_ITEM_ID'));
		}

		$arItem = self::getElement($id);
		if (!$arItem) {
			throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_ERROR_ITEM'));
		}

		$iblockId = $arItem['IBLOCK_ID'];

		if (Solution::isSaleMode()) {
			if (!Loader::includeModule('sale')) {
				throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_ERROR_MODULE_SALE'));
			}

			[
				'bAddProps' => $bAddProps, 
				'bPartProps' => $bPartProps,
				'propsList' => $propsList, 
				'skuTreeProps' => $skuTreeProps,
				'propsValues' => $propsValues,
			] = $arPropsOptions;

			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(self::getFUserId(), self::getSiteId());

			if ($basketItem = $basket->getExistsItem('catalog', $id)) {
				$basketItem->setField('QUANTITY', $quantity);
				$basketItem->setField('DELAY', 'N');
			} else {
				$providerClass = 'CCatalogProductProvider';
				if (
					Loader::includeModule('catalog') &&
					class_exists('\Bitrix\Catalog\Product\Basket') && 
					method_exists('\Bitrix\Catalog\Product\Basket', 'getDefaultProviderName')
				) {
					$providerClass = \Bitrix\Catalog\Product\Basket::getDefaultProviderName();
				}

				$arFields = [
					'QUANTITY' => $quantity,
					'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
					'LID' => self::getSiteId(),
					'PRODUCT_PROVIDER_CLASS' => $providerClass,
				];

				if ($bAddProps) {
					$properties = [];

					$mxResult = \CCatalogSku::GetProductInfo($id);
					if (is_array($mxResult)) {
						if (
							$propsList || 
							strlen($skuTreeProps)
						) {
							$properties = \CIBlockPriceTools::GetOfferProperties(
								$id,
								$mxResult['IBLOCK_ID'],
								$propsList,
								$skuTreeProps
							);
						}
					} else {
						if ($propsList) {
							$properties = \CIBlockPriceTools::CheckProductProperties(
								$iblockId,
								$id,
								$propsList,
								$propsValues,
								$bPartProps
							);
	
							if (!$properties) {
								throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR'));
							}
						}
	
						if (!is_array($properties)) {
							throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR'));
						}
					}
				}

				$basketItem = $basket->createItem('catalog', $id);
				$basketItem->setFields($arFields);

				if ($bAddProps) {
					$basketItemPropertyCollection = $basketItem->getPropertyCollection();

					foreach ($properties as $arProperty) {
						$basketItemPropertyCollection->redefine($arProperty);
					}

					$basketItemPropertyCollection->save();
				}
			}

			$r = $basket->save();
			if (!$r->isSuccess()) {
				throw new SystemException(implode('. ', $r->getErrorMessages()));
			}
		} else {
			$siteId = self::getSiteId();
			$userId = self::getUserId();

			if (!is_array($_SESSION[$siteId])) {
				$_SESSION[$siteId] = [];
			}

			if (!is_array($_SESSION[$siteId][$userId])) {
				$_SESSION[$siteId][$userId] = [];
			}

			if (!is_array($_SESSION[$siteId][$userId]['BASKET_ITEMS'])) {
				$_SESSION[$siteId][$userId]['BASKET_ITEMS'] = [];
			}

			$_SESSION[$siteId][$userId]['BASKET_ITEMS'][$id] = array_merge(
				(array)$_SESSION[$siteId][$userId]['BASKET_ITEMS'][$id],
				//$arItem,
				[
					'QUANTITY' => $quantity,
				]
			);
		}
		
		self::$bModified = true;
	}

	public static function removeItem(int $id) {
		if ($id <= 0) {
			throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_ERROR_ITEM_ID'));
		}

		$arItem = self::getElement($id);
		if (!$arItem) {
			throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_ERROR_ITEM'));
		}

		$iblockId = $arItem['IBLOCK_ID'];

		if (Solution::isSaleMode()) {
			if (!Loader::includeModule('sale')) {
				throw new SystemException(Loc::getMessage('ITEMACTION_BASKET_ERROR_MODULE_SALE'));
			}

			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(self::getFUserId(), self::getSiteId());
			
			if ($basketItem = $basket->getExistsItem('catalog', $id)) {
				$basketItem->delete();
			} else {
				$dbBasketItems = \CSaleBasket::GetList(
					array("NAME" => "ASC", "ID" => "ASC"),
					array("PRODUCT_ID" => $id, "FUSER_ID" => \CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
					false, false, array("ID", "DELAY")
				)->Fetch();
				if(!empty($dbBasketItems)){
					\CSaleBasket::Delete($dbBasketItems["ID"]);
				}
			}

			$r = $basket->save();
			if (!$r->isSuccess()) {
				throw new SystemException(implode('. ', $r->getErrorMessages()));
			}
		} else {
			$siteId = self::getSiteId();
			$userId = self::getUserId();

			if (!is_array($_SESSION[$siteId])) {
				$_SESSION[$siteId] = [];
			}

			if (!is_array($_SESSION[$siteId][$userId])) {
				$_SESSION[$siteId][$userId] = [];
			}

			if (!is_array($_SESSION[$siteId][$userId]['BASKET_ITEMS'])) {
				$_SESSION[$siteId][$userId]['BASKET_ITEMS'] = [];
			}

			unset($_SESSION[$siteId][$userId]['BASKET_ITEMS'][$id]);
		}
	
		self::$bModified = true;
	}

	public static function clear() {
		$arItems = self::getItems()['BASKET'];
		foreach ($arItems as $id => $quantity) {
			self::removeItem($id);
		}	
	}

	public static function getTitle() {
		$items = self::getItems();

		if ($items['BASKET']) {
			return '';
			
			if (Solution::isSaleMode()) {

			} else {

			}

			return 
				Loc::getMessage(
					'ITEMACTION_BASKET_TITLE',
					[
						'#COUNT#' => count($items['BASKET']),
					]
				);
		} else {
			return Loc::getMessage('ITEMACTION_BASKET_EMPTY_TITLE');
		}
	}

	protected static function getElement(int $id) :array {
		Loader::includeModule('iblock');

		$arItem = \CIBlockElement::GetByID($id)->Fetch();

		return $arItem ?: [];
	}
}
