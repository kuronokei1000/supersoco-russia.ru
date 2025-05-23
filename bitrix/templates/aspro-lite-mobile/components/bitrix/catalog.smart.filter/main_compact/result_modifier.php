<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arResult['ITEMS']) {
	$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";
	foreach ($arResult["ITEMS"] as $key => $arItem) {
		/*unset empty values*/
		if (
			(
				($arItem["DISPLAY_TYPE"] == "A" || isset($arItem["PRICE"]))
				&& ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
			)
			|| !$arItem["VALUES"]
		)
			unset($arResult["ITEMS"][$key]);
		/**/

		$arResult['ITEMS'][$key]['IS_PROP_INLINE'] = false;

		if ($arItem['PROPERTY_TYPE'] === 'L') {
			$iPropValuesCount = CIBlockPropertyEnum::GetList([], [
				'PROPERTY_ID' => $arItem['ID'],
				'IBLOCK_ID' => $arItem['IBLOCK_ID']
			])->SelectedRowsCount();

			if ($iPropValuesCount === 1) {
				if (is_array($arResult["ITEMS"][$key]["VALUES"]))
					sort($arResult["ITEMS"][$key]["VALUES"]);

				if ($arResult["ITEMS"][$key]["VALUES"])
					$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"] = $arItem["NAME"];

				$arResult['ITEMS'][$key]['IS_PROP_INLINE'] = true;
			}
		}

		if (isset($arItem['PRICE']) && $arItem['PRICE']) {
			if (
				(isset($arItem['VALUES']['MIN']['HTML_VALUE']) && $arItem['VALUES']['MIN']['HTML_VALUE'])
				|| (isset($arItem['VALUES']['MAX']['HTML_VALUE']) && $arItem['VALUES']['MAX']['HTML_VALUE'])
			) {
				$arResult['PRICE_SET'] = 'Y';
				break;
			}
		}

		$i = 0;

		if ($arItem['PROPERTY_TYPE'] == 'S' || $arItem['PROPERTY_TYPE'] == 'L' || $arItem['PROPERTY_TYPE'] == 'E') {
			foreach ($arItem['VALUES'] as $arValue) {
				if (isset($arValue['CHECKED']) && $arValue['CHECKED']) {
					$arResult["ITEMS"][$key]['PROPERTY_SET'] = 'Y';
					++$i;
				}
			}

			if ($i) {
				$arResult["ITEMS"][$key]['COUNT_SELECTED'] = $i;
			}
		}

		if ($arItem['PROPERTY_TYPE'] == 'N') {
			foreach ($arItem['VALUES'] as $arValue) {
				if (isset($arValue['HTML_VALUE']) && $arValue['HTML_VALUE']) {
					$arResult['ITEMS'][$key]['PROPERTY_SET'] = 'Y';
				}
			}
		}

		if (isset($arParams["HIDDEN_PROP"]) && in_array($arItem["CODE"], $arParams["HIDDEN_PROP"]))
			unset($arResult["ITEMS"][$key]);
	}
}

\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__);

$arResult["JS_FILTER_PARAMS"]["AJAX"] = $arParams["AJAX"] === "Y";
if ($arParams["AJAX"] === "Y" && !$arResult["JS_FILTER_PARAMS"]["SEF_DEL_FILTER_URL"]) {
	$arDelUrlParams = array('sort', 'order', 'control_ajax', 'ajax_get_filter', 'ajax_get', 'linerow', 'display');
	$arResult["JS_FILTER_PARAMS"]["SEF_DEL_FILTER_URL"] = CHTTP::urlDeleteParams($arResult["FORM_ACTION"], $arDelUrlParams, array("delete_system_params" => true));

	if (strpos($arResult["FORM_ACTION"], 'ajax_get_filter') !== false) {
		$arResult["FORM_ACTION"] = $arResult["JS_FILTER_PARAMS"]["SEF_DEL_FILTER_URL"];
	}

	if ($arResult["HIDDEN"]) {
		foreach ($arResult["HIDDEN"] as $key => $arItem) {
			if (in_array($arItem['CONTROL_ID'], $arDelUrlParams)) {
				unset($arResult["HIDDEN"][$key]);
			}
		}
	}
}

if (!$arResult['ITEMS']) {
	$arResult['EMPTY_ITEMS'] = true;
}

// sort
if ($arParams['SHOW_SORT']) {
	include 'sort.php';
}

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;
