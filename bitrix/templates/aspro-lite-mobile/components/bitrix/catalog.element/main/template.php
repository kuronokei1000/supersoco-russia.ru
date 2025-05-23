<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

global $arTheme;
use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Loader,
	\Bitrix\Main\Web\Json;

$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = TSolution::GetFrontParametrValue('BASKET_PAGE_URL') ?? SITE_DIR.'basket/';
$dataItem = TSolution::getDataItem($arResult);
$bOrderButton = $arResult['PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES';
$bAskButton = $arResult['PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES';
$bOcbButton = $arParams['SHOW_ONE_CLINK_BUY'] != 'N' && $bOrderViewBasket;
$bGallerythumbVertical = $arParams['GALLERY_THUMB_POSITION'] === 'vertical';
$cntVisibleChars = intval($arParams['VISIBLE_PROP_COUNT']) > 0 ? intval($arParams['VISIBLE_PROP_COUNT']) : 6;

$bShowRating = $arParams['SHOW_RATING'] == 'Y';
$bShowCompare = $arParams['DISPLAY_COMPARE'] == 'Y';
$bShowFavorit = $arParams['SHOW_FAVORITE'] == 'Y';
$bUseShare = $arParams['USE_SHARE'] == 'Y';
$bShowSendGift = $arParams['SHOW_SEND_GIFT'] === 'Y';
$bShowCheaperForm = $arParams['SHOW_CHEAPER_FORM'] === 'Y';
$bShowReview = $arParams['SHOW_REVIEW'] !== 'N';
$bPopupVideo = !!$arResult['POPUP_VIDEO'];
$bShowCalculateDelivery = $arParams["CALCULATE_DELIVERY"] === 'Y';

$templateData["USE_OFFERS_SELECT"] = false;

// $topGalleryClassList = " detail-gallery-big--".$arResult['GALLERY_SIZE'];
$topGalleryClassList = " detail-gallery-big--".($bGallerythumbVertical ? 'vertical' : 'horizontal');
if ($bPopupVideo) {
	$topGalleryClassList .= " detail-gallery-big--with-video";
}

$arSkuTemplateData = [];
$bSKU2 = $arParams['TYPE_SKU'] === 'TYPE_2';
$bShowSkuProps = !$bSKU2;

$arSKUSetsData = [];
if ($arResult['SKU']['SKU_GROUP']) {
	$arSKUSetsData = [
		'IBLOCK_ID' => $arResult['SKU']['CURRENT']['IBLOCK_ID'],
		'ITEMS' => $arResult['SKU']['SKU_GROUP_VALUES'],
		'CURRENT_ID' => $arResult['SKU']['CURRENT']['ID']
	];
}

$bCrossAssociated = isset($arParams["CROSS_LINK_ITEMS"]["ASSOCIATED"]["VALUE"]) && !empty($arParams["CROSS_LINK_ITEMS"]["ASSOCIATED"]["VALUE"]);
$bCrossExpandables = isset($arParams["CROSS_LINK_ITEMS"]["EXPANDABLES"]["VALUE"]) && !empty($arParams["CROSS_LINK_ITEMS"]["EXPANDABLES"]["VALUE"]);

if (TSolution::isSaleMode()) {
	$arDiscount = TSolution\Product\Price::getDiscountByItemID($arResult['ID']);
	$discountDateTo = $arDiscount ? $arDiscount['ACTIVE_TO'] : '';
} else {
	$discountDateTo = $arResult['DISPLAY_PROPERTIES']['DATE_COUNTER']['VALUE'];
}

/*set array props for component_epilog*/
$templateData = array(
	'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
	'INCLUDE_FOLDER_PATH' => $arResult['INCLUDE_FOLDER_PATH'],
	'ORDER' => $bOrderViewBasket,
	'TIZERS' => array(
		'IBLOCK_ID' => $arParams['IBLOCK_TIZERS_ID'],
		'VALUE' => $arResult['TIZERS'],
	),
	'SALE' => TSolution\Functions::getCrossLinkedItems($arResult, array('LINK_SALE'), array('LINK_GOODS', 'LINK_GOODS_FILTER')),
	'ARTICLES' => TSolution\Functions::getCrossLinkedItems($arResult, array('LINK_ARTICLES'), array('LINK_GOODS', 'LINK_GOODS_FILTER')),
	'SERVICES' => TSolution\Functions::getCrossLinkedItems($arResult, array('SERVICES'), array('LINK_GOODS', 'LINK_GOODS_FILTER')),
	'FAQ' => TSolution\Functions::getCrossLinkedItems($arResult, array('LINK_FAQ')),
	'ASSOCIATED' => $arParams["USE_ASSOCIATED_CROSS"] ? [] : TSolution\Functions::getCrossLinkedItems($arResult, array('ASSOCIATED', 'ASSOCIATED_FILTER')),
	'EXPANDABLES' => $arParams["USE_EXPANDABLES_CROSS"] ? [] : TSolution\Functions::getCrossLinkedItems($arResult, array('EXPANDABLES', 'EXPANDABLES_FILTER')),
	'CATALOG_SETS' => [
		'SET_ITEMS' => $arResult['SET_ITEMS'],
		'SKU_SETS' => $arSKUSetsData,
	],
	'POPUP_VIDEO' => $bPopupVideo,
	'RATING' => floatval($arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING'] ? $arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] : 0),
	'USE_SHARE' => $arParams['USE_SHARE'] === 'Y',
	'SHOW_REVIEW' => $bShowReview,
	'CALCULATE_DELIVERY' => $bShowCalculateDelivery,
	'BRAND' => $arResult['BRAND_ITEM'],
);
?>

<?if (TSolution::isSaleMode()):?>
	<div class="basket_props_block" id="bx_basket_div_<?=$arResult["ID"];?>" style="display: none;">
		<?if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])):?>
			<?foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo):?>
				<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?=$propID;?>]" value="<?=htmlspecialcharsbx($propInfo['ID']);?>">
				<?
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID])){
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
				}
				?>
			<?endforeach;?>
		<?endif;?>
		<?if ($arResult['PRODUCT_PROPERTIES']):?>
			<div class="wrapper">
				<?foreach($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo):?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group fill-animate">
								<?if(
									'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 
									'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
								):?>
									<label class="font_14"><span><?=$arResult['PROPERTIES'][$propID]['NAME']?></span></label>
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
									<label class="font_14"><span><?=$arResult['PROPERTIES'][$propID]['NAME']?></span></label>
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

<?// top banner?>
<?$templateData['SECTION_BNR_CONTENT'] = isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES';?>
<?if($templateData['SECTION_BNR_CONTENT']):?>
	<?
	$templateData['SECTION_BNR_UNDER_HEADER'] = $arResult['PROPERTIES']['BNR_TOP_UNDER_HEADER']['VALUE_XML_ID'];
	$templateData['SECTION_BNR_COLOR'] = $arResult['PROPERTIES']['BNR_TOP_COLOR']['VALUE_XML_ID'];
	$atrTitle = $arResult['PROPERTIES']['BNR_TOP_BG']['DESCRIPTION'] ?: $arResult['PROPERTIES']['BNR_TOP_BG']['TITLE'] ?: $arResult['NAME'];
	$atrAlt = $arResult['PROPERTIES']['BNR_TOP_BG']['DESCRIPTION'] ?: $arResult['PROPERTIES']['BNR_TOP_BG']['ALT'] ?: $arResult['NAME'];

	//buttons
	$bannerButtons = [
		[
			'TITLE' => $arResult['PROPERTIES']['BUTTON1TEXT']['VALUE'] ?? '',
			'CLASS' => 'btn choise '.($arResult['PROPERTIES']['BUTTON1CLASS']['VALUE_XML_ID'] ?? 'btn-default').' '.($arResult['PROPERTIES']['BUTTON1COLOR']['VALUE_XML_ID'] ?? ''),
			'ATTR' => [
				($arResult['PROPERTIES']['BUTTON1TARGET']['VALUE_XML_ID'] === 'scroll' || !$arResult['PROPERTIES']['BUTTON1TARGET']['VALUE_XML_ID']
					? 'data-block=".right_block .detail"'
					: 'target="'.$arResult['PROPERTIES']['BUTTON1TARGET']['VALUE_XML_ID'].'"')
			],
			'LINK' => $arResult['PROPERTIES']['BUTTON1LINK']['VALUE'],
			'TYPE' => $arResult['PROPERTIES']['BUTTON1TARGET']['VALUE_XML_ID'] === 'scroll' || !$arResult['PROPERTIES']['BUTTON1TARGET']['VALUE_XML_ID']
				? 'anchor'
				: 'link'
		]
	];

	if( $arResult['PROPERTIES']['BUTTON2TEXT']['VALUE'] && $arResult['PROPERTIES']['BUTTON2LINK']['VALUE'] ){
		$bannerButtons[] = [
			'TITLE' => $arResult['PROPERTIES']['BUTTON2TEXT']['VALUE'],
			'CLASS' => 'btn choise '.($arResult['PROPERTIES']['BUTTON2CLASS']['VALUE_XML_ID'] ?? 'btn-default').' '.($arResult['PROPERTIES']['BUTTON2COLOR']['VALUE_XML_ID'] ?? ''),
			'ATTR' => [
				($arResult['PROPERTIES']['BUTTON2TARGET']['VALUE_XML_ID'] ? 'target="'.$arResult['PROPERTIES']['BUTTON2TARGET']['VALUE_XML_ID'].'"' : '')
			],
			'LINK' => $arResult['PROPERTIES']['BUTTON2LINK']['VALUE'],
			'TYPE' => 'link',
		];
	}
	?>
	<?$this->SetViewTarget('section_bnr_content');?>
		<?TSolution\Functions::showBlockHtml(array(
			'FILE' => '/images/detail_banner.php',
			'PARAMS' => array(
				'TITLE' => $arResult['NAME'],
				'COLOR' => $templateData['SECTION_BNR_COLOR'],
				'TEXT' => array(
					'TOP' => $arResult['SECTION'] ? reset($arResult['SECTION']['PATH'])['NAME'] : '',
					'PREVIEW' => array(
						'TYPE' => $arResult['PREVIEW_TEXT_TYPE'],
						'VALUE' => $arResult['PREVIEW_TEXT'],
					)
				),
				'PICTURES' => array(
					'BG' => CFile::GetFileArray($arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']),
					'IMG' => CFile::GetFileArray($arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']),
				),
				'BUTTONS' => $bannerButtons,
				'ATTR' => array(
					'ALT' => $atrAlt,
					'TITLE' => $atrTitle,
				),
				'TOP_IMG' => $bTopImg
			),
		));?>
	<?$this->EndViewTarget();?>
<?endif;?>

<?
$article = $arResult['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'];

//unset($arResult['OFFERS']); // get correct totalCount
$totalCount = TSolution\Product\Quantity::getTotalCount([
	'ITEM' => $arResult, 
	'PARAMS' => $arParams
]);
$arStatus = TSolution\Product\Quantity::getStatus([
	'ITEM' => $arResult, 
	'PARAMS' => $arParams,
	'TOTAL_COUNT' => $totalCount,
	'IS_DETAIL' => true,
]);

/* sku replace start */
$arCurrentOffer = $arResult['SKU']['CURRENT'];
$elementName = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME'];
$bShowSelectOffer = $arCurrentOffer && $bShowSkuProps;

if ($bShowSelectOffer) {
	$arResult['PARENT_IMG'] = '';
	if ($arResult['PREVIEW_PICTURE']) {
		$arResult['PARENT_IMG'] = $arResult['PREVIEW_PICTURE'];
	} elseif ($arResult['DETAIL_PICTURE']) {
		$arResult['PARENT_IMG'] = $arResult['DETAIL_PICTURE'];
	}

	$arResult['DETAIL_PAGE_URL'] = $arCurrentOffer['DETAIL_PAGE_URL'];

	if ($arParams['SHOW_GALLERY'] === 'Y') {
		if(!$arCurrentOffer["DETAIL_PICTURE"] && $arCurrentOffer["PREVIEW_PICTURE"])
			$arCurrentOffer["DETAIL_PICTURE"] = $arCurrentOffer["PREVIEW_PICTURE"];

		$arOfferGallery = TSolution\Functions::getSliderForItem([
			'TYPE' => 'catalog_block',
			'PROP_CODE' => $arParams['OFFER_ADD_PICT_PROP'],
			// 'ADD_DETAIL_SLIDER' => false,
			'ITEM' => $arCurrentOffer,
			'PARAMS' => $arParams,
		]);
		if ($arOfferGallery) {
			$arResult['GALLERY'] = array_merge($arOfferGallery, $arResult['GALLERY']);
		}
	} else {
		if ($arCurrentOffer['PREVIEW_PICTURE'] || $arCurrentOffer['DETAIL_PICTURE']) {
			if ($arCurrentOffer['PREVIEW_PICTURE']) {
				$arResult['PREVIEW_PICTURE'] = $arCurrentOffer['PREVIEW_PICTURE'];
			} elseif ($arCurrentOffer['DETAIL_PICTURE']) {
				$arResult['PREVIEW_PICTURE'] = $arCurrentOffer['DETAIL_PICTURE'];
			}
		}
	}
	if (!$arCurrentOffer['PREVIEW_PICTURE'] && !$arCurrentOffer['DETAIL_PICTURE']) {
		if ($arResult['PREVIEW_PICTURE']) {
			$arCurrentOffer['PREVIEW_PICTURE'] = $arResult['PREVIEW_PICTURE'];
		} elseif ($arResult['DETAIL_PICTURE']) {
			$arCurrentOffer['PREVIEW_PICTURE'] = $arResult['DETAIL_PICTURE'];
		}
	}

	if ($arCurrentOffer["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"] || $arCurrentOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) {
		$article = $arCurrentOffer['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'] ?? $arCurrentOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"];
	}

	$arResult["DISPLAY_PROPERTIES"]["FORM_ORDER"] = $arCurrentOffer["DISPLAY_PROPERTIES"]["FORM_ORDER"];
	$arResult["DISPLAY_PROPERTIES"]["PRICE"] = $arCurrentOffer["DISPLAY_PROPERTIES"]["PRICE"];
	$arResult["NAME"] = $arCurrentOffer["NAME"];
	$elementName = $arCurrentOffer["NAME"];

	$arResult['OFFER_PROP'] = TSolution::PrepareItemProps($arCurrentOffer['DISPLAY_PROPERTIES']);
	
	$dataItem = TSolution::getDataItem($arCurrentOffer);

	$totalCount = TSolution\Product\Quantity::getTotalCount([
		'ITEM' => $arCurrentOffer, 
		'PARAMS' => $arParams
	]);
	$arStatus = TSolution\Product\Quantity::getStatus([
		'ITEM' => $arCurrentOffer, 
		'PARAMS' => $arParams,
		'TOTAL_COUNT' => $totalCount,
		'IS_DETAIL' => true,
	]);
}

$status = $arStatus['NAME'];
$statusCode = $arStatus['CODE'];
/* sku replace end */
?>

<?// detail description?>
<?$templateData['DETAIL_TEXT'] = boolval(strlen($arResult['DETAIL_TEXT']));?>
<?if(strlen($arResult['DETAIL_TEXT'])):?>
	<?$this->SetViewTarget('PRODUCT_DETAIL_TEXT_INFO');?>
		<div class="content content--max-width" itemprop="description">
			<?=$arResult['DETAIL_TEXT'];?>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?// props content?>
<?$templateData['CHARACTERISTICS'] = boolval($arResult['CHARACTERISTICS']);?>
<?$templateData['HAS_CHARACTERISTICS'] = boolval($arResult['CHARACTERISTICS']);?>
<?if($arResult['CHARACTERISTICS']):?>
	<?$this->SetViewTarget('PRODUCT_PROPS_INFO');?>
	<?TSolution\Functions::showBlockHtml([
		'FILE' => '/chars.php',
		'PARENT_COMPONENT' => $this->getComponent(),
		'PARAMS' => [
			'GRUPPER_PROPS' => $arParams['GRUPPER_PROPS'],
			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
			'IBLOCK_TYPE' => $arResult['IBLOCK_TYPE'],
			'CHARACTERISTICS' => $arResult['CHARACTERISTICS'],
			'SKU_IBLOCK_ID' => $arParams['SKU_IBLOCK_ID'],
			'OFFER_PROP' => $arResult['OFFER_PROP'],
			'SHOW_HINTS' => $arParams['SHOW_HINTS'],
			'PROPERTIES_DISPLAY_TYPE' => $arParams['PROPERTIES_DISPLAY_TYPE'],
		],
	]);?>
	<?$this->EndViewTarget();?>
<?endif;?>

<?// files?>
<?$templateData['DOCUMENTS'] = boolval($arResult['DOCUMENTS']);?>
<?if($templateData['DOCUMENTS']):?>
	<?$this->SetViewTarget('PRODUCT_FILES_INFO');?>
		<div class="doc-list-inner doc-list-inner--view-list">
			<div class="doc-list-inner__list  grid-list grid-list--rounded grid-list--items-2-768 grid-list--no-gap grid-list--gap-column-24">
				<?foreach($arResult['DOCUMENTS'] as $arItem):?>
					<?
					$arDocFile = TSolution::GetFileInfo($arItem);
					$docFileDescr = $arDocFile['DESCRIPTION'] ?? '';
					$docFileSize = $arDocFile['FILE_SIZE_FORMAT'];
					$docFileType = $arDocFile['TYPE'];
					$bDocImage = false;
					if ($docFileType == 'jpg' || $docFileType == 'jpeg' || $docFileType == 'bmp' || $docFileType == 'gif' || $docFileType == 'png') {
						$bDocImage = true;
					}
					?>
					<div class="doc-list-inner__wrapper grid-list__item grid-list__item--rounded colored_theme_hover_bg-block grid-list-border-outer fill-theme-parent-all">
						<div class="doc-list-inner__item height-100 shadow-hovered shadow-no-border-hovered">
							<?if($arDocFile):?>
								<div class="doc-list-inner__icon-wrapper">
									<a class="file-type doc-list-inner__icon">
										<i class="file-type__icon file-type__icon--<?=$docFileType?>"></i>
									</a>
								</div>
							<?endif;?>
							<div class="doc-list-inner__content-wrapper<?=($bDocImage ? ' doc-list-inner__content--with-icon' : '');?>">
								<div class="doc-list-inner__top">
									<?if($arDocFile):?>
										<?if($bDocImage):?>
											<a href="<?=$arDocFile['SRC']?>" class="doc-list-inner__name fancy dark_link color-theme-target switcher-title" data-caption="<?=htmlspecialchars($docFileDescr)?>"><?=$docFileDescr?></a>
										<?else:?>
											<a href="<?=$arDocFile['SRC']?>" target="_blank" class="doc-list-inner__name dark_link color-theme-target switcher-title" title="<?=htmlspecialchars($docFileDescr)?>">
												<?=$docFileDescr?>
											</a>
										<?endif;?>
										<div class="doc-list-inner__label"><?=$docFileSize?></div>
									<?else:?>
										<div class="doc-list-inner__name switcher-title"><?=$docFileDescr?></div>
									<?endif;?>
									<? if ($arDocFile): ?>
										<? if ($bDocImage) : ?>
											<a class="doc-list-inner__icon-preview-image doc-list-inner__link-file  fancy fill-theme-parent"
												data-caption="<?= htmlspecialchars($docFileDescr) ?>"
												href="<?= $arDocFile['SRC'] ?>">
												<?=\TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/documents_icons.svg#image-preview-18-16', 'image-preview fill-theme-target fill-dark-light-block', ['WIDTH' => 18,'HEIGHT' => 18]); ?>
											</a>
										<? else : ?>
											<a class="doc-list-inner__icon-preview-image doc-list-inner__link-file  fill-theme-parent"
												target="_blank"
												href="<?= $arDocFile['SRC'] ?>">
												<?=\TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/documents_icons.svg#file-download-18-16', 'image-preview fill-theme-target fill-dark-light-block', ['WIDTH' => 18,'HEIGHT' => 18]);?>
											</a>
										<? endif ?>
									<? endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?endforeach;?>
			</div>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?// big gallery?>
<?$templateData['BIG_GALLERY'] = boolval($arResult['BIG_GALLERY']);?>
<?if($arResult['BIG_GALLERY']):?>
	<?$this->SetViewTarget('PRODUCT_BIG_GALLERY_INFO');?>
		<?
		$arGallery = array_map(function($array){
			return [
				'src' => $array['DETAIL']['SRC'],
				'preview' => $array['PREVIEW']['src'],
				'alt' => $array['ALT'],
				'title' => $array['TITLE']
			];
		}, $arResult['BIG_GALLERY']);
		?>
		<?= TSolution\Functions::showGallery($arGallery, [
			'CONTAINER_CLASS' => 'gallery-detail font_24 grid-list--gap-8',
		]); ?>
	<?$this->EndViewTarget();?>
<?endif;?>

<?// video?>
<?$templateData['VIDEO'] = boolval($arResult['VIDEO']);
$bOneVideo = count((array)$arResult['VIDEO']) == 1;
?>
<?if($arResult['VIDEO']):?>
	<?$this->SetViewTarget('PRODUCT_VIDEO_INFO');?>
		<?TSolution\Functions::showBlockHtml([
			'FILE' => 'video/detail_video_block.php',
			'PARAMS' => [
				'VIDEO' => $arResult['VIDEO'],
			],
		])?>
	<?$this->EndViewTarget();?>
<?endif;?>

<?// ask question?>
<?if($bAskButton):?>
	<?if($arParams['LEFT_BLOCK_CATALOG_DETAIL'] === 'N'):?>
		<?$this->SetViewTarget('PRODUCT_SIDE_INFO');?>
	<?else:?>
		<?$this->SetViewTarget('under_sidebar_content');?>
	<?endif;?>
		<div class="ask-block bordered rounded-4">
			<div class="ask-block__container">
				<div class="ask-block__icon">
					<?=TSolution::showIconSvg('ask colored', SITE_TEMPLATE_PATH.'/images/svg/Question_lg.svg');?>
				</div>
				<div class="ask-block__text text-block color_666 font_14">
					<?=$arResult['INCLUDE_ASK']?>
				</div>
				<div class="ask-block__button">
					<div class="btn btn-default btn-transparent-bg animate-load" data-event="jqm" data-param-id="<?=TSolution::getFormID(VENDOR_PARTNER_NAME."_".VENDOR_SOLUTION_NAME."_question");?>" data-autoload-need_product="<?=TSolution::formatJsName($arResult['NAME'])?>" data-name="question">
						<span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span>
					</div>
				</div>
			</div>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?
/* gifts */
if ($arParams['USE_GIFTS_DETAIL'] === 'Y') {
	$templateData['GIFTS'] = [
		'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
		'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
		'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
		'POTENTIAL_PRODUCT_TO_BUY' => [
			'ID' => $arResult['ID'],
			'MODULE' => $arResult['MODULE'] ?? 'catalog',
			'PRODUCT_PROVIDER_CLASS' => $arResult['PRODUCT_PROVIDER_CLASS'] ?? 'CCatalogProductProvider',
			'QUANTITY' => $arResult['QUANTITY'] ?? '',
			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
	
			'PRIMARY_OFFER_ID' => $arResult['OFFERS'][0]['ID'] ?? '',
			'SECTION' => [
				'ID' =>  $arResult['SECTION']['ID'] ?? '',
				'IBLOCK_ID' =>  $arResult['SECTION']['IBLOCK_ID'] ?? '',
				'LEFT_MARGIN' =>  $arResult['SECTION']['LEFT_MARGIN'] ?? '',
				'RIGHT_MARGIN' =>  $arResult['SECTION']['RIGHT_MARGIN'] ?? '',
			],
		]
	];
}
?>


<div class="catalog-detail__top-info rounded-4 flexbox flexbox--direction-row flexbox--wrap-nowrap">
	<?
	// add to viewed
	TSolution\Product\Common::addViewed([
		'ITEM' => $arCurrentOffer ?: $arResult
	]);
	?>

	<?// meta?>
	<meta itemprop="name" content="<?=$name = strip_tags($elementName)?>" />
	<link itemprop="url" href="<?=$arResult['DETAIL_PAGE_URL']?>" />
	<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
	<meta itemprop="description" content="<?=(strlen(strip_tags($arResult['PREVIEW_TEXT'])) ? strip_tags($arResult['PREVIEW_TEXT']) : (strlen(strip_tags($arResult['DETAIL_TEXT'])) ? strip_tags($arResult['DETAIL_TEXT']) : $name))?>" />
	<meta itemprop="sku" content="<?=$arResult['ID'];?>" />

	<?if ($arResult['SKU_CONFIG']):?><div class="js-sku-config" data-value='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arResult['SKU_CONFIG'], false, true))?>'></div><?endif;?>
	<?if ($arResult['SKU']['PROPS']):?>
		<template class="offers-template-json">
			<?=TSolution\SKU::getOfferTreeJson($arResult["SKU"]["OFFERS"])?>
		</template>
		<?$templateData["USE_OFFERS_SELECT"] = true;?>
	<?endif;?>

	<?//discount counter?>
	<?$itemDiscount = '';?>

	<div class="detail-gallery-big<?= $topGalleryClassList; ?> swipeignore image-list__link">
		<div class="sticky-block">
			<div class="detail-gallery-big-wrapper">
				<?
				$countPhoto = count($arResult['GALLERY']);
				$arFirstPhoto = reset($arResult['GALLERY']);
				$urlFirstPhoto = $arFirstPhoto['BIG']['src'] ? $arFirstPhoto['BIG']['src'] : $arFirstPhoto['SRC'];
				?>
				<link href="<?=$urlFirstPhoto?>" itemprop="image"/>
				<?
				$gallerySetting = [
					'MAIN' => [
						'SLIDE_CLASS_LIST' => 'detail-gallery-big__item detail-gallery-big__item--big swiper-slide',
						'PLUGIN_OPTIONS' => [
							'direction' => 'horizontal',
							'init' => false,
							'keyboard' => [
								'enabled' => true,
							],
							'loop' => false,
							'pagination' => [
								'enabled' => true,
								'el' => '.detail-gallery-big-slider-main .swiper-pagination',
							],
							'slidesPerView' => 1,
							'thumbs' => [
								'swiper' => '.gallery-slider-thumb',
							],
							'type' => 'detail_gallery_main',
							"preloadImages" => false,
							"lazy" => [
								"loadPrevNext" => true,
							],
						],
					],
					'THUMBS' => [
						'SLIDE_CLASS_LIST' => 'gallery__item gallery__item--thumb swiper-slide rounded-x pointer',
						'PLUGIN_OPTIONS' => [
							'direction' => ($bGallerythumbVertical ? 'vertical' : 'horizontal'),
							'init' => false,
							'loop' => false,
							'pagination' => false,
							'slidesPerView' => 'auto',
							'type' => 'detail_gallery_thumb',
							'watchSlidesProgress' => true,
							"preloadImages" => false,
							"lazy" => [
								"loadPrevNext" => true,
							],
						],
					]
				];
				?>
				<div class="gallery-wrapper__aspect-ratio-container">
					<? // thumb gallery ?>
					<? if (isset($gallerySetting['THUMBS']) && $countPhoto || $bPopupVideo || $bShowSelectOffer): ?>
						<div class="detail-gallery-big-slider-thumbs">
							<? if (isset($gallerySetting['THUMBS']) && $countPhoto || $bShowSelectOffer): ?>
							<div class="gallery-slider-thumb__container<?= $bPopupVideo ? ' gallery-slider-thumb__container--with-popup' : ''; ?>">
								<div class="gallery-slider-thumb-button gallery-slider-thumb-button--prev slider-nav swiper-button-prev" style="display: none">
									<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/arrows.svg#left-7-12', 'stroke-dark-light', [
										'WIDTH' => 7, 
										'HEIGHT' => 12
									]); ?>
								</div>
								
								<div class="gallery-slider-thumb js-detail-img-thumb swiper slider-solution gallery-slider-thumb__container--hide-navigation"
									data-size="<?= $countPhoto; ?>"
									data-slide-class-list="<?= $gallerySetting['THUMBS']['SLIDE_CLASS_LIST']; ?>"
									<? if (isset($gallerySetting['THUMBS']['PLUGIN_OPTIONS']) && count($gallerySetting['THUMBS']['PLUGIN_OPTIONS'])): ?>
									data-plugin-options='<?= Json::encode($gallerySetting['THUMBS']['PLUGIN_OPTIONS']); ?>'
									<? endif; ?>
								>
									<div class="gallery__thumb-wrapper thumb swiper-wrapper" >
										<? if ($countPhoto > 1): ?>
											<? foreach ($arResult['GALLERY'] as $i => $arImage): ?>
												<?
												$alt = $arImage['ALT'];
												$title = $arImage['TITLE'];
												$url = $arImage['SMALL']['src'] ? $arImage['SMALL']['src'] : $arImage['SRC'];
												?>
												<div id="thumb-photo-<?=$i?>" class="<?= $gallerySetting['THUMBS']['SLIDE_CLASS_LIST']; ?>">
													<img class="gallery__picture rounded-x swiper-lazy" data-lazyload src="<?= $url; ?>" alt="<?= $alt; ?>" title="<?= $title; ?>"/>
												</div>
											<? endforeach; ?>
										<? endif; ?>
									</div>
								</div>

								<div class="gallery-slider-thumb-button gallery-slider-thumb-button--next slider-nav swiper-button-next" style="display: none">
									<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/arrows.svg#right-7-12', 'stroke-dark-light', [
										'WIDTH' => 7, 
										'HEIGHT' => 12
									]); ?>
								</div>
							</div>
							<? endif; ?>

							<? if($bPopupVideo): ?>
								<div class="video-block popup_video">
									<a class="video-block__play video-block__play--static video-block__play--sm bg-theme-after various video_link image dark-color" href="<?=$arResult['POPUP_VIDEO']?>" title="<?=Loc::getMessage("VIDEO")?>"><span class="play text-upper"><?=Loc::getMessage("VIDEO")?></span></a>
								</div>
							<? endif; ?>
						</div>
					<? endif; ?>

					<? // main gallery ?>
					<div class="detail-gallery-big-slider-main">
						<? TSolution\Product\Common::showStickers([
							'TYPE' => '',
							'ITEM' => $arResult,
							'PARAMS' => $arParams,
							'WRAPPER' => 'catalog-detail__sticker-wrapper',
							'CONTENT' => $itemDiscount,
						]); ?>
						<div class="detail-gallery-big-slider big js-detail-img swiper slider-solution slider-solution--show-nav-hover"
							data-slide-class-list="<?= $gallerySetting['MAIN']['SLIDE_CLASS_LIST']; ?>"
							<? if (isset($gallerySetting['MAIN']['PLUGIN_OPTIONS']) && count($gallerySetting['MAIN']['PLUGIN_OPTIONS'])): ?>
							data-plugin-options='<?= \Bitrix\Main\Web\Json::encode($gallerySetting['MAIN']['PLUGIN_OPTIONS']); ?>'
							<? endif; ?>
						>
							<? if ($countPhoto > 0): ?>
								<div class="detail-gallery-big-slider__wrapper swiper-wrapper">
									<? foreach ($arResult['GALLERY'] as $i => $arImage): ?>
										<?
											$alt = $arImage['ALT'];
											$title = $arImage['TITLE'];
											$url = $arImage['BIG']['src'] ? $arImage['BIG']['src'] : $arImage['SRC'];
										?>
										<div id="big-photo-<?=$i?>" class="<?= $gallerySetting['MAIN']['SLIDE_CLASS_LIST']; ?>">
											<a href="<?=$url?>" data-fancybox="gallery" class="detail-gallery-big__link popup_link fancy fancy-thumbs" title="<?= $title; ?>">
												<img class="detail-gallery-big__picture swiper-lazy" data-lazyload src="<?= $url; ?>" alt="<?= $alt; ?>" title="<?= $title; ?>" />
											</a>
										</div>
									<? endforeach; ?>
								</div>

							<? else: ?>
								<div class="detail-gallery-big-slider__wrapper swiper-wrapper">
									<div class="detail-gallery-big__item detail-gallery-big__item--big detail-gallery-big__item--no-image swiper-slide">
										<span class="detail-gallery-big__link">
											<img class="detail-gallery-big__picture" src="<?=SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg'?>" />
										</span>
									</div>
								</div>
							<? endif; ?>
						</div>

						<div class="swiper-pagination swiper-pagination--bottom visible-767 swiper-pagionation--line-to-600"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="catalog-detail__main">
		<?// element article?>
		<?if(strlen($article)):?>
			<div class="catalog-detail__main-article font_14 color_999" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
				<span class="article"><meta itemprop="name" content="<?=GetMessage('S_ARTICLE_FILL');?>"><?=GetMessage('S_ARTICLE')?>&nbsp;<span 
					class="js-replace-article"
					data-value="<?=$arResult['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE']?>"
					itemprop="value"
				><?=$article?></span></span>
			</div>
		<?endif;?>
		<div class="grid-list grid-list--gap-24">
			<div class="grid-list__item">
				<h1 class="font_32 switcher-title js-popup-title font_20--to-600"><?=$elementName;?></h1>
				<?if(
					strlen($article)
					|| $bShowRating
					|| $bShowCompare 
					|| $bShowFavorit
					|| $bUseShare
				):?>
					<div class="catalog-detail__info mt-16">
						<?if(
							strlen($article)
							|| $bShowCompare 
							|| $bShowFavorit
							|| $bUseShare
							|| $bShowRating
						):?>
							<div class="line-block line-block--20 flexbox--justify-beetwen flexbox--wrap">
								<div class="line-block__item">
									<?if (strlen($article)|| $bShowRating):?>
										<div class="catalog-detail__info-tech">
											<div class="line-block line-block--20 flexbox--wrap js-popup-info">
												<?// rating?>
												<?if ($bShowRating):?>
													<div class="line-block__item font_14 color_222">
														<?=\TSolution\Product\Common::getRatingHtml([
															'ITEM' => $arResult,
															'PARAMS' => $arParams,
															'SHOW_REVIEW_COUNT' => $bShowReview,
															'SVG_SIZE' => [
																'WIDTH' => 16,
																'HEIGHT' => 16,
															],
															'USE_SCHEMA' => true,
														])?>
													</div>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
								</div>

								<? if ($bShowCompare || $bShowFavorit || $bUseShare): ?>
									<div class="line-block__item ">
										<div class="flexbox flexbox--row flexbox--wrap">
											<?if (!$bSKU2):?>
												<div class="js-replace-icons">
													<? if ($bShowFavorit): ?>
														<?= \TSolution\Product\Common::getActionIcon([
															'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arResult),
															'PARAMS' => $arParams,
															'CLASS' => 'md',
														]); ?>
													<? endif; ?>
													
													<? if ($bShowCompare): ?>
														<?= \TSolution\Product\Common::getActionIcon([
															'ITEM' => (($arCurrentOffer && \TSolution::isSaleMode()) ? $arCurrentOffer : $arResult),
															'PARAMS' => $arParams,
															'TYPE' => 'compare',
															'CLASS' => 'md',
															'SVG_SIZE' => ['WIDTH' => 20,'HEIGHT' => 16],
														]); ?>
													<? endif; ?>
												</div>
											<?endif;?>
											
											<? if ($bUseShare): ?>
												<? \TSolution\Functions::showShareBlock([
													'INNER_CLASS' => 'item-action__inner item-action__inner--md item-action__inner--sm-to-600',
													'CLASS' => 'item-action item-action--horizontal',
												]);?>
											<? endif; ?>
										</div>
									</div>
								<? endif; ?>
							</div>
						<?endif;?>
					</div>
				<?endif;?>
			</div>
			<div class="grid-list__item">
				<?
				$arPriceConfig = [
					'PRICE_CODE' => $arParams['PRICE_CODE'],
					'PRICE_FONT' => 24,
					'PRICEOLD_FONT' => 16,
				];
				
				$priceHtml = TSolution\Product\Price::show([
					'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arResult),
					'PARAMS' => $arParams,
					'SHOW_SCHEMA' => true,
					'BASKET' => $bOrderViewBasket,
					'PRICE_FONT' => 24,
					'PRICEOLD_FONT' => 16,
					'RETURN' => true,
				]);
				?>

				<?ob_start();?>
				<? if ($bSKU2 && $arResult['HAS_SKU']): ?>
					<div class="catalog-detail__cart">
						<?= TSolution\Product\Basket::getAnchorButton([
							'BTN_NAME' => TSolution::GetFrontParametrValue('EXPRESSION_READ_MORE_OFFERS_DEFAULT'),
							'BLOCK' => 'sku',
						]); ?>
					</div>
				<? else: ?>
					<?
					$arBtnConfig = [
						'BASKET_URL' => $basketURL,
						'BASKET' => $bOrderViewBasket,
						'DETAIL_PAGE' => true,
						'ORDER_BTN' => $bOrderButton,
						'BTN_CLASS' => 'btn-wide btn-lg',
						'BTN_CLASS_MORE' => 'bg-theme-target border-theme-target btn-wide',
						'BTN_IN_CART_CLASS' => 'btn-wide btn-lg',
						'BTN_CALLBACK_CLASS' => 'btn-transparent-border',
						'BTN_ORDER_CLASS' => 'btn-wide btn-transparent-border',
						'SHOW_COUNTER' => false,
						'ONE_CLICK_BUY' => "N",
						'QUESTION_BTN' => $bAskButton,
						'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
						'CATALOG_IBLOCK_ID' => $arResult['IBLOCK_ID'],
						'ITEM_ID' => $arResult['ID'],
					];

					$arBasketConfig = TSolution\Product\Basket::getOptions(array_merge(
						$arBtnConfig, 
						[
							'ITEM' => ($arCurrentOffer ? $arCurrentOffer : $arResult),
							'PARAMS' => $arParams,
							'TOTAL_COUNT' => $totalCount,
						]
					));
					?>
						<?=$arBasketConfig['HTML']?>
				<? endif; ?>
				<?
				$btnHtml = trim(ob_get_contents());
				ob_end_clean();
				?>
				<?$arConfigOcb = [
					'ONE_CLICK_BUY' => $arBasketConfig['ACTION'] == 'ADD',
					'ITEM' => [
						'NAME' => $arCurrentOffer ? $arCurrentOffer['NAME'] : $arResult['NAME'],
					]
				];?>
				

				<div class="catalog-detail__buy-block outer-rounded-x <?=(($btnHtml || $priceHtml) ? '' : ' hidden')?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer" data-id="<?=$arResult['ID']?>" data-item="<?=$dataItem;?>">
					<div class="line-block line-block--20 line-block--align-normal flexbox--wrap flexbox--justify-beetwen<?=($priceHtml ? '' : ' hidden')?>">
						<div class="line-block__item catalog-detail__price catalog-detail__info--margined js-popup-price" data-price-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arPriceConfig, false, true))?>'>
							<?=$priceHtml?>
						</div>
					</div>	
					<div class="buy_block mt-16">
						<div class="catalog-detail__wrapper-cart flexbox flexbox--direction-row-reverse grid-list grid-list--gap-8">
							<? if ($bSKU2 && $arResult['HAS_SKU']) :?>
								<?=$btnHtml?>
							<? else: ?>
								<div class="catalog-detail__cart js-replace-btns js-config-btns<?=($btnHtml ? '' : ' hidden')?>" data-btn-config='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arBtnConfig, false, true))?>'>
									<?=$btnHtml?>
								</div>
									
								<a class="btn btn-default catalog-detail__link btn-lg" href="<?=$basketURL?>" title="<?=GetMessage('CATALOG_BUTTON_BUY')?>"><?=GetMessage('CATALOG_BUTTON_BUY')?></a>
							<? endif; ?>
						</div>
						<? if ($bOcbButton) {
							echo TSolution\Product\Basket::showOneClickBuyButton($arConfigOcb);
						}?>
					</div>	
					
					<?
					$offersScheme = new TSolution\Scheme\Offers([
						'ITEM' => $arResult,
						'DISCOUNT' => $discountDateTo,
					]);
					$offersScheme->show();
					?>
				</div>

				<? unset($arConfigOcb); ?>
			</div>

			<?if ($bShowSkuProps && $arResult['SKU']['PROPS']):?>
				<div class="grid-list__item catalog-detail__offers">
					<div 
					class="sku-props sku-props--detail"
					data-site-id="<?=SITE_ID;?>"
					data-item-id="<?=$arResult['ID'];?>"
					data-iblockid="<?=$arResult['IBLOCK_ID'];?>"
					data-offer-id="<?=$arCurrentOffer['ID'];?>"
					data-offer-iblockid="<?=$arCurrentOffer['IBLOCK_ID'];?>"
					data-offers-id='<?=str_replace('\'', '"', CUtil::PhpToJSObject($GLOBALS[$arParams['FILTER_NAME']]['OFFERS_ID'], false, true))?>'
					>
						<div class="line-block line-block--flex-wrap line-block--flex-100 line-block--40 line-block--align-flex-end">
							<?=TSolution\SKU\Template::showSkuPropsHtml($arResult['SKU']['PROPS'], [
								'HIDE_WITH_ONE_VALUE' => false
							])?>
							<? // table sizes ?>
							<? if ($arResult['SIZE_PATH']): ?>
								<div class="line-block__item">
									<div class="catalog-detail__pseudo-link catalog-detail__pseudo-link--with-gap table-sizes">
										<span class="font_13 fill-dark-light-block dark_link"
											data-event="jqm" 
											data-param-form_id="include_block" 
											data-param-url="<?= $arResult['SIZE_PATH']; ?>" 
											data-param-block_title="<?= urlencode(TSolution::formatJsName(GetMessage('TABLE_SIZES')));?>"
											data-name="include_block"
										>
											<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/catalog/item_icons.svg#table_sizes', '', [
												'WIDTH' => 18,
												'HEIGHT' => 11
											]); ?>
											<span class="dotted"><?= GetMessage("TABLES_SIZE"); ?></span>
										</span>
									</div>
								</div>
							<? endif; ?>
						</div>
					</div>
				</div>
			<?endif;?>

			<?if($arResult['CHARACTERISTICS']):?>
				<div class="grid-list__item flexbox flexbox--direction-row grid-list grid-list--gap-12 grid-list__item">
					<?if($arResult['CHARACTERISTICS']):?>
						<span class="catalog-detail__pseudo-link pointer dark_link font_13">
							<span class="choise dotted" data-block="char"><?=($arParams["T_CHARACTERISTICS"] ? $arParams["T_CHARACTERISTICS"] : Loc::getMessage("T_CHARACTERISTICS"));?></span>
						</span>
					<?endif;?>
										<?if(strlen($arResult['DETAIL_TEXT'])):?>
						<span class="catalog-detail__pseudo-link dark_link pointer font_13">
							<span class="choise dotted" data-block="desc"><?=Loc::getMessage('MORE_TEXT_BOTTOM')?></span>
						</span>
					<?endif;?>
				</div>
			<?endif;?>
            <div class="grid-list__item">
				<?if((strlen($status)) || $bShowCheaperForm || $bShowSendGift || trim(strip_tags($arResult['INCLUDE_CONTENT']))):?>
					<div class="grid-list--gap-12 catalog-detail__cell-block grid-list grid-list--items-1 outer-rounded-x shadow font_14">
						<?// status?>
						<? if (strlen($status)): ?>
							<div class="grid-list__item">
								<?if ($bUseSchema):?>
									<?= TSolution\Functions::showSchemaAvailabilityMeta($statusCode); ?>
								<?endif;?>
								<?= TSolution\Product\Common::showModalBlock([
									'NAME' => $status,
									'NAME_CLASS' => "js-replace-status status-icon ".($bSKU2 && $arResult['HAS_SKU'] ? "" : $statusCode),
									'SVG_PATH' => '/catalog/item_status_icons.svg#'.$statusCode,
									'SVG_SIZE' => ['WIDTH' => 16, 'HEIGHT' => 16],
									'USE_SIZE_IN_PATH' => false,
									'ICON_CLASS' => 'status__svg-icon '.$statusCode,
									'WRAPPER' => 'status-container color_222 ' . $statusCode,
									'DATA_ATTRS' => [
										'state' => $statusCode,
										'code' => $arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID'],
										'value' => $arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE'],
									]
								]); ?>
							</div>
						<? endif; ?>
						
						<? // calculate delivery?>
						<? if (
							$bShowCalculateDelivery &&
							!$bSKU2
						): ?>
							<div class="grid-list__item">
							<?
							$arConfig = [
								'NAME' => $arParams['EXPRESSION_FOR_CALCULATE_DELIVERY'],
								'SVG_NAME' => 'delivery',
								'SVG_SIZE' => ['WIDTH' => 16, 'HEIGHT' => 15],
								'SVG_PATH' => '/catalog/item_order_icons.svg#delivery',
								'WRAPPER' => 'stroke-dark-light-block dark_link animate-load',
								'DATA_ATTRS' => [
									'event' => 'jqm',
									'param-form_id' => 'delivery',
									'name' => 'delivery',
									'param-product_id' => $arCurrentSKU ? $arCurrentSKU['ID'] : $arResult['ID'],
								]
							];

							if ($arParams['USE_REGION'] === 'Y' && $arParams['STORES'] && is_array($arParams['STORES'])) {
								$arConfig['DATA_ATTRS']['param-region_stores_id'] = implode(',', $arParams['STORES']);
							}
							?>
							<?= TSolution\Product\Common::showModalBlock($arConfig); ?>
							<? unset($arConfig); ?>
							</div>
						<? endif; ?>

						<? // found cheaper?>
						<? if ($bShowCheaperForm): ?>
							<div class="grid-list__item">
								<?= TSolution\Product\Common::showModalBlock([
									'NAME' => $arParams['CHEAPER_FORM_NAME'],
									'SVG_NAME' => 'valet',
									'SVG_PATH' => '/catalog/item_order_icons.svg#valet',
									'SVG_SIZE' => ['WIDTH' => 16, 'HEIGHT' => 16],
									'WRAPPER' => 'stroke-dark-light-block dark_link animate-load',
									'DATA_ATTRS' => [
										'event' => "jqm",
										'param-id' => TSolution::partnerName . '_' . TSolution::solutionName . '_cheaper',
										'name' => 'cheaper',
										'autoload-product_name' => TSolution::formatJsName($arCurrentSKU ? $arCurrentSKU['NAME'] : $arResult['NAME']),
										'autoload-product_id' => $arCurrentSKU ? $arCurrentSKU['ID'] : $arResult['ID'],
									]
								]); ?>
							</div>
						<? endif; ?>
						
						<? // send gift?>
						<? if ($bShowSendGift): ?>
							<div class="grid-list__item">
								<?= TSolution\Product\Common::showModalBlock([
									'NAME' => $arParams['SEND_GIFT_FORM_NAME'],
									'SVG_NAME' => 'gift',
									'WRAPPER' => 'stroke-dark-light-block dark_link animate-load',
									'SVG_SIZE' => ['WIDTH' => 16, 'HEIGHT' => 17],
									'SVG_PATH' => '/catalog/item_order_icons.svg#gift',
									'DATA_ATTRS' => [
										'event' => "jqm",
										'param-id' => TSolution::partnerName . '_' . TSolution::solutionName . '_send_gift',
										'name' => 'send_gift',
										'autoload-product_name' => TSolution::formatJsName($arResult["NAME"]),
										'autoload-product_link' => (CMain::IsHTTPS() ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . $APPLICATION->GetCurPage(),
										'autoload-product_id' => $arResult["ID"]
									]
								]); ?>
							</div>
						<? endif; ?>

						<? if (trim(strip_tags($arResult['INCLUDE_CONTENT']))): ?>
							<div class="grid-list__item">
								<?= TSolution\Product\Common::showModalBlock([
									'SVG_NAME' => 'gift',
									'SVG_PATH' => '/catalog/item_order_icons.svg#attention-16-16',
									'USE_SIZE_IN_PATH' => false,
									'SVG_SIZE' => ['WIDTH' => 17, 'HEIGHT' => 16],
									'TEXT' => $arResult['INCLUDE_CONTENT'],
									'WRAPPER' => 'fill-dark-light',
								]); ?>
							</div>
						<? endif; ?>
					</div>
				<?endif;?>
				<?if(strlen($arResult['INCLUDE_PRICE'])):?>
					<div class="price_txt font_13 color_999 mt-16">
						<?=$arResult['INCLUDE_PRICE']?>
					</div>
				<?endif;?>
			</div>

			<div class="grid-list__item">
				<? if ($arResult['BRAND_ITEM'] && $arResult['BRAND_ITEM']["IMAGE"]): ?>
					<div class="grid-list__item">
						<div class="brand-detail flexbox line-block--gap line-block--gap-8">
							<div class="brand-detail-info">
								<div class="brand-detail-info__image rounded-x">
									<a href="<?=$arResult['BRAND_ITEM']["DETAIL_PAGE_URL"];?>">
										<img src="<?=$arResult['BRAND_ITEM']["IMAGE"]["src"];?>" alt="<?=$arResult['BRAND_ITEM']["NAME"];?>" title="<?=$arResult['BRAND_ITEM']["NAME"];?>" itemprop="image">
									</a>
								</div>
							</div>

							<div class="brand-detail-info__preview line-block line-block--gap line-block--gap-8 font_14 flexbox--wrap">
								<div class="line-block__item">
									<a class="chip chip--transparent bordered" href="<?=$arResult['BRAND_ITEM']["DETAIL_PAGE_URL"];?>" target="_blank">
										<span class="chip__label"><?=GetMessage("ITEMS_BY_BRAND", array("#BRAND#" => $arResult['BRAND_ITEM']["NAME"]))?></span>
									</a>
								</div>
								<?if($arResult['SECTION']):?>
									<div class="line-block__item">
										<a class="chip chip--transparent bordered" href="<?= $arResult['BRAND_ITEM']['CATALOG_PAGE_URL'] ?>" target="_blank">
											<span class="chip__label"><?=GetMessage("ITEMS_BY_SECTION")?></span>
										</a>
									</div>
								<?endif;?>
							</div>
						</div>
					</div>
				<? endif; ?>
				<div class="catalog-detail__info-tc"></div>
				

				<div class="catalog-detail__main-part catalog-detail__main-part--right sticky-block flex-1 line-block__item grid-list grid-list--items-1 grid-list--gap-8 grid-list--fill-bg">
					<div class="grid-list__item">
						
					</div>
					
					<?//sales?>
					<div class="grid-list__item" data-js-block=".catalog-detail__sale-block"></div>
				</div>
			</div>

			<? //tizers ?>
			<div class="grid-list__item" data-js-block=".catalog-detail__tizers-block"></div>
			
		</div>
	</div>
</div>