<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
	  \Bitrix\Main\Web\Json;?>
<?if($arResult["ITEMS"]):?>
	<?
	$templateData['ITEMS'] = true;

	\TSolution\Product\Price::checkCatalogModule(); // check included catalog module (\TSolution\Product\Price::$catalogInclude)

	$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];
	$bUseSchema = !(isset($arParams["NO_USE_SHCEMA_ORG"]) && $arParams["NO_USE_SHCEMA_ORG"] == "Y");
	$bAjax = $arParams["AJAX_REQUEST"]=="Y";
	$bMobileScrolledItems = $arParams['MOBILE_SCROLLED'];

	$bShowCompare = $arParams['DISPLAY_COMPARE'] == 'Y';
	$bShowFavorit = $arParams['SHOW_FAVORITE'] == 'Y';
	$bShowRating = $arParams['SHOW_RATING'] == 'Y';

	$bOrderViewBasket = $arParams['ORDER_VIEW'];
	$basketURL = (strlen(trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']) : '');

	$gridClass .= ' grid-list--items-1';

	if($bMobileScrolledItems){
		$gridClass .= ' mobile-scrolled mobile-scrolled--items-3 mobile-offset';
	} else {
		$gridClass .= ' grid-list--compact';
	}

	$gridClass .= ' grid-list--no-gap';

	$itemClass = ' shadow-hovered shadow-hovered-f600 shadow-no-border-hovered side-icons-hover bg-theme-parent-hover color-theme-parent-all border-theme-parent-hover js-popup-block';

	if ($arParams['TEXT_CENTER']) {
		$itemClass .= ' catalog-block__item--centered';
	}

	$bUseSelectOffer = false;

	$bBottomButtons = (isset($arParams['POSITION_BTNS']) && $arParams['POSITION_BTNS'] == '4');
	?>
	<?$templateData['HAS_CHARACTERISTICS'] = false;?>
	<?if(!$bAjax):?>
	<div class="catalog-items <?=$templateName;?>_template <?=$arParams['IS_COMPACT_SLIDER'] ? 'compact-catalog-slider' : ''?>">
		<div class="fast_view_params" data-params="<?=urlencode(serialize($arTransferParams));?>"></div>
		<?if ($arResult['SKU_CONFIG']):?><div class="js-sku-config" data-value='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arResult['SKU_CONFIG'], false, true))?>'></div><?endif;?>
		<div class="catalog-list" <?if ($bUseSchema):?>itemscope itemtype="http://schema.org/ItemList"<?endif;?> >
			<div class="js_append ajax_load list grid-list <?=$gridClass?>">
	<?endif;?>
		<?foreach($arResult["ITEMS"] as $arItem){?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$item_id = $arItem["ID"];

			if (isset($arParams['ID_FOR_TABS']) && $arParams['ID_FOR_TABS'] == 'Y') {
				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID'])."_".$arParams["FILTER_HIT_PROP"];
			} else {
				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
			}

			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
			
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
			/* sku replace end */
			?>

			
			<?$itemDiscount = '';?>

			<div class="catalog-list__wrapper grid-list__item grid-list-border-outer">
				<?if (TSolution::isSaleMode()):?>
					<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>_<?=$arParams["FILTER_HIT_PROP"]?>" style="display: none;">
						<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])):?>
							<?foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo):?>
								<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE'];?>[<?=$propID;?>]" value="<?=htmlspecialcharsbx($propInfo['ID']);?>">
								<?
								if (isset($arItem['PRODUCT_PROPERTIES'][$propID])){
									unset($arItem['PRODUCT_PROPERTIES'][$propID]);
								}
								?>
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
													<?foreach($propInfo['VALUES'] as $valueID => $value):?>
														<label>
															<input class="form-control" type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]" value="<?=$valueID?>" <?=($valueID == $propInfo['SELECTED'] ? '"checked"' : '')?>><?=$value?>
														</label>
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
				
				<div class="catalog-list__item <?=$itemClass?>" id="<?=$arItem["strMainID"]?>">
					<?if ($arItem['SKU']['PROPS']):?>
						<template class="offers-template-json">
							<?=TSolution\SKU::getOfferTreeJson($arItem["SKU"]["OFFERS"])?>
						</template>
						<?$bUseSelectOffer = true;?>
					<?endif;?>
					<div class="catalog-list__inner flexbox flexbox--direction-row height-100" <?if ($bUseSchema):?>itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product"<?endif;?>>
						<?$arImgConfig = [
							'TYPE' => 'catalog_list',
							'ADDITIONAL_IMG_CLASS' => 'js-replace-img'
						];?>
						<div class="js-config-img" data-img-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arImgConfig, false, true))?>'></div>
						<?if ($bUseSchema):?>
							<?/*<meta itemprop="position" content="<?=(++$positionProduct)?>" />*/?>
							<meta itemprop="description" content="<?=htmlspecialcharsbx(strip_tags($arItem['PREVIEW_TEXT'] ?: $arItem['NAME']))?>" />
						<?endif;?>
						<?$arParams['SHOW_FAST_VIEW'] = 'N';?>
						<?=TSolution\Product\Image::showImage(
							array_merge(
								[
									'ITEM' => $arItem,
									'PARAMS' => $arParams,
									'STICKY' => true,
									'CONTENT_TOP' => $itemDiscount,
								],
								$arImgConfig
							)
						)?>

						<?if ($bUseSchema):?>
							<meta itemprop="name" content="<?=$arItem['NAME']?>">
							<link itemprop="url" href="<?=$arItem['DETAIL_PAGE_URL']?>">
						<?endif;?>
						<div 
							class="catalog-list__info flex-1 flexbox flexbox--direction-row" 
							data-id="<?=($arCurrentOffer ? $arCurrentOffer['ID'] : $arItem['ID'])?>"
							data-item="<?=$dataItem;?>"
							<?if ($bUseSchema):?>itemprop="offers" itemscope itemtype="http://schema.org/Offer"<?endif;?>
						>
							<div class="catalog-list__info-top">
								<div class="catalog-list__info-inner">
									<?// element title?>
									<div class="catalog-list__info-title linecamp-3 height-auto-t600 font_18 font_large font_14--to-600">
										<?if ($bUseSchema):?>
											<link itemprop="url" href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<?endif;?>
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link switcher-title js-popup-title color-theme-target"><span><?=$elementName;?></span></a>
									</div>
									<?if ($bShowRating || strlen($status) || strlen($article)):?>
										<div class="catalog-list__info-tech">
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
												<?if (strlen($status)):?>
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
									
									<?if ($arItem['PREVIEW_TEXT']):?>
										<div
										 class="catalog-list__info-text font_14 compact-hidden-t600"
										 itemprop="description"
										>
											<?=$arItem['PREVIEW_TEXT'];?>
										</div>
									<?endif;?>
									<?if ($arItem['PROPS'] || $arItem['OFFER_PROP']):?>
										<?$templateData['HAS_CHARACTERISTICS'] = true;?>
										<div class="properties properties--mt-13 js-offers-prop compact-hidden-t600">
											<?if ($arItem['PROPS']):?>
												<?foreach ($arItem["PROPS"] as $arProp):?>
													<?if (!empty( $arProp["VALUE"])):?>
														<div class="properties__item font_14 js-prop-replace">
															<span class="properties__title js-prop-title">
																<?=$arProp["NAME"]?>
																<?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?>
																	<div class="hint hint--down">
																		<span class="hint__icon rounded bg-theme-hover border-theme-hover bordered"><i>?</i></span>
																		<div class="tooltip"><?=$arProp["HINT"]?></div>
																	</div>
																<?endif;?>
															</span>
															<span class="properties__hr properties__item--inline">&mdash;</span>
															<span class="properties__value color_222 js-prop-value">
															<?
															if (count((array)$arProp["DISPLAY_VALUE"])>1) {
																foreach($arProp["DISPLAY_VALUE"] as $key => $value) { 
																	if ($arProp["DISPLAY_VALUE"][$key+1]) {
																		echo $value.", ";
																	} else {
																		echo $value;
																	}
																}
															} else { 
																echo $arProp["DISPLAY_VALUE"]; 
															}?>
															</span>
														</div>
													<?endif;?>
												<?endforeach;?>
											<?endif;?>
											<?if ($arItem['OFFER_PROP']):?>
												<?foreach ($arItem["OFFER_PROP"] as $arProp):?>
													<?if (!empty( $arProp["VALUE"])):?>
														<div class="properties__item font_14 js-prop">
															<span class="properties__title">
																<?=$arProp["NAME"]?>
																<?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?>
																	<div class="hint hint--down">
																		<span class="hint__icon rounded bg-theme-hover border-theme-hover bordered"><i>?</i></span>
																		<div class="tooltip"><?=$arProp["HINT"]?></div>
																	</div>
																<?endif;?>
															</span>
															<span class="properties__hr properties__item--inline">&mdash;</span>
															<span class="properties__value color_222">
															<?
															if (is_array($arProp["VALUE"]) && count($arProp["VALUE"])>1) {
																foreach ($arProp["VALUE"] as $key => $value) { 
																	if ($arProp["VALUE"][$key+1]) {
																		echo $value.", ";
																	} else {
																		echo $value;
																	}
																}
															} else { 
																echo $arProp["VALUE"]; 
															}?>
															</span>
														</div>
													<?endif;?>
												<?endforeach;?>
											<?endif;?>
										</div>
									<?endif;?>

									<?if ($arItem['SKU']['PROPS']):?>
										<div class="catalog-table__item-wrapper hide-600">
											<div 
											class="sku-props sku-props--list"
											data-site-id="<?=SITE_ID;?>"
											data-item-id="<?=$arItem['ID'];?>"
											data-iblockid="<?=$arItem['IBLOCK_ID'];?>"
											data-offer-id="<?=$arCurrentOffer['ID'];?>"
											data-offer-iblockid="<?=$arCurrentOffer['IBLOCK_ID'];?>"
											data-offers-id='<?=str_replace('\'', '"', CUtil::PhpToJSObject($GLOBALS[$arParams['FILTER_NAME']]['OFFERS_ID'], false, true))?>'
											>
											<?
											// echo "<pre>";
											// print_r($arItem['SKU']['PROPS']);
											// echo "</pre>";
											?>
												<div class="line-block line-block--flex-wrap line-block--24 line-block--align-normal">
													<?=TSolution\SKU\Template::showSkuPropsHtml($arItem['SKU']['PROPS'])?>
												</div>
											</div>
										</div>
									<?endif;?>
								</div>
							</div>
							<div class="catalog-list__info-bottom">
								<?// element price?>
								<?$arPriceConfig = [
									'PRICE_CODE' => $arParams['PRICE_CODE'],
									'PRICE_FONT' => '18 font_14--to-600',
								];?>
								<div class="js-popup-price" data-price-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arPriceConfig, false, true))?>'>
									<?=TSolution\Product\Price::show([
										'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arItem),
										'PARAMS' => $arParams,
										'SHOW_SCHEMA' => $bUseSchema,
										'BASKET' => $bOrderViewBasket,
										'PRICE_FONT' => '18 font_14--to-600',
									]);?>
								</div>
								<?// element btns?>
								<?$arBtnConfig = [
									'BASKET_URL' => $basketURL,
									'BASKET' => $bOrderViewBasket,
									'ORDER_BTN' => $bOrderButton,
									'BTN_CLASS' => 'btn-wide',
									'QUESTION_BTN' => $arItem['PROPERTIES']['FORM_QUESTION']['VALUE'] == 'Y',
									'ONE_CLICK_BUY' => false,
									'BTN_IN_CART_CLASS' => 'btn-wide',
									'BTN_ORDER_CLASS' => 'btn-wide btn-transparent-border',
									'SHOW_COUNTER' => false,
									'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
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
										'TOTAL_COUNT' => $totalCount,
									]
								));?>
								<?//if ($arBasketConfig['HTML']):?>
								<?if ($bShowCompare 
									|| $bShowFavorit 
									|| ($arCurrentOffer
										|| (!$arCurrentOffer && $arBasketConfig['HTML'])
									)
								):?>
									<div class="catalog-list__info-bottom-btns">
										<div class="line-block line-block--8 line-block--8-vertical flexbox--wrap flexbox--justify-center">
											<div class="line-block__item js-btn-state-wrapper  <?=($arCurrentOffer ? 'hide-600' : '');?> <?=(!$arBasketConfig['HTML'] ? ' hidden' : '');?>">
												<div class="js-replace-btns js-config-btns" data-btn-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arBtnConfig, false, true))?>'>
													<?=$arBasketConfig['HTML']?>
												</div>
											</div>
											<?if ($arCurrentOffer):?>
												<div class="visible-600 line-block__item flex-1">
													<?=TSolution\Product\Basket::getMoreButton([
														'ITEM' => $arCurrentOffer
													]);?>
												</div>
											<?endif;?>
											<?if ($bShowCompare || $bShowFavorit):?>
												<div class="line-block__item js-replace-icons">
													<?if ($bShowFavorit):?>
														<?=\TSolution\Product\Common::getActionIcon([
															'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arItem),
															'PARAMS' => $arParams,
														])?>
													<?endif;?>
													<?if ($bShowCompare):?>
														<?=\TSolution\Product\Common::getActionIcon([
															'ITEM' => (($arCurrentOffer && \TSolution::isSaleMode()) ? $arCurrentOffer : $arItem),
															'PARAMS' => $arParams,
															'TYPE' => 'compare',
															'SVG_SIZE' => ['WIDTH' => 20,'HEIGHT' => 16],
														])?>
													<?endif;?>
												</div>
											<?endif;?>
										</div>
									</div>
								<?endif;?>
								<?//hint?>
								<?if ($arItem['INCLUDE_TEXT']):?>
									<div class="block-with-icon block-with-icon--mt-14">
										<?=TSolution::showIconSvg("icon block-with-icon__icon", SITE_TEMPLATE_PATH.'/images/svg/catalog/info_big.svg', '', '', true, false);?>
										<div class="block-with-icon__text color_666 font_13">
											<?=$arItem['INCLUDE_TEXT'];?>
										</div>
									</div>
								<?endif;?>
							</div>
						</div>

					</div>
				</div>

			</div>
		<?}?>

		<?if ($bHasBottomPager && $bMobileScrolledItems):?>
			<?if($bAjax):?>
				<div class="wrap_nav bottom_nav_wrapper">
			<?endif;?>

				<?$bHasNav = (strpos($arResult["NAV_STRING"], 'more_text_ajax') !== false);?>
				<div class="bottom_nav mobile_slider <?=($bHasNav ? '' : ' hidden-nav');?>" data-parent=".catalog-list" data-append=".grid-list" <?=($bAjax ? "style='display: none; '" : "");?>>
					<?=$arResult["NAV_STRING"]?>
				</div>

			<?if($bAjax):?>
				</div>
			<?endif;?>
		<?endif;?>

	<?if(!$bAjax):?>
			</div> <?//.js_append ajax_load block grid-list?>
	<?endif;?>

		<?if($bAjax):?>
			<div class="wrap_nav bottom_nav_wrapper">
		<?endif;?>

		<?if($arParams['HEADING_COUNT_ELEMENTS'] == 'Y'):?>
			<?if((int)$arResult['NAV_RESULT']->NavRecordCount > 0):?>
				<?$this->SetViewTarget("more_text_title");?>
					<span class="element-count-wrapper"><span class="element-count color_999 rounded-4"><?=TSolution\Functions::declOfNum($arResult['NAV_RESULT']->NavRecordCount, [Loc::getMessage('ONE_ITEM'), Loc::getMessage('TWO_ITEM'), Loc::getMessage('FIVE_ITEM')]);?></span></span>
				<?$this->EndViewTarget();?>
			<?endif;?>
		<?endif;?>

		<div class="bottom_nav_wrapper nav-compact">
			<div class="bottom_nav <?=($bMobileScrolledItems ? 'hide-600' : '');?>" <?=($arParams['AJAX_REQUEST'] == "Y" ? "style='display: none; '" : "");?> data-count="<?=$arResult['NAV_RESULT']->NavRecordCount;?>" data-parent=".catalog-list" data-append=".grid-list">
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

	<script>
		if (typeof initCountdown === "function") initCountdown();
	</script>
	<?if($bUseSelectOffer):?>
		<script>typeof useOfferSelect === 'function' && useOfferSelect()</script>
	<?endif;?>
<?elseif($arParams['IS_CATALOG_PAGE'] == 'Y'):?>
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