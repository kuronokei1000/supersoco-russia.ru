<?
namespace Aspro\Lite\Itemaction;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Main\SystemException,
	CLite as Solution,
	CLiteCache as SolutionCache;

Loc::loadMessages(__FILE__);

class Subscribe {
	protected static $siteId = '';
	protected static $userId = false;
	protected static $iblocks = [];
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

	public static function setUserId(int $userId) {
		self::$bModified = true;
		self::$userId = $userId;
	}

	public static function getOffersIDFromItems(&$arItems) {
		$arResult = [];
		if ($arItems) {
			$arElements = SolutionCache::CIBlockElement_GetList(
				[
					'CACHE' => [
						'GROUP' => ['ID'],
						'TIME' => 0,
					]
				],
				[
					'ID' => $arItems,
				],
				false, 
				false, 
				[
					'ID',
					'IBLOCK_ID',
					'PROPERTY_CML2_LINK',
				]
			);

			foreach ($arElements as $key => $arItem) {
				if ($arItem['PROPERTY_CML2_LINK_VALUE']) {
					$arResult[] = $key;
					unset($arItems[$key]);
					$arItems[] = $arItem['PROPERTY_CML2_LINK_VALUE'];
				}
			}
		}
		
		return $arResult;
	}

	public static function getItems() :array {
		static $result;

		if (self::$bModified) {
			$result = [];

			if(
				Loader::includeModule('catalog') &&
				class_exists('\Bitrix\Catalog\Product\SubscribeManager')
			) {
				if (!is_array($_SESSION['SUBSCRIBE_PRODUCT'])) {
					$_SESSION['SUBSCRIBE_PRODUCT'] = [];
				}
		
				if (!is_array($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'])) {
					$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'] = [];
				}

				$userId = self::getUserId();

				if (
					$userId || 
					$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID']
				) {
					$resultObject = \Bitrix\Catalog\SubscribeTable::getList(
						array(
							'select' => array(
								'ID',
								'ITEM_ID',
							),
							'filter' => [
								'USER_ID' => $userId,
								'=SITE_ID' => self::getSiteId(),
								array(
									'LOGIC' => 'OR',
									array('=DATE_TO' => false),
									array('>DATE_TO' => date($GLOBALS['DB']->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
								),
							],
						)
					);
					while ($arItem = $resultObject->fetch()) {
						$result[$arItem['ID']] = intval($arItem['ITEM_ID']);
					}

					if(
						!$userId &&
						$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID']
					){
						foreach ($result as $id => $key) {
							if (!$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'][$key]) {
								unset($result[$id]);
							}	
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
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_ITEM_ID'));
		}

		$arItem = self::getElement($id);
		if (!$arItem) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_ITEM'));
		}

		if (!Loader::includeModule('catalog')) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_MODULE_CATALOG'));
		}

		if (!class_exists('\Bitrix\Catalog\Product\SubscribeManager')) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_MANAGER_CLASS'));
		}

		if (!is_array($_SESSION['SUBSCRIBE_PRODUCT'])) {
			$_SESSION['SUBSCRIBE_PRODUCT'] = [];
		}

		if (!is_array($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'])) {
			$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'] = [];
		}
		
		$userId = self::getUserId();
		if (!$userId) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_NEED_AUTH'));
		}

		$subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
		$contactTypes = $subscribeManager->contactTypes;
		$contactTypeId = key($contactTypes);

		$userContact = trim($contactTypeId == \Bitrix\Catalog\SubscribeTable::CONTACT_TYPE_EMAIL ? $GLOBALS['USER']->getEmail() : '');
		if(!$userContact) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_CONTACT_EMAIL'));
		}

		$subscribeId = $subscribeManager->addSubscribe([
			'USER_CONTACT' => $userContact,
			'ITEM_ID' => $id,
			'SITE_ID' => self::getSiteId(),
			'CONTACT_TYPE' => $contactTypeId,
			'USER_ID' => $userId,
		]);

		self::$bModified = true;
	}

	public static function removeItem(int $id) {
		if ($id <= 0) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_ITEM_ID'));
		}

		$arItem = self::getElement($id);
		if (!$arItem) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_ITEM'));
		}

		if (!Loader::includeModule('catalog')) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_MODULE_CATALOG'));
		}

		if (!class_exists('\Bitrix\Catalog\Product\SubscribeManager')) {
			throw new SystemException(Loc::getMessage('ITEMACTION_SUBSCRIBE_ERROR_MANAGER_CLASS'));
		}

		if (!is_array($_SESSION['SUBSCRIBE_PRODUCT'])) {
			$_SESSION['SUBSCRIBE_PRODUCT'] = [];
		}

		if (!is_array($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'])) {
			$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'] = [];
		}

		$userId = self::getUserId();

		if ($userId) {
			$items = self::getItems();
			if ($subscribeId = array_search($id, $items)) {
				$subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
				$subscribeManager->deleteManySubscriptions([$subscribeId], $id);
			}
		} else {
			$resultObject = \Bitrix\Catalog\SubscribeTable::getList(
				array(
					'select' => array(
						'ID',
						'ITEM_ID',
					),
					'filter' => [
						'=SITE_ID' => self::getSiteId(),
						'ITEM_ID' => $id,
						'USER_CONTACT' => $_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'][$id],
						array(
							'LOGIC' => 'OR',
							array('=DATE_TO' => false),
							array('>DATE_TO' => date($GLOBALS['DB']->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
						),
					],
				)
			);
			if ($arItem = $resultObject->Fetch()) {
				\Bitrix\Catalog\SubscribeTable::delete($arItem['ID']);
				unset($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'][$id]);
			}
		}

		self::$bModified = true;
	}

	public static function getTitle() {
		return Loc::getMessage('ITEMACTION_SUBSCRIBE_TITLE');
	}

	protected static function getElement(int $id) :array {
		Loader::includeModule('iblock');

		$arItem = \CIBlockElement::GetByID($id)->Fetch();

		return $arItem ?: [];
	}
}
