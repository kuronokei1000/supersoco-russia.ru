<?
/** @global CMain $APPLICATION */
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$application = Bitrix\Main\Application::getInstance();
$request = $application->getContext()->getRequest();
$post = $request->getPostList();

if (isset($request['lid']) && !empty($request['lid'])) {
	if (!is_string($request['lid']))
		die();
	if (preg_match('/^[a-z0-9_]{2}$/i', $request['lid']))
		define('SITE_ID', $request['lid']);
}

if (!Loader::includeModule('catalog'))
	return;

Loc::loadMessages(__FILE__);

if ($_SERVER["REQUEST_METHOD"] == "POST" && strlen($post["action"]) > 0 && check_bitrix_sessid()) {
	$APPLICATION->RestartBuffer();

	switch ($post["action"]) {
		case "catalogSetAdd2Basket":
			if (is_array($post["set_ids"])) {
				foreach ($post["set_ids"] as $itemID) {
					if (!is_string($itemID))
						continue;
					$itemID = (int)$itemID;
					if ($itemID <= 0)
						continue;

					$product_properties = true;
					if (!empty($post["setOffersCartProps"])) {
						$product_properties = CIBlockPriceTools::GetOfferProperties(
							$itemID,
							$post["iblockId"],
							$post["setOffersCartProps"]
						);
					}
					$ratio = 1;
					if ($post["itemsRatio"][$itemID])
						$ratio = $post["itemsRatio"][$itemID];

					Add2BasketByProductID($itemID, $ratio, array("LID" => $post["lid"]), $product_properties);
				}
			}
			break;
	}

	die();
}
