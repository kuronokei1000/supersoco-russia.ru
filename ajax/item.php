<?
use Bitrix\Main\Web\Json,
	Bitrix\Main\SystemException,
	Bitrix\Main\Loader,
	TSolution\Itemaction\Basket,
	TSolution\Itemaction\Compare,
	TSolution\Itemaction\Favorite,
	TSolution\Itemaction\Subscribe;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$arResult = [
	'success' => true,
	'error' => '',
	'items' => [],
	'count' => 0,
];

// change item state
try {
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

	$action = $request->get('action');
	$state = $request->get('state');
	$type = $request->get('type');

	if (!check_bitrix_sessid()) {
		throw new SystemException('Invalid bitrix sessid');
	}

	// need for solution class and variables
	if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
		throw new SystemException('Error include solution constants');
	}

	if (!Loader::includeModule(VENDOR_MODULE_ID)) {
		throw new SystemException('Error include module '.VENDOR_MODULE_ID);
	}

	$bNeedItems = $action !== 'basket_clear';
	if ($bNeedItems) {
		if ($action === 'basket') {
			$arItems = [];
			if ($type === 'multiple') {
				foreach ((array)$request->get('items') as $arItem) {
					$arItems[] = [
						intval(is_array($arItem) ? $arItem['ID'] : $arItem),
						floatval(is_array($arItem) ? $arItem['QUANTITY'] : 1),
					];
				}
			} else {
				$id = intval($request->get('ID'));
				$quantity = floatval($request->get('quantity'));

				$arItems[] = [
					$id,
					$quantity,
				];
			}
		} else {
			if ($type === 'multiple') {
				$arItems = [];
				foreach ((array)$request->get('items') as $arItem) {
					$arItems[] = intval(is_array($arItem) ? $arItem['ID'] : $arItem);
				}
			} else {
				$id = intval($request->get('ID'));
				$arItems = [$id];
			}
		}

		if (
			!is_array($arItems) ||
			!$arItems
		) {
			throw new SystemException('Invalid items');
		}
	}

	switch ($action) {
		case 'compare':
			foreach ($arItems as $id) {
				if ($state) {
					Compare::addItem($id);
				} else {
					Compare::removeItem($id);
				}
			}

			$arResult['items'] = Compare::getItems();
			$arResult['count'] = count($arResult['items']);
			$arResult['title'] = Compare::getTitle();

			break;
		case 'favorite':
			foreach ($arItems as $id) {
				if ($state) {
					Favorite::addItem($id);
				} else {
					Favorite::removeItem($id);
				}
			}

			$arResult['items'] = Favorite::getItems();
			$arResult['count'] = count($arResult['items']);
			$arResult['title'] = Favorite::getTitle();

			break;
		case 'subscribe':	
			foreach ($arItems as $id) {
				if ($state) {
					Subscribe::addItem($id);
				} else {
					Subscribe::removeItem($id);
				}
			}

			$arResult['items'] = Subscribe::getItems();
			$arResult['count'] = count($arResult['items']);
			$arResult['title'] = Subscribe::getTitle();

			break;
		case 'basket':
		case 'basket_clear':
			if ($action === 'basket_clear') {
				Basket::clear();
			} else {
				foreach ($arItems as $arItem) {
					[$id, $quantity] = $arItem;

					if ($state) {
						$arPropsOptions = [];
	
						if (TSolution::isSaleMode()) {
							$arPropsOptions['bAddProps'] = $request->get('add_props') === 'Y';
							$arPropsOptions['bPartProps'] = $request->get('part_props') === 'Y';
							$arPropsOptions['propsList'] = $request->get('props') ? json_decode($request->get('props')) : [];
							$arPropsOptions['skuTreeProps'] = $request->get('basket_props') ? $request->get('basket_props') : '';
							$arPropsOptions['propsValues'] = $request->get('prop') ? $request->get('prop') : [];
						}
	
						Basket::addItem($id, $quantity, $arPropsOptions);
					} else {
						Basket::removeItem($id);
					}
				}
			}

			$arResult['items'] = Basket::getItems()['BASKET'];
			$arResult['count'] = count($arResult['items']);
			$arResult['title'] = Basket::getTitle();
			break;
		default: 
			throw new SystemException('Invalid action "'.htmlspecialcharsbx($action).'"');
	}
}
catch (SystemException $e) {
	$arResult['error'] = $e->getMessage();
	$arResult['success'] = false;
}

die(Json::encode($arResult));