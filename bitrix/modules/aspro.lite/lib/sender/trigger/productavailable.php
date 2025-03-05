<?
namespace Aspro\Lite\Sender\Trigger;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
	Bitrix\Iblock\ElementTable;

Loader::includeModule('sender');
Loc::loadMessages(__FILE__);

class ProductAvailable extends \Bitrix\Sender\Trigger{
	public static $arCatalogs = [];
	public static $arOptions = [];

	public static function onTriggerList($data) {
		$data['TRIGGER'] = '\Aspro\Lite\Sender\Trigger\ProductAvailable';

		return $data;
    }

    public static function getPersonalizeList() {
		return [
			[
				'CODE' => 'PRODUCT_ID',
				'NAME' => Loc::getMessage('ASPRO_SENDER_TRIGGER_PRODUCTAVAILABLE_TAG_PRODUCT_ID'),
			],
			[
				'CODE' => 'PRODUCT_NAME',
				'NAME' => Loc::getMessage('ASPRO_SENDER_TRIGGER_PRODUCTAVAILABLE_TAG_PRODUCT_NAME'),
			],
		];
    }

	public function getName() {
		return Loc::getMessage('ASPRO_SENDER_TRIGGER_PRODUCTAVAILABLE_TITLE');
	}

	public function getCode() {
		return 'aspro_lite_productavailable';
	}

	public function getEventModuleId() {
		return 'catalog';
	}

	public function getEventType() {
		return '\Bitrix\Catalog\Product::OnUpdate';
	}

	public static function canBeTarget() {
		return false;
	}

	public function filter() {
		$event = $this->getParam('EVENT');
		$entity = $event['object'];
		$arFields = $event['fields'];
		$productId = $event['id']['ID'];
		$productId = $productId > 0 ? $productId : 0;

		if (
			$productId &&
			isset($arFields['QUANTITY']) &&
			$arFields['QUANTITY'] > 0 &&
			$entity->isChanged('QUANTITY')
		) {
			if (!$entity->isFilled('QUANTITY')) {
				$entity->fill('QUANTITY');
			}

			if (!$entity->remindActual('QUANTITY')) {
				if (Loader::includeModule('iblock')) {
					$arOptions = $this->getOptions();
	
					if ($arProduct = self::getProduct($productId)) {
						$arIBlocksIds = (array)$arOptions['IBLOCK_ID']['VALUE'];
						if (!$arIBlocksIds) {
							$arCatalogs = $this->getCatalogs();
							$arIBlocksIds = array_column(array_merge([], ...(array_column($arCatalogs, 'ITEMS'))), 'IBLOCK_ID');
						}
	
						return in_array($arProduct['IBLOCK_ID'], $arIBlocksIds);
					}
				}
			}
		}

		return false;
	}

	protected static function getProduct($productId) {
		static $arProducts = [];

		if ($productId > 0) {
			if (!isset($arProducts[$productId])) {
				$arProducts[$productId] = (array)ElementTable::getList(
					[
						'filter' => [
							'ID' => $productId,
						],
						'limit' => 1,
						'select' => [
							'ID',
							'NAME',
							'IBLOCK_ID',
						],
					]
				)->fetch();
			}
		}

		return $arProducts[$productId];
	}

    public function getForm() {
		$arCatalogs = $this->getCatalogs();
		$arOptions = $this->getOptions();

		$html = '
		<div style="margin-top: -37px;">
			<div>
				<label for="'.$arOptions['IBLOCK_ID']['ID'].'" >'.Loc::getMessage('ASPRO_SENDER_TRIGGER_PRODUCTAVAILABLE_OPTION_CATALOG').':</label>
			</div>
			<div><select id="'.$arOptions['IBLOCK_ID']['ID'].'" name="'.$arOptions['IBLOCK_ID']['NAME'].'[]" multiple="multiple" style="width: 100%;">';
				foreach ($arCatalogs as $arCatalogGroup) {
					$html .= '<optgroup label="'.$arCatalogGroup['NAME'].'">';

					foreach ($arCatalogGroup['ITEMS'] as $arCatalog) {
						$selected = in_array($arCatalog['IBLOCK_ID'], $arOptions['IBLOCK_ID']['VALUE']) ? ' selected="selected"' : '';
						$html .= '<option value="'.$arCatalog['ID'].'"'.$selected.'>['.$arCatalog['IBLOCK_ID'].'] '.$arCatalog['NAME'].'</option>';
					}
					
					$html .= '</optgroup>';
				}
			$html .= '</select></div>
		</div>';

		return $html;
	}

	protected function getOptions() {
		if (!self::$arOptions) {
			$arOptions = [
				'IBLOCK_ID' => [
					'ID' => 'aspro_lite_product_amount_'.$this->getFieldName('IBLOCK_ID'),
					'NAME' => $this->getFieldName('IBLOCK_ID'),
					'VALUE' => (array)$this->getFieldValue('IBLOCK_ID'),
				],
			];

			if ($this->getFields()) {
				self::$arOptions = $arOptions;
			}
			else {
				return $arOptions;
			}
		}
		
		return self::$arOptions;
	}

    protected function getCatalogs() {
		if (!self::$arCatalogs) {
			self::$arCatalogs = [];

			if (Loader::includeModule('iblock')) {
				$arIBlockTypes = [];

				$dbRes = \CIBlockType::GetList(
					['sort' => 'asc'],
				);
				while ($arIBlockType = $dbRes->Fetch()) {
					if ($arIBlockType = \CIBlockType::GetByIDLang($arIBlockType['ID'], LANG)) {
						$arIBlockTypes[$arIBlockType['IBLOCK_TYPE_ID']] = $arIBlockType;				
					}
				}

				$dbRes = \CCatalog::GetList(
					['sort' => 'asc'],
				);
				while ($arCatalog = $dbRes->Fetch()) {
					if (!isset(self::$arCatalogs[$arCatalog['IBLOCK_TYPE_ID']])) {
						self::$arCatalogs[$arCatalog['IBLOCK_TYPE_ID']] = [
							'NAME' => $arIBlockTypes[$arCatalog['IBLOCK_TYPE_ID']]['NAME'],
							'ITEMS' => [],
						];
					}

					self::$arCatalogs[$arCatalog['IBLOCK_TYPE_ID']]['ITEMS'][$arCatalog['ID']] = $arCatalog;
				}
			}
		}

		return self::$arCatalogs;
	}

	public function getRecipient() {
		$result = [];

		$event = $this->getParam('EVENT');
		$productId = $event['id']['ID'];
		$productId = $productId > 0 ? $productId : 0;

		if ($productId) {
			if (Loader::includeModule('catalog')) {
				$dbRes = \Bitrix\Catalog\SubscribeTable::getList(
					[
						'filter' => [
							'ITEM_ID' => $productId,
							'SITE_ID' => $this->getSiteId(),
							[
								'LOGIC' => 'OR',
								['=DATE_TO' => false],
								['>DATE_TO' => date($GLOBALS['DB']->dateFormatToPHP(\CLang::getDateFormat('FULL')), time())],
							],
						],
					]
				);
				while($arSubscribe = $dbRes->fetch()) {
					$result[$arSubscribe['USER_ID']] = [
						'USER_ID' => $arSubscribe['USER_ID'],
						'EMAIL' => trim($arSubscribe['USER_CONTACT']),
						'NAME' => '',
					];
				}

				$arUserIds = array_keys($result);
				if ($arUserIds) {
					$dbRes = \Bitrix\Main\UserTable::getList(
						[
							'filter' => [
								'ID' => $arUserIds,
							],
							'select' => [
								'ID',
								'NAME',
								'LAST_NAME',
								'EMAIL',
							],
						]
					);
					while ($arUser = $dbRes->fetch()) {
						$result[$arUser['ID']]['NAME'] = trim($arUser['NAME'] .' '. $arUser['LAST_NAME']);
						if (!strlen($result[$arUser['ID']]['EMAIL'])) {
							$result[$arUser['ID']]['EMAIL'] = $arUser['EMAIL'];
						}
					}

					$result = array_values($result);
				}
			}
		}

		return $result;
	}

	public function getPersonalizeFields() {
		$event = $this->getParam('EVENT');
		$productId = $event['id']['ID'];
		$productId = $productId > 0 ? $productId : 0;

		if ($productId) {
			if (Loader::includeModule('iblock')) {
				$arProduct = self::getProduct($productId);
			}
		}
        
		return [
			'PRODUCT_ID' => $productId,
			'PRODUCT_NAME' => $arProduct ? $arProduct['NAME'] : '',
		];
	}
}
