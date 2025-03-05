<?php

namespace Aspro\Lite\Scheme;

use \Aspro\Functions\CAsproLite as SolutionFunctions,
	\Aspro\Lite\Product\Common as SolutionProduct,
	\Aspro\Lite\Product\Quantity as SolutionQuantity;

class Offers {
	protected $skuProps = [];
	protected $item = [];
	protected $offers = [];
	protected $discount = '';

	protected $totalOffers = 0;
	protected $lowPrice = 0;
	protected $highPrice = 0;
	protected $currency = '';

	public function __construct(array $arOptions = [])
	{
		$arDefaultConfig = [
			'ITEM' => [],
			'DISCOUNT' => [],
		];
		$arConfig = array_merge($arDefaultConfig, $arOptions);
		$this->item = $arConfig['ITEM'];
		$this->discount = $arConfig['DISCOUNT'] ? date('Y-m-d', MakeTimeStamp($this->discount)) : '';

		$this->skuProps = $this->item['SKU']['PROPS'];
		$this->offers = $this->item['SKU']['OFFERS'];

		SolutionProduct::checkCatalogModule();
	}
	
	
	public function show(): void
	{
		if (!$this->offers) return;

		$this->prepareOptions();

		$schema = '<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" style="display:none;">
						<meta itemprop="offerCount" content="'.$this->totalOffers.'" />
						<meta itemprop="lowPrice" content="'.$this->lowPrice.'" />
						<meta itemprop="highPrice" content="'.$this->highPrice.'" />
						<meta itemprop="priceCurrency" content="'.$this->currency.'" />
						'.$this->getOffersListScheme().'
				   </span>';

		echo $schema;
	}

	protected function getOffersListScheme(): string
	{
		$schema = '';

		$arSkuPropsKeys = array_keys($this->skuProps);
		foreach ($this->offers as $arOffer) {
			$price = $this->getOfferPrice($arOffer);
			$priceCurrency = $this->getOfferCurrency($arOffer);
			$availability = $this->getOfferAvailability($arOffer);
			$arCurrentOfferProps = [];
			
			foreach ($arOffer['TREE'] as $propID => $propValue) {
				if (!$propValue) continue;

				$id = str_replace('PROP_', '', $propID);
				$skuKeyIndex = array_search($id, array_column($this->skuProps, 'ID'));
				$needleSkuPropValues = $this->skuProps[$arSkuPropsKeys[$skuKeyIndex]]['VALUES'];
				if ($needleSkuPropValues) {
					$currentOfferPropValueIndex = array_search($propValue, array_column($needleSkuPropValues, 'ID'));
					$arCurrentOfferProps[] = $this->skuProps[$arSkuPropsKeys[$skuKeyIndex]]['NAME'].':'.$needleSkuPropValues[$currentOfferPropValueIndex]['NAME'];
				}
			}

			$currentOfferListProps = implode('/', $arCurrentOfferProps);
			$schema .= '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							<meta itemprop="sku" content="'.$currentOfferListProps.'" />
							<a href="'.$arOffer['DETAIL_PAGE_URL'].'" itemprop="url"></a>
							<meta itemprop="price" content="'.$price.'" />
							<meta itemprop="priceCurrency" content="'.$priceCurrency.'" />
							<link itemprop="availability" href="'.$availability.'" />';

			if ($this->discount) {
				$schema .= '<meta itemprop="priceValidUntil" content="'.$this->discount.'" />';
			}
			
			$schema .= '</span>';
		}
		return $schema;
	}

	protected function prepareOptions(): void
	{
		$this->totalOffers = count((array)$this->offers);
		$this->setPrices();
	}

	protected function setPrices(): void
	{
		$this->lowPrice = $this->item['MIN_PRICE']['DISCOUNT_VALUE'] ?: $this->item['MIN_PRICE']['VALUE'];
		$this->highPrice = $this->item['MAX_PRICE']['DISCOUNT_VALUE'] ?: $this->item['MAX_PRICE']['VALUE'];
		$this->currency = $this->item['MIN_PRICE']['CURRENCY'] ?? '';
	}

	protected function getOfferPrice(array $arOffer = []): int
	{
		$price = 0;
		if (SolutionProduct::$catalogInclude) {
			$price = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'] ?: $arOffer['MIN_PRICE']['VALUE'] ?? 0;
		} else {
			$price = $arOffer['DISPLAY_PROPERTIES']["FILTER_PRICE"]["VALUE"] ?? 0;
		}
		return (int)$price;
	}
	protected function getOfferCurrency(array $arOffer = []): string
	{
		$priceCurrency = '';
		if (SolutionProduct::$catalogInclude) {
			$priceCurrency = $arOffer['MIN_PRICE']['CURRENCY'] ?? '';
		} else {
			$priceCurrency = $arOffer['DISPLAY_PROPERTIES']['PRICE_CURRENCY']['VALUE_XML_ID'] ?? '';
		}

		return $priceCurrency;
	}
	protected function getOfferAvailability(array $arOffer = []): string
	{
		$availability = "OutOfStock";
		if (SolutionProduct::$catalogInclude) {
			$availability = ($arOffer['CAN_BUY'] ? 'InStock' : 'OutOfStock');
		} else {
			$arStatus = SolutionQuantity::getStatus([
				'ITEM' => $arOffer,
				'TOTAL_COUNT' => $this->totalOffers,
				'IS_DETAIL' => true,
			]);

			$availability = SolutionFunctions::showSchemaAvailabilityMeta($arStatus['CODE'], true);
		}
		
		return "http://schema.org/".$availability;
	}
}