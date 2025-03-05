<?
namespace Aspro\Lite\Product;

use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option,
	CLite as Solution,
    CLiteCache as SolutionCache,
    Aspro\Functions\CAsproLite as SolutionFunctions;

class Common {
	public static $catalogInclude = null;

	public static function checkCatalogModule()
    {
        if (static::$catalogInclude === null) {
			static::$catalogInclude = Solution::isSaleMode();
		}
    }

	public static function showStickers($arOptions = [])
	{
		global $APPLICATION;
		$arDefaultOptions = [
			'TYPE' => '',
			'WRAPPER' => '',
			'CONTENT' => '',
			'DOP_CLASS' => '',
			'ITEM' => [],
			'PARAMS' => [],
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		if ($handler = SolutionFunctions::getCustomFunc(__FUNCTION__)) {
			return call_user_func_array($handler, [$arConfig]);
		}

		$arParams = $arConfig['PARAMS'];
		$arItem = $arConfig['ITEM'];
		if($arItem):?>
			<?ob_start();?>

			<?$prop = ($arParams["STIKERS_PROP"] ? $arParams["STIKERS_PROP"] : "HIT");?>
			<?$saleSticker = ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT");?>

			<?if(
				$arItem["PROPERTIES"][$prop]['VALUE_XML_ID']
				|| $arItem["PROPERTIES"][$saleSticker]["VALUE"]
				|| $arConfig['CONTENT']
			):?>
				<?if($arConfig['WRAPPER']):?>
					<div class="<?=$arConfig['WRAPPER']?>">
				<?endif;?>
				<div class="sticker sticker--upper <?=$arConfig['DOP_CLASS']?>">

					<?if (is_array($arItem["PROPERTIES"][$prop]['VALUE_XML_ID'])):?>
						<?foreach($arItem["PROPERTIES"][$prop]['VALUE_XML_ID'] as $key => $class):?>
							<div><div class="sticker__item sticker__item--<?=strtolower($class);?> font_10"><?=$arItem['PROPERTIES']['HIT']['VALUE'][$key]?></div></div>
						<?endforeach;?>
					<?endif;?>

					<?if($arItem["PROPERTIES"][$saleSticker]["VALUE"]):?>
						<div><div class="sticker__item sticker__item--sale-text font_10"><?=$arItem["PROPERTIES"][$saleSticker]["VALUE"];?></div></div>
					<?endif;?>
					
					<?if($arConfig['CONTENT']):?>
						<div><?=$arConfig['CONTENT'];?></div>
					<?endif;?>
				</div>
				<?if($arConfig['WRAPPER']):?>
					</div>
				<?endif;?>
			<?endif;?>
			<?$html = ob_get_contents();
			ob_end_clean();

			// event for manipulation
			foreach (GetModuleEvents(Solution::moduleID, 'OnAspro'.ucfirst(__FUNCTION__), true) as $arEvent) {
				ExecuteModuleEventEx($arEvent, array($arConfig, &$html));
			}

			echo trim($html);?>
		<?endif;?>
	<?}

	public static function showFastView($arOptions = ['ITEM' => [], 'PARAMS' => ''])
	{
		$arDefaultOptions = [
			'WRAPPER' => '',
			'BTN_CLASS' => 'btn btn-xs btn-default',
			'WITH_TEXT' => 'Y',
			'WITH_ICON' => 'N',
			'ITEM' => [],
			'PARAMS' => [],
			'RETURN' => false,
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		$arParams = $arOptions['PARAMS'];
		$arItem = $arOptions['ITEM'];

		$arThemeOptions = Solution::GetBackParametrsValues(SITE_ID);
		?>
		<?ob_start();?>
			<?if ($arThemeOptions['USE_FAST_VIEW_PAGE_DETAIL'] != 'NO' && $arParams['USE_FAST_VIEW_PAGE_DETAIL'] != 'NO' && $arParams['SHOW_FAST_VIEW'] !== 'N'):?>
				<?$sFastOrderText = $arThemeOptions['EXPRESSION_FOR_FAST_VIEW'];?>
				<?if($arConfig['WRAPPER']):?>
					<div class="<?=$arConfig['WRAPPER']?>">
				<?endif;?>
				<div class="btn-fast-view rounded-x hide-600">
					<div data-event="jqm" class="<?=$arConfig['BTN_CLASS'];?>" data-name="fast_view" data-param-form_id="fast_view" data-param-iblock_id="<?=$arItem['IBLOCK_ID']?>" data-param-id="<?=$arItem['ID']?>" data-param-item_href="<?=urlencode($arItem['DETAIL_PAGE_URL'])?>">
						<?if ($arConfig['WITH_ICON'] === 'Y'):?>
							<?=Solution::showIconSvg("side-search", SITE_TEMPLATE_PATH."/images/svg/catalog/Fancy_side.svg");?>
						<?endif;?>
						<?if ($arConfig['WITH_TEXT'] === 'Y'):?>
							<?=$sFastOrderText?>
						<?endif;?>
					</div>
				</div>
				<?if($arConfig['WRAPPER']):?>
					</div>
				<?endif;?>
			<?endif;?>
		<?$html = ob_get_contents();
		ob_end_clean();

		// event for manipulation
		foreach (GetModuleEvents(Solution::moduleID, 'OnAspro'.ucfirst(__FUNCTION__), true) as $arEvent) {
			ExecuteModuleEventEx($arEvent, array($arConfig, &$html));
		}
		if ($arConfig['RETURN']) {
			return $html;
		} else {
			echo $html;
		}?>
	<?}

	public static function getActionIcon($arOptions = ['ITEM' => [], 'PARAMS' => ''])
	{
		$arDefaultOptions = [
			'WRAPPER' => '',
			'CLASS' => 'sm',
			'ITEM_ACTION_CLASS' => '',
			'TYPE' => 'favorite',
			'ORIENT' => 'horizontal',
			'ITEM' => [],
			'PARAMS' => [],
			'SVG_SIZE' => ['WIDTH' => 18,'HEIGHT' => 16],
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		$upperType = strtoupper($arConfig['TYPE']);
		$arItem = $arOptions['ITEM'];

		[
			'bSku' => $bSku,
			'id' => $id,
			'productId' => $productId,
		] = self::getProduct($arItem);
		

		// modify offer_id to product_id in type_sku=2
		if (
			$bSku &&
			(
				// $arConfig['TYPE'] === 'favorite' ||
				(
					$arConfig['TYPE'] === 'compare' &&
					!Solution::isSaleMode()
				)
			)
		) {
			$id = $productId;
		}
		?>
		<?$html = '';?>
		<?ob_start();?>
			<?if($arConfig['WRAPPER']):?>
				<div class="<?=$arConfig['WRAPPER']?>">
			<?endif;?>
			<div class="item-action item-action--<?=$arConfig['ORIENT'];?> item-action--<?=$arConfig['TYPE'];?> <?=$arConfig['ITEM_ACTION_CLASS'];?>">
				<a href="javascript:void(0)" rel="nofollow" class="item-action__inner item-action__inner--<?=$arConfig['CLASS'];?> item-action__inner--sm-to-600 js-item-action fill-theme-use-svg-hover fill-dark-light-block" data-action="<?=$arConfig['TYPE'];?>" data-id="<?=$id?>" title="<?=Loc::getMessage($upperType.'_ITEM')?>" data-title="<?=Loc::getMessage($upperType.'_ITEM')?>" data-title_added="<?=Loc::getMessage($upperType.'_ITEM_REMOVE')?>">
					<?=Solution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/catalog/item_icons.svg#'.$arConfig['TYPE'].'-'.implode('-', $arConfig['SVG_SIZE']), '', $arConfig['SVG_SIZE']);?>
				</a>
			</div>
			<?if($arConfig['WRAPPER']):?>
				</div>
			<?endif;?>
		<?$html = ob_get_contents();
		ob_end_clean();?>
		<?return $html;?>
	<?}
	
	public static function getRatingHtml($arOptions = ['ITEM' => [], 'PARAMS' => ''])
	{
		$arDefaultOptions = [
			'WRAPPER' => '',
			'PADDING' => '4',
			'ITEM' => [],
			'PARAMS' => [],
			'SVG_SIZE' => ['WIDTH' => 13,'HEIGHT' => 13],
			'SVG_ICON_SIZE' => ['WIDTH' => 13,'HEIGHT' => 13],
			'SHOW_REVIEW_COUNT' => false,
			'USE_SCHEMA' => false,
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		$arParams = $arOptions['PARAMS'];
		$arItem = $arOptions['ITEM'];
		$bUseSchema = $arConfig['USE_SCHEMA'];
		?>
		<?$html = '';?>
		<?ob_start();?>
			<?$message = $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE');?>
			<?if($arConfig['WRAPPER']):?>
				<div class="<?=$arConfig['WRAPPER']?>">
			<?endif;?>
			<div class="rating" title="<?=$message?>" <?=$bUseSchema ? 'itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"' : '';?>>
				<?if ($bUseSchema):?>
					<meta itemprop="ratingValue" content="<?=(float)$arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ?: 5;?>" />
					<meta itemprop="reviewCount" content="<?=(int)$arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ?: 1;?>" />
					<meta itemprop="bestRating" content="5" />
					<meta itemprop="worstRating" content="1" />
				<?endif;?>
				<div class="line-block line-block--<?=$arConfig['PADDING'];?>">
					<div class="line-block__item flexbox">
						<?= Solution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/catalog/item_icons.svg#star-'.implode('-', $arConfig['SVG_ICON_SIZE']), 
							'rating__star-svg' . ($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? ' rating__star-svg--filled' : ''), 
							$arConfig['SVG_SIZE']
						);?>
					</div>

					<div class="line-block__item rating__value">
						<?= (float)$arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE']; ?>
					</div>

					<? if ($arConfig['SHOW_REVIEW_COUNT']): ?>
						<div class="line-block__item rating__count">
							<a href="#reviews" class="dotted font_14 color_222 rating__static-block">
								<?= $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] 
									? SolutionFunctions::declOfNum($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'], [GetMessage('ONE_REVIEW'), GetMessage('TWO_REVIEWS'), GetMessage('FIVE_REVIEWS')])
									: GetMessage('VOTES_RESULT_NONE');
								?>
							</a>
						</div>
					<? endif; ?>
				</div>
			</div>
			<?if($arConfig['WRAPPER']):?>
				</div>
			<?endif;?>
		<?$html = ob_get_contents();
		ob_end_clean();?>
		<?return $html;?>
	<?}

	public static function showModalBlock($arOptions)
	{
		$arDefaultConfig = [
			'DATA_ATTRS' => [],
			'NAME' => '',
			'NAME_CLASS' => '',
			'SHOW_ICON' => true,
			'SVG_PATH' => '',
			'SVG_SIZE' => [],
			'ICON_CLASS' => '',
			'TEXT' => '',
			'TEXT_CLASS' => '',
			'USE_SIZE_IN_PATH' => true,
			'WRAPPER' => '',
		];
		$arConfig = array_merge($arDefaultConfig, $arOptions);
	?>
		<?$html = '';?>
		<?ob_start();?>
		<span>
			<span class="catalog-detail__pseudo-link<?= $arConfig['WRAPPER'] ? ' ' . $arConfig['WRAPPER'] : ''; ?>"
				<? foreach ($arConfig['DATA_ATTRS'] as $attr => $value): ?>
					data-<?= $attr; ?>="<?= $value; ?>"
				<? endforeach; ?>
			>
				<? if ($arConfig['SHOW_ICON'] && $arConfig['SVG_PATH']): ?>
					<? $svg_path = SITE_TEMPLATE_PATH . '/images/svg' . $arConfig['SVG_PATH'] . ($arConfig['SVG_SIZE'] && $arConfig['USE_SIZE_IN_PATH'] ? '-' . implode('-', $arConfig['SVG_SIZE']) : ''); ?>
					<? if (file_exists($_SERVER['DOCUMENT_ROOT'] . explode('#', $svg_path)[0])): ?>
						<span class="icon-container">
							<?= Solution::showSpriteIconSvg($svg_path, 'pseudo-link__icon '.$arConfig['ICON_CLASS'], $arConfig['SVG_SIZE']); ?>
						</span>
					<? endif; ?>
				<? endif; ?>
				
				<span class="catalog-detail__pseudo-link-text">
					<? if ($arOptions['NAME']): ?>
						<span class="<?= $arConfig['NAME_CLASS'] ?: (count($arConfig['DATA_ATTRS']) ? 'dotted' : ''); ?>"><?= $arOptions['NAME']; ?></span>
					<? endif; ?>

					<? if ($arOptions['TEXT']): ?>
						<span class="<?= $arOptions['TEXT_CLASS']; ?>"><?= $arOptions['TEXT']; ?></span>
					<? endif; ?>
				</span>
			</span>
		</span>
		<?
		$html = ob_get_contents();
		ob_end_clean();
		// event for manipulation
		foreach (GetModuleEvents(Solution::moduleID, 'OnAspro'.ucfirst(__FUNCTION__), true) as $arEvent) {
			ExecuteModuleEventEx($arEvent, array($arConfig, &$html));
		}
		?>
		<?return $html;?>
	<?}

	public static function getViewedParams($arOptions = ['ITEM' => []])
	{
		$arViewedParams = [];

		if (
			($arItem = $arOptions['ITEM']) &&
			is_array($arItem)
		) {
			$priceOld = $price = $economy = false;
			$priceOldPrint = $pricePrint = $economyPrint = $currency = '';

			[
				'bSku' => $bSku,
				'id' => $id,
				'productId' => $productId,
			] = self::getProduct($arItem);

			if (Solution::isSaleMode()) {
				$arItemPrices = [];
				if ($arItem['MIN_PRICE']) {
					$arItemPrices =& $arItem['MIN_PRICE'];
				} elseif ($arItem['PRICES']) {
					$arItemPrices = Price::getCatalogPrice($arItem['PRICES']);
				} elseif ($arItem['ITEM_PRICES']){
					$arItemPrices = Price::getCatalogPriceNew($arItem);
				}

				if ($arItemPrices) {
					$priceOld = $arItemPrices['VALUE'];
					$priceOldPrint = $arItemPrices['PRINT_VALUE'];
					$price = $arItemPrices['DISCOUNT_VALUE'];
					$pricePrint = $arItemPrices['PRINT_DISCOUNT_VALUE'];
					$currency = $arItemPrices['CURRENCY'];
					$economy = $arItemPrices['DISCOUNT_DIFF'];
					$economyPrint = $arItemPrices['DISCOUNT_DIFF_PERCENT'] ? '-'.$arItemPrices['DISCOUNT_DIFF_PERCENT'].'%' : '-'.$arItemPrices['PRINT_DISCOUNT_DIFF'];
				}
			} else {				
				if ($arItem['PRICEOLD']) {
					$priceOld = $arItem['PRICEOLD']['VALUE'];
				} elseif (
					$arItem['DISPLAY_PROPERTIES'] &&
					$arItem['DISPLAY_PROPERTIES']['PRICEOLD']
				) {
					$priceOld = $arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'];
				}

				if ($arItem['PRICE']) {
					$price = $arItem['PRICE']['VALUE'];
				} elseif (
					$arItem['DISPLAY_PROPERTIES'] &&
					$arItem['DISPLAY_PROPERTIES']['PRICE']
				) {
					$price = $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'];
				}

				if ($arItem['ECONOMY']) {
					$economy = $arItem['ECONOMY']['VALUE'];
				} elseif (
					$arItem['DISPLAY_PROPERTIES'] &&
					$arItem['DISPLAY_PROPERTIES']['ECONOMY']
				) {
					$economy = $arItem['DISPLAY_PROPERTIES']['ECONOMY']['VALUE'];
				}

				if ($arItem['PRICE_CURRENCY']) {
					$currency = $arItem['PRICE_CURRENCY']['VALUE'];
				} elseif (
					$arItem['DISPLAY_PROPERTIES'] &&
					$arItem['DISPLAY_PROPERTIES']['PRICE_CURRENCY']
				) {
					$currency = $arItem['DISPLAY_PROPERTIES']['PRICE_CURRENCY']['VALUE'];
				}
				if (
					!$currency &&
					$arItem['PROPERTIES'] &&
					$arItem['PROPERTIES']['PRICE_CURRENCY']
				) {
					$currency = $arItem['PROPERTIES']['PRICE_CURRENCY']['VALUE'];
				}

				$priceOldPrint = trim(str_replace('#CURRENCY#', $currency, $priceOld));
				$pricePrint = trim(str_replace('#CURRENCY#', $currency, $price));
				$economyPrint = trim(str_replace('#CURRENCY#', $currency, $economy));
			}

			if (!$price) {
				$price = $priceOld;
				$pricePrint = $priceOldPrint;
			}

			if (!$priceOld) {
				$priceOld = $price;
				$priceOldPrint = $pricePrint;
			}

			$arItemPrice = [
				'PRICEOLD' => $priceOld,
				'PRICEOLD_PRINT' => $priceOldPrint,
				'PRICE' => $price,
				'PRICE_PRINT' => $pricePrint,
				'ECONOMY' => $economy,
				'ECONOMY_PRINT' => $economyPrint,
				'CURRENCY' => $currency,
			];

			$arViewedParams = [
				'ID' => $id,
				'PRODUCT_ID' => $productId,
				'IBLOCK_ID' => $arItem['IBLOCK_ID'],
				'NAME' => $arItem['NAME'],
				'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL'],
				'PICTURE_ID' => $arItem['PREVIEW_PICTURE'] ? (is_array($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['ID'] : $arItem['PREVIEW_PICTURE']) : false,
				'CATALOG_MEASURE_NAME' => $arItem['CATALOG_MEASURE_NAME'] ?? '',
				'PRICE' => $arItemPrice,
				'IS_OFFER' => $bSku ? 'Y' : 'N',
				'WITH_OFFERS' => $arItem['PRODUCT']['TYPE'] == 3 ? 'Y' : 'N',
			];
		}

		return $arViewedParams;
	}

	public static function addViewed($arOptions = ['ITEM' => []])
	{
		if (
			($arItem = $arOptions['ITEM']) &&
			is_array($arItem)
		) {
			$arViewedParams = self::getViewedParams([
				'ITEM' => $arItem
			]);

			if ($arViewedParams) {
				?>
				<script>
				if (typeof JViewed === 'function') {
					JViewed.get().addProduct(
						<?= $arViewedParams['ID'] ?>,
						<?= \Bitrix\Main\Web\Json::encode(
							$arViewedParams
						) ?>
					);
				}
				</script>
				<?
			}
		}
	}

	public static function getProduct($arItem) :array
	{
		$id = $productId = 0;
		$bSku = false;

		if (
			$arItem &&
			is_array($arItem)
		) {
			$id = $productId = $arItem['ID'];

			if (Solution::isSaleMode()) {
				$bSku = is_array($arItem['PRODUCT']) ? $arItem['PRODUCT']['TYPE'] == 4 : $arItem['CATALOG_TYPE'] == 4;
				if ($bSku) {
					if (
						$arItem['PROPERTIES'] &&
						$arItem['PROPERTIES']['CML2_LINK'] &&
						$arItem['PROPERTIES']['CML2_LINK']['VALUE']
					) {
						$productId = $arItem['PROPERTIES']['CML2_LINK']['VALUE'];
					} elseif (
						$arItem['DISPLAY_PROPERTIES'] &&
						$arItem['DISPLAY_PROPERTIES']['CML2_LINK'] &&
						$arItem['DISPLAY_PROPERTIES']['CML2_LINK']['VALUE']
					) {
						$productId = $arItem['DISPLAY_PROPERTIES']['CML2_LINK']['VALUE'];
					}
				}
			} else {
				if (
					$arItem['PROPERTIES'] &&
					$arItem['PROPERTIES']['CML2_LINK'] &&
					$arItem['PROPERTIES']['CML2_LINK']['VALUE']
				) {
					$productId = $arItem['PROPERTIES']['CML2_LINK']['VALUE'];
					$bSku = true;
				} elseif (
					$arItem['DISPLAY_PROPERTIES'] &&
					$arItem['DISPLAY_PROPERTIES']['CML2_LINK'] &&
					$arItem['DISPLAY_PROPERTIES']['CML2_LINK']['VALUE']
				) {
					$productId = $arItem['DISPLAY_PROPERTIES']['CML2_LINK']['VALUE'];
					$bSku = true;
				}
			}
		}

		return [
			'bSku' => $bSku,
			'id' => $id,
			'productId' => $productId,
		];
	}

	public static function getMeasureRatio($productId = null)
	{
		$mxRatio = 1;
		if (Loader::includeModule('catalog') && $productId) {
			$rsRatios = \CCatalogMeasureRatio::getList(
				array(),
				array('=PRODUCT_ID' => $productId),
				false,
				false,
				array('PRODUCT_ID', 'RATIO')
			);
			if ($arRatio = $rsRatios->Fetch())
			{
				$arRatio['PRODUCT_ID'] = (int)$arRatio['PRODUCT_ID'];
				$intRatio = (int)$arRatio['RATIO'];
				$dblRatio = (float)$arRatio['RATIO'];
				$mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
				if (CATALOG_VALUE_EPSILON > abs($mxRatio))
					$mxRatio = 1;
				elseif (0 > $mxRatio)
					$mxRatio = 1;
			}
		}
		return $mxRatio;
	}

	public static function getMeasureById($measureId): array
	{
		$result = [];
		if (Loader::includeModule('catalog')) {
			$rsMeasure = \CCatalogMeasure::getList(
				[],
				[
					'=ID' => $measureId
				]
			);
			if ($measure = $rsMeasure->fetch()) {
				$result = $measure;
			}
		}
		return $result;
    }

	public static function showMeasure(array $arMeasure)
	{
		$result = '';

		if ($arMeasure && $arMeasure['SYMBOL_RUS'] && Solution::GetFrontParametrValue('SHOW_MEASURE') === 'Y') {
			$result = '/<span>'.$arMeasure['SYMBOL_RUS'].'</span>';
		}
		return $result;
    }

	/**
	 * Filter blocks for component_epilog of catalog.element
	 * 
	 * @param array $arBlocks [
	 * 	'ORDERED' => [...]
	 * 	'STATIC' => [...]
	 * 	'EXCLUDED' => [...]
	 * ]
	 * @param string $templatePath
	 * @param string $templateName
	 * 
	 * @return array [
	 * 	'STATIC' => [...],
	 *  'ORDERED' => [...],
	 * ]
	 */
	public static function showEpilogBlocks(array $arBlocks, string $templatePath, string $templateName = ''): array
	{
		$epilogBlockPath = $templatePath . '/epilog_blocks/';
		$arBlocksStatic = ['tizers'];
		$arBlocksExcluded = [];
		
		// event for setup static blocks for solutions default templates
		foreach (GetModuleEvents(Solution::moduleID, 'onBeforeAsproShowEpilogBlock', true) as $arEvent)
			ExecuteModuleEventEx($arEvent, [&$arBlocksStatic, &$arBlocksExcluded, $templateName]);

		if ($arBlocks['STATIC']) {
			$arBlocksStatic = $arBlocks['STATIC'];
		}

		if ($arBlocks['EXCLUDED']) {
			$arBlocksExcluded = $arBlocks['EXCLUDED'];
		}

		$arBlocksOrdered = $arBlocks['ORDERED'] ?? [];

		if ($arBlocksStatic) {
			$arBlocksStatic = array_map(function ($blockCode) use ($epilogBlockPath) {
				$path = $epilogBlockPath . $blockCode . '.php';

				return file_exists($path) ? $path : '';
			}, $arBlocksStatic);
			$arBlocksStatic = array_diff($arBlocksStatic, ['']);
		}

		if ($arBlocksOrdered) {
			$arBlocksOrdered = array_map(function ($blockCode) use ($epilogBlockPath, $arBlocksStatic, $arBlocksExcluded) {
				$path = $epilogBlockPath . $blockCode . '.php';

				return file_exists($path) && !in_array($blockCode, $arBlocksStatic) && !in_array($blockCode, $arBlocksExcluded)
					? $path
					: '';
			}, $arBlocksOrdered);
			$arBlocksOrdered = array_diff($arBlocksOrdered, ['']);
		}

		return [
			'STATIC' => $arBlocksStatic,
			'ORDERED' => $arBlocksOrdered,
		];
	}
}
