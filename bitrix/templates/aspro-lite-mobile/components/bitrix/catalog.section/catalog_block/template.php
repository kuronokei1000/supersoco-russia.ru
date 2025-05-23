<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Web\Json;

$this->setFrameMode(true);

$bBigDataMode = $arParams['BIG_DATA_MODE'] === 'Y';
?>
<?if($arResult["ITEMS"]):?>
	<!-- items-container -->
	<?
	$templateData['ITEMS'] = true;

	\TSolution\Product\Price::checkCatalogModule(); // check included catalog module (\TSolution\Product\Price::$catalogInclude)

	$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];
	$bUseSchema = !(isset($arParams["NO_USE_SHCEMA_ORG"]) && $arParams["NO_USE_SHCEMA_ORG"] == "Y");
	$bAjax = $arParams["AJAX_REQUEST"]=="Y";
	$bMobileScrolledItems = $arParams['MOBILE_SCROLLED'];
	$bSlider = $arParams['SLIDER'] === true || $arParams['SLIDER'] === 'Y';

	$bShowCompare = $arParams['DISPLAY_COMPARE'];
	$bShowFavorit = $arParams['SHOW_FAVORITE'] == 'Y';
	$bShowRating = $arParams['SHOW_RATING'] == 'Y';

	$elementInRow = $arParams['ELEMENT_IN_ROW'];

	$bOrderViewBasket = $arParams['ORDER_VIEW'];
	$basketURL = (strlen(trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']) : '');

	$bUseSelectOffer = false;

	if ($bSlider) {
		$sliderClasses = 'mobile-scrolled  mobile-offset mobile-scrolled--items-3';
		$arParams['SHOW_GALLERY'] = 'N';
	} else {	
		if($bMobileScrolledItems){
			$gridClass .= ' mobile-scrolled mobile-scrolled--items-3 mobile-offset';
			$arParams['SHOW_GALLERY'] = 'N';
		}
		else {
			$gridClass .= ' grid-list--compact';
		}
	
		$gridClass .= ' grid-list--items-'.$elementInRow.'-1200 grid-list--items-'.($elementInRow-1).'-992 grid-list--items-3-768 grid-list--items-2-601';
	}

	$itemClass = ' outer-rounded-x bg-theme-parent-hover border-theme-parent-hover color-theme-parent-all js-popup-block';
	if (!$bSlider)
		$itemClass .= ' shadow-hovered shadow-hovered-f600 shadow-no-border-hovered';
	
	if ($arParams['BORDERED'] !== 'N') {
		$itemClass .= ' bordered';
	}?>
	<?if(!$bAjax):?>
	<div class="catalog-items <?=$templateName;?>_template <?=$arParams['IS_COMPACT_SLIDER'] ? 'compact-catalog-slider' : ''?>">
		<div class="fast_view_params" data-params="<?=urlencode(serialize($arTransferParams));?>"></div>
		<?if ($arResult['SKU_CONFIG']):?><div class="js-sku-config" data-value='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arResult['SKU_CONFIG'], false, true))?>'></div><?endif;?>
		<div class="catalog-block<?= $bSlider ? ' relative swiper-nav-offset' : ''; ?>" <?if ($bUseSchema):?>itemscope itemtype="http://schema.org/ItemList"<?endif;?> >
			<?if($bSlider):?>
				<div class="js_append ajax_load appear-block <?=$sliderClasses?>" data-p="<?=$arParams['SHOW_GALLERY']?>">
				<? if ($sliderWrapperClasses): ?>
					<div class="<?= $sliderWrapperClasses; ?>">
				<? endif; ?>
			<?else:?>
				<div class="js_append ajax_load block grid-list grid-list--fill-bg <?=$gridClass?>">
			<?endif;?>
	<?endif;?>
		<?foreach($arResult["ITEMS"] as $arItem){
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$item_id = $arItem["ID"];
			$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);

			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
			// use order button?
			$bOrderButton = ($arItem["PROPERTIES"]["FORM_ORDER"]["VALUE_XML_ID"] == "YES");
			$dataItem = TSolution::getDataItem($arItem);

			$article = $arItem['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'];

			//unset($arItem['OFFERS']); // get correct totalCount
			$totalCount = TSolution\Product\Quantity::getTotalCount([
				'ITEM' => $arItem, 
				'PARAMS' => $arParams
			]);
			$arStatus = TSolution\Product\Quantity::getStatus([
				'ITEM' => $arItem, 
				'PARAMS' => $arParams,
				'TOTAL_COUNT' => $totalCount
			]);

			/* sku replace start */
			$arCurrentOffer = $arItem['SKU']['CURRENT'];

			if ($arCurrentOffer) {
				$arItem['PARENT_IMG'] = '';
				if ($arItem['PREVIEW_PICTURE']) {
					$arItem['PARENT_IMG'] = $arItem['PREVIEW_PICTURE'];
				} elseif ($arItem['DETAIL_PICTURE']) {
					$arItem['PARENT_IMG'] = $arItem['DETAIL_PICTURE'];
				}

				$oid = \Bitrix\Main\Config\Option::get(VENDOR_MODULE_ID, 'CATALOG_OID', 'oid');
				if ($oid) {
					$arItem['DETAIL_PAGE_URL'].= '?'.$oid.'='.$arCurrentOffer['ID'];
					$arCurrentOffer['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'];
				}
				if ($arParams['SHOW_GALLERY'] === 'Y') {
					if($arCurrentOffer["PREVIEW_PICTURE"]){
						$arCurrentOffer["DETAIL_PICTURE"] = $arCurrentOffer["PREVIEW_PICTURE"];
					}
					$arOfferGallery = TSolution\Functions::getSliderForItem([
						'TYPE' => 'catalog_block',
						'PROP_CODE' => $arParams['OFFER_ADD_PICT_PROP'],
						// 'ADD_DETAIL_SLIDER' => false,
						'ITEM' => $arCurrentOffer,
						'PARAMS' => $arParams,
					]);
					if ($arOfferGallery) {
						$arItem['GALLERY'] = array_merge($arOfferGallery, $arItem['GALLERY']);
						array_splice($arItem['GALLERY'], $arParams['MAX_GALLERY_ITEMS']);
					}
				} else {
					if ($arCurrentOffer['PREVIEW_PICTURE'] || $arCurrentOffer['DETAIL_PICTURE']) {
						if ($arCurrentOffer['PREVIEW_PICTURE']) {
							$arItem['PREVIEW_PICTURE'] = $arCurrentOffer['PREVIEW_PICTURE'];
						} elseif ($arCurrentOffer['DETAIL_PICTURE']) {
							$arItem['PREVIEW_PICTURE'] = $arCurrentOffer['DETAIL_PICTURE'];
						}
					}
					if (!$arCurrentOffer['PREVIEW_PICTURE'] && !$arCurrentOffer['DETAIL_PICTURE']) {
						if ($arItem['PREVIEW_PICTURE']) {
							$arCurrentOffer['PREVIEW_PICTURE'] = $arItem['PREVIEW_PICTURE'];
						} elseif ($arItem['DETAIL_PICTURE']) {
							$arCurrentOffer['PREVIEW_PICTURE'] = $arItem['DETAIL_PICTURE'];
						}
					}
				}

				if ($arCurrentOffer["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"] || $arCurrentOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) {
					$article = $arCurrentOffer['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'] ?? $arCurrentOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"];
				}
				
				$arItem["DISPLAY_PROPERTIES"]["FORM_ORDER"] = $arCurrentOffer["DISPLAY_PROPERTIES"]["FORM_ORDER"];
				$arItem["DISPLAY_PROPERTIES"]["PRICE"] = $arCurrentOffer["DISPLAY_PROPERTIES"]["PRICE"];
				$arItem["NAME"] = $arCurrentOffer["NAME"];
				$elementName = $arCurrentOffer["NAME"];
				$arItem['OFFER_PROP'] = TSolution::PrepareItemProps($arCurrentOffer['DISPLAY_PROPERTIES']);
				
				$dataItem = TSolution::getDataItem($arCurrentOffer);

				$totalCount = TSolution\Product\Quantity::getTotalCount([
					'ITEM' => $arCurrentOffer, 
					'PARAMS' => $arParams
				]);
				$arStatus = TSolution\Product\Quantity::getStatus([
					'ITEM' => $arCurrentOffer, 
					'PARAMS' => $arParams,
					'TOTAL_COUNT' => $totalCount
				]);
			}
			$bOrderButton = ($arItem["DISPLAY_PROPERTIES"]["FORM_ORDER"]["VALUE_XML_ID"] == "YES");

			$status = $arStatus['NAME'];
			$statusCode = $arStatus['CODE'];
			/* sku replace end */?>
			
			<?$itemDiscount = '';?>
			
			<div class="catalog-block__wrapper<?= $elementSliderClasses; ?> grid-list__item grid-list-border-outer <?=($arCurrentOffer ? 'has-offers' : '');?>" data-hovered="false">
				<?if (TSolution::isSaleMode()):?>
					<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>_<?=$arParams["FILTER_HIT_PROP"]?>" style="display: none;">
						<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])):?>
							<?foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo):?>
								<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE'];?>[<?=$propID;?>]" value="<?=htmlspecialcharsbx($propInfo['ID']);?>">
								<?if (isset($arItem['PRODUCT_PROPERTIES'][$propID])){
									unset($arItem['PRODUCT_PROPERTIES'][$propID]);
								}?>
							<?endforeach;?>
						<?endif;?>
						<?if ($arItem['PRODUCT_PROPERTIES']):?>
							<div class="wrapper">
								<?foreach($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo):?>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group fill-animate">
												<?if(
													'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 
													'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
												):?>
													<label class="font_14"><span><?=$arItem['PROPERTIES'][$propID]['NAME']?></span></label>
													<?foreach($propInfo['VALUES'] as $valueID => $value):?>
														<div class="form-radiobox">
															<label class="form-radiobox__label">
																<input class="form-radiobox__input" type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]" value="<?=$valueID?>">
																<span class="bx_filter_input_checkbox">
																	<span><?=$value?></span>
																</span>
																<span class="form-radiobox__box"></span>
															</label>
														</div>
													<?endforeach;?>
												<?else:?>
													<label class="font_14"><span><?=$arItem['PROPERTIES'][$propID]['NAME']?></span></label>
													<div class="input">
														<select class="form-control" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]">
															<?foreach($propInfo['VALUES'] as $valueID => $value):?>
																<option value="<?=$valueID?>" <?=($valueID == $propInfo['SELECTED'] ? '"selected"' : '')?>><?=$value?></option>
															<?endforeach;?>
														</select>
													</div>
												<?endif;?>
											</div>
										</div>
									</div>
								<?endforeach;?>
							</div>
						<?endif;?>
					</div>
				<?endif;?>
				
				<div class="catalog-block__item <?=$itemClass?>" id="<?=$arItem["strMainID"]?>">
					<?if ($arItem['SKU']['PROPS']):?>
						<template class="offers-template-json">
							<?=TSolution\SKU::getOfferTreeJson($arItem["SKU"]["OFFERS"])?>
						</template>
						<?$bUseSelectOffer = true;?>
					<?endif;?>
					<div class="catalog-block__inner flexbox height-100" <?if ($bUseSchema):?>itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product"<?endif;?>>
						<?if ($bUseSchema):?>
							<meta itemprop="description" content="<?=htmlspecialcharsbx(strip_tags($arItem['PREVIEW_TEXT'] ?: $arItem['NAME']))?>" />
						<?endif;?>
						<?$arImgConfig = [
							'TYPE' => 'catalog_block',
							'ADDITIONAL_IMG_CLASS' => 'js-replace-img',
							'ADDITIONAL_WRAPPER_CLASS' => ($arParams['IMG_CORNER'] == 'Y' ? 'catalog-block__item--img-corner' : ''),
						];?>
						<div class="js-config-img" data-img-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arImgConfig, false, true))?>'></div>
						
						<?$priceHtml = $discountHtml = '';?>
						<?if (TSolution\Product\Price::check(($arCurrentOffer ? $arCurrentOffer : $arItem))) {
							$arPrices = TSolution\Product\Price::show([
								'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arItem),
								'PARAMS' => $arParams,
								'SHOW_SCHEMA' => $bUseSchema,
								'BASKET' => $bOrderViewBasket,
								'RETURN' => true,
								'APART_ECONOMY' => true,
								'PRICE_FONT' => '18 font_14--to-600',
							]);
							if ($arPrices['PRICES']) {
								$priceHtml = $arPrices['PRICES'];
							}
							if ($arPrices['ECONOMY']) {
								$discountHtml = $arPrices['ECONOMY'];
							}
						}?>
						<?$arParams['SHOW_FAST_VIEW'] = 'N';?>
						<?=TSolution\Product\Image::showImage(
							array_merge(
								[
									'ITEM' => $arItem,
									'PARAMS' => $arParams,
									'CONTENT_TOP' => $itemDiscount,
									'CONTENT_BOTTOM' => $discountHtml,
								],
								$arImgConfig
							)
						)?>

						<?if ($bUseSchema):?>
							<meta itemprop="name" content="<?=$arItem['NAME']?>">
							<link itemprop="url" href="<?=$arItem['DETAIL_PAGE_URL']?>">
						<?endif;?>
						<div 
							class="catalog-block__info flex-1 flexbox flexbox--justify-beetwen" 
							data-id="<?=($arCurrentOffer ? $arCurrentOffer['ID'] : $arItem['ID'])?>"
							data-item="<?=$dataItem;?>"
							<?if ($bUseSchema):?>itemprop="offers" itemscope itemtype="http://schema.org/Offer"<?endif;?>
						>
							<div class="catalog-block__info-top">
								<div class="catalog-block__info-inner">
									<?// element price?>
									<?$arPriceConfig = [
										'PRICE_CODE' => $arParams['PRICE_CODE'],
										'PRICE_FONT' => '18 font_14--to-600',
										'PARAMS' => [
											'SHOW_DISCOUNT_PERCENT' => 'N' // hide discount in js_item_detail.php
										],
									];?>
									<div class="js-popup-price" data-price-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arPriceConfig, false, true))?>'>
										<?if ($priceHtml):?>
											<?=$priceHtml;?>
										<?endif;?>
									</div>
									<?// element title?>
									<div class="catalog-block__info-title linecamp-3 height-auto-t600 font_15 font_14--to-600">
										<?if ($bUseSchema):?>
											<link itemprop="url" href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<?endif;?>
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link switcher-title js-popup-title color-theme-target"><span><?=$elementName;?></span></a>
									</div>
									<?if ($bShowRating || strlen($status) || strlen($article)):?>
										<div class="catalog-block__info-tech">
											<div class="line-block line-block--12 flexbox--wrap js-popup-info">
												<?// rating?>
												<?if ($bShowRating):?>
													<div class="line-block__item font_13 font_12--to-600">
														<?=\TSolution\Product\Common::getRatingHtml([
															'ITEM' => $arItem,
															'PARAMS' => $arParams,
														])?>
													</div>
												<?endif;?>
												<?// status?>
												<?if ((strlen($status) && !isset($arParams['HIDE_STATUS_BUTTON']))):?>
													<div class="line-block__item font_13 font_12--to-600">
														<?if ($bUseSchema):?>
															<?=TSolution\Functions::showSchemaAvailabilityMeta($statusCode);?>
														<?endif;?>
														<span 
															 class="status-icon <?=$statusCode?> js-replace-status" 
															 data-state="<?=$statusCode?>"
															 data-code="<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" 
															 data-value="<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?>"
															><?=$status?></span>
													</div>
												<?endif;?>
												<?// article?>
												<?if (strlen($article)):?>
													<div class="line-block__item font_13 font_12--to-600 color_999">
														<span class="article"><?=GetMessage('S_ARTICLE')?>&nbsp;<span 
															 class="js-replace-article"
															 data-value="<?=$arItem['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE']?>"
															><?=$article?></span></span>
													</div>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
								</div>
							</div>
						</div>
						<?// element btns?>
						<?$arBtnConfig = [
							'BASKET_URL' => $basketURL,
							'BASKET' => $bOrderViewBasket,
							'ORDER_BTN' => $bOrderButton,
							'BTN_CLASS' => 'btn-sm btn-wide',
							'BTN_CLASS_MORE' => 'btn-sm bg-theme-target border-theme-target',
							'BTN_IN_CART_CLASS' => 'btn-sm',
							'BTN_CLASS_SUBSCRIBE' => 'btn-sm',
							'BTN_ORDER_CLASS' => 'btn-sm btn-wide btn-transparent-border',
							'ONE_CLICK_BUY' => false,
							'SHOW_COUNTER' => false,
							'CATALOG_IBLOCK_ID' => $arItem['IBLOCK_ID'],
							'ITEM_ID' => $arItem['ID'],
						];
						if ($arParams['TYPE_SKU'] === 'TYPE_2' && $arItem['HAS_SKU']) {
							$arBtnConfig['SHOW_MORE'] = true;
							$arItem['CAN_BUY'] = 'N';
							$totalCount = 0;
						}?>
						<?
						$arBasketConfig = TSolution\Product\Basket::getOptions(array_merge(
							$arBtnConfig, 
							[
								'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arItem),
								'IS_OFFER' => (boolean)$arCurrentOffer,
								'PARAMS' => $arParams,
								'TOTAL_COUNT' => $totalCount
							]
						));?>
						<?if (
							($bShowCompare 
							|| $bShowFavorit 
							|| ($arCurrentOffer
								|| (!$arCurrentOffer && $arBasketConfig['HTML'])
							)
							|| $arItem['SKU']['PROPS'])
							&& !isset($arParams['HIDE_BUY_BUTTON'])
						):?>
							<div class="catalog-block__info-bottom <?=($arCurrentOffer ? 'catalog-block__info-bottom--with-sku' : '');?>">
								<div class="line-block line-block--8 line-block--8-vertical flexbox--wrap flexbox--justify-center">
									<div class="line-block__item js-btn-state-wrapper flex-1 <?=($arCurrentOffer ? 'hide-600' : '');?> <?=(!$arBasketConfig['HTML'] ? ' hidden' : '');?>">
										<div class="js-replace-btns js-config-btns" data-btn-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arBtnConfig, false, true))?>'>
											<?=$arBasketConfig['HTML']?>
										</div>
									</div>
									<?if ($arCurrentOffer):?>
										<div class="visible-600 line-block__item flex-1">
											<?=TSolution\Product\Basket::getMoreButton([
												'ITEM' => $arCurrentOffer,
												'BTN_CLASS_MORE' => ''
											]);?>
										</div>
									<?endif;?>
									<?if ($bShowCompare || $bShowFavorit):?>
										<div class="line-block__item js-replace-icons">
											<?if ($bShowFavorit):?>
												<?=\TSolution\Product\Common::getActionIcon([
													'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arItem),
													'PARAMS' => $arParams,
													'CLASS' => 'md',
												])?>
											<?endif;?>
											<?if ($bShowCompare):?>
												<?=\TSolution\Product\Common::getActionIcon([
													'ITEM' => (($arCurrentOffer && \TSolution::isSaleMode()) ? $arCurrentOffer : $arItem),
													'PARAMS' => $arParams,
													'TYPE' => 'compare',
													'CLASS' => 'md',
													'SVG_SIZE' => ['WIDTH' => 20,'HEIGHT' => 16],
												])?>
											<?endif;?>
										</div>
									<?endif;?>
								</div>
								<?if ($arItem['SKU']['PROPS'] && !$bMobileScrolledItems):?>
									<div class="catalog-block__offers hide-600">
										<div 
										class="sku-props sku-props--block"
										data-site-id="<?=SITE_ID;?>"
										data-item-id="<?=$arItem['ID'];?>"
										data-iblockid="<?=$arItem['IBLOCK_ID'];?>"
										data-offer-id="<?=$arCurrentOffer['ID'];?>"
										data-offer-iblockid="<?=$arCurrentOffer['IBLOCK_ID'];?>"
										data-offers-id='<?=str_replace('\'', '"', CUtil::PhpToJSObject($GLOBALS[$arParams['FILTER_NAME']]['OFFERS_ID'], false, true))?>'
										>
											<div class="line-block line-block--flex-wrap line-block--40 line-block--align-flex-end line-block--flex-100">
												<?=TSolution\SKU\Template::showSkuPropsHtml($arItem['SKU']['PROPS'])?>
											</div>
										</div>
									</div>
								<?endif;?>
							</div>
						<?endif;?>
					</div>
				</div>

			</div>
		<?}?>

		<?if(!$bSlider):?>
			<?if ($bHasBottomPager && $bMobileScrolledItems):?>
				<?if($bAjax):?>
					<div class="wrap_nav bottom_nav_wrapper">
				<?endif;?>

					<?$bHasNav = (strpos($arResult["NAV_STRING"], 'more_text_ajax') !== false);?>
					<div class="bottom_nav mobile_slider <?=($bHasNav ? '' : ' hidden-nav');?>" data-parent=".catalog-block" data-append=".grid-list" <?=($bAjax ? "style='display: none; '" : "");?>>
						<?=$arResult["NAV_STRING"]?>
					</div>

				<?if($bAjax):?>
					</div>
				<?endif;?>
			<?endif;?>
		<?endif;?>

	<?if(!$bAjax):?>
			<? if ($sliderWrapperClasses): ?>
				</div>
			<? endif; ?>
			</div> <?//.js_append ajax_load block grid-list?>
			
	<?endif;?>

		<?if($bAjax):?>
			<div class="wrap_nav bottom_nav_wrapper">
		<?endif;?>

		<?if($arParams['HEADING_COUNT_ELEMENTS'] == 'Y'):?>
			<?$textCount = TSolution\Functions::declOfNum($arResult['NAV_RESULT']->NavRecordCount, [Loc::getMessage('ONE_ITEM'), Loc::getMessage('TWO_ITEM'), Loc::getMessage('FIVE_ITEM')])?>
			<?if((int)$arResult['NAV_RESULT']->NavRecordCount > 0):?>
				<?$this->SetViewTarget("more_text_title");?>
					<span class="element-count-wrapper"><span class="element-count color_999 rounded-4"><?=$textCount;?></span></span>
				<?$this->EndViewTarget();?>
			<?endif;?>
		<?endif;?>

		<div class="bottom_nav_wrapper nav-compact">
			<div class="bottom_nav <?=($bMobileScrolledItems ? 'hide-600' : '');?>" <?=($arParams['HEADING_COUNT_ELEMENTS'] == 'Y' ? 'data-all_count="'.$textCount.'"' : '')?> <?=($arParams['AJAX_REQUEST'] == "Y" ? "style='display: none; '" : "");?> data-count="<?=$arResult['NAV_RESULT']->NavRecordCount;?>" data-parent=".catalog-block" data-append=".grid-list">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>	

	<?if($bAjax):?>
		</div>
	<?endif;?>

	<?if(!$bAjax):?>
		</div> <?//.catalog-block?>
	</div> <?//.catalog-items?>
	<?endif;?>

	<script>typeof initCountdown === "function" && initCountdown()</script>
	<?if($bUseSelectOffer && !$bMobileScrolledItems):?>
		<script>typeof useOfferSelect === 'function' && useOfferSelect()</script>
	<?endif;?>

	<!-- items-container -->
<?elseif($arParams['IS_CATALOG_PAGE'] == 'Y' && !$bBigDataMode):?>
	<div class="no_goods catalog_block_view">
		<div class="no_products">
			<div class="wrap_text_empty">
				<?if($_REQUEST["set_filter"]){?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products_filter.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}else{?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}?>
			</div>
		</div>
	</div>
<?endif;?>

<?if ($bBigDataMode):?>
	<?$signer = new \Bitrix\Main\Security\Sign\Signer;?>
	<script>
	new JBigData(
		<?=CUtil::PhpToJSObject([
			'siteId' => $component->getSiteId(),
			'count' => $arParams['BIGDATA_COUNT'],
			'bigData' => $arResult['BIG_DATA'],
			'parameters' => $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section'),
			'template' => $signer->sign($templateName, 'catalog.section'),
		])?>
	);
	</script>
<?endif;?>