<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!empty($arResult['ITEMS'])) {
	$arOffers = [];

	if($GLOBALS[$arParams['FILTER_NAME']] && $arFilter = $GLOBALS[$arParams['FILTER_NAME']]['ITEMS']) {

		foreach($arFilter as $items) {
			$arItems[] = $items['OFFER_ID'] ?? $items['ID'];
		}
		
		$arFilterSKU = array_flip(array_column($GLOBALS[$arParams['FILTER_NAME']]['ITEMS'], 'OFFER_ID'));
		
		if ($arFilterSKU) {
			foreach ($arResult['ITEMS'] as $key => $arItem) {
				if ($arItem['OFFERS']) {
					array_push($arOffers, ...$arItem['OFFERS']);
				}
				$arNewItemsList[$arItem['ID']] = $arItem;
			}
			
			$arOffers = array_filter($arOffers, fn($offer) => isset($arFilterSKU[$offer['ID']]));
		

			$arOffersTmp = [];
			foreach($arOffers as $key => $arOffer) {
				if(!$arOffer['PREVIEW_PICTURE']) {
					$arOffer['PREVIEW_PICTURE'] = $arNewItemsList[$arOffer["LINK_ELEMENT_ID"]]['PREVIEW_PICTURE'];
				}
				
				$arOffersTmp[$arOffer['ID']] = $arOffer;
			}
			$arOffers = $arOffersTmp;

			$arNewItemsList += $arOffers;
			
			$arResult['ITEMS'] = [];
			foreach($arItems as $items) {
				$arResult['ITEMS'][$items] = $arNewItemsList[$items];
			}
			
			unset($arOffers, $arOffersTmp);
		}
	}
}
?>