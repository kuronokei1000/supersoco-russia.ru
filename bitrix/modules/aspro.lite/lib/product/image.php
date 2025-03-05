<?
namespace Aspro\Lite\Product;

use \Bitrix\Main\Loader,
    \Bitrix\Main\Config\Option;

use CLite as Solution,
    CLiteCache as SolutionCache,
    Aspro\Lite\Product\Common as SolutionProduct,
    Aspro\Functions\CAsproLite as SolutionFunctions;

class Image {

    public static function showImage($arOptions = [])
	{
		global $APPLICATION;
		$arDefaultOptions = [
			'TYPE' => '',
			'CONTENT_TOP' => '',
			'CONTENT_BOTTOM' => '',
			'WRAP_LINK' => true,
			'ADDITIONAL_WRAPPER_CLASS' => '',
			'ADDITIONAL_IMG_CLASS' => '',
			'RETURN' => false,
			'ITEM' => [],
			'PARAMS' => [],
			'STICKY' => false,
			'FV_WITH_ICON' => 'N',
			'FV_WITH_TEXT' => 'Y',
			'FV_BTN_CLASS' => 'btn btn-xs btn-default',
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		if ($handler = SolutionFunctions::getCustomFunc(__FUNCTION__)) {
			return call_user_func_array($handler, [$arConfig]);
		}

		$arParams = $arConfig['PARAMS'];
		$arItem = $arConfig['ITEM'];
		?>
		<?ob_start();?>
			<div class="image-list <?=$arConfig['ADDITIONAL_WRAPPER_CLASS'];?>">
				<div class="image-list-wrapper js-image-block<?=($arConfig['STICKY'] ? ' sticky-block' : '')?>">
					<?/*self::showSideIcons([
						'TYPE' => $arConfig['TYPE'],
						'ITEM' => $arItem,
						'PARAMS' => $arParams,
					]);*/?>
					<?SolutionProduct::showStickers([
						'TYPE' => $arConfig['TYPE'],
						'ITEM' => $arItem,
						'PARAMS' => $arParams,
						'CONTENT' => $arConfig['CONTENT_TOP'],
					]);?>
					<?SolutionProduct::showFastView([
						'ITEM' => $arItem,
						'PARAMS' => $arParams,
						'WITH_ICON' => $arConfig['FV_WITH_ICON'],
						'WITH_TEXT' => $arConfig['FV_WITH_TEXT'],
						'BTN_CLASS' => $arConfig['FV_BTN_CLASS'],
					]);?>
					<?if($arParams['SHOW_GALLERY'] != 'N'):?>
						<?self::showSectionGallery([
							'TYPE' => $arConfig['TYPE'],
							'ADDITIONAL_IMG_CLASS' => $arConfig['ADDITIONAL_IMG_CLASS'],
							'ITEM' => $arItem,
							'PARAMS' => $arParams,
						]);?>
					<?else:?>
						<?self::showImg([
							'TYPE' => $arConfig['TYPE'],
							'WRAP_LINK' => $arConfig['WRAP_LINK'],
							'ADDITIONAL_IMG_CLASS' => $arConfig['ADDITIONAL_IMG_CLASS'],
							'ITEM' => $arItem,
							'PARAMS' => $arParams,
						]);?>
					<?endif;?>
				</div>
				<?if ($arConfig['CONTENT_BOTTOM']):?>
					<?=$arConfig['CONTENT_BOTTOM'];?>
				<?endif;?>
			</div>
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

	public static function showImg($arOptions = [])
	{
		global $APPLICATION;
		$arDefaultOptions = [
			'TYPE' => '',
			'WRAP_LINK' => true,
			'ADDITIONAL_IMG_CLASS' => '',
			'ITEM' => [],
			'PARAMS' => [],
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		if ($handler = SolutionFunctions::getCustomFunc(__FUNCTION__)) {
			return call_user_func_array($handler, [$arConfig]);
		}

		$arParams = $arConfig['PARAMS'];
		$arItem = $arConfig['ITEM'];
		$dopClassImg = $arConfig['ADDITIONAL_IMG_CLASS'];
		$bHasParentImg = (isset($arItem['PARENT_IMG']) && $arItem['PARENT_IMG']);
		?>

		<?if($arItem):?>
			<?ob_start();?>

			<?
			$jsImgSrc = '';
			if ($bHasParentImg) {
				$arItem['PARENT_IMG'] = is_array($arItem['PARENT_IMG'])
					? $arItem['PARENT_IMG']['SRC']
					: \CFile::GetPath($arItem['PARENT_IMG']);
				$jsImgSrc = 'data-js="'.$arItem['PARENT_IMG'].'"';
			}
			?>

			<?if($arConfig['WRAP_LINK']):?>
				<?/*if($arConfig['ZOOM']):?>
					<a href="javascript:void(0)" rel="nofollow" class="image-list__link fancy-js">
				<?else:*/?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="image-list__link">
				<?//endif;?>
			<?endif;?>
				<?
				$a_alt = (is_array($arItem["PREVIEW_PICTURE"]) && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem['SELECTED_SKU_IPROPERTY_VALUES'] ? ($arItem["SELECTED_SKU_IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["SELECTED_SKU_IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"]) : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"])));

				$a_title = (is_array($arItem["PREVIEW_PICTURE"]) && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem['SELECTED_SKU_IPROPERTY_VALUES'] ? ($arItem["SELECTED_SKU_IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["SELECTED_SKU_IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"]) : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"])));
				?>

				<?if (!empty($arItem["PREVIEW_PICTURE"]) ):?>
					<?
					$src = is_array($arItem["PREVIEW_PICTURE"]) ? $arItem["PREVIEW_PICTURE"]["SRC"] : \CFile::GetPath($arItem["PREVIEW_PICTURE"]);
					if ($arItem["DETAIL_PICTURE"]) {
						if (isset($arItem["DETAIL_PICTURE"]["SRC"])) {
							$bigSrc = $arItem["DETAIL_PICTURE"]["SRC"];
						} else {
							$bigSrc = \CFile::GetPath($arItem["DETAIL_PICTURE"]);
						}
					} else {
						$bigSrc = $src;
					}
					?>
					<img class="img-responsive rounded-x <?=$dopClassImg;?>" src="<?=$src;?>" data-big="<?=$bigSrc?>" <?=$jsImgSrc;?> alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
				<?elseif (!empty($arItem["DETAIL_PICTURE"])):?>
					<?if(isset($arItem["DETAIL_PICTURE"]["src"])):?>
						<?$img["src"] = $arItem["DETAIL_PICTURE"]["src"]?>
					<?else:?>
						<?$img = \CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 350, "height" => 350 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
					<?endif;?>
					<img class="img-responsive rounded-x <?=$dopClassImg;?>" src="<?=$img["src"]?>" <?=$jsImgSrc;?> alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
				<?else:?>
					<img class="img-responsive rounded-x <?=$dopClassImg;?>" src="<?=SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg';?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
				<?endif;?>
			<?if($arConfig['WRAP_LINK']):?>
				</a>
			<?endif;?>

			<?$html = ob_get_contents();
			ob_end_clean();

			// event for manipulation
			foreach (GetModuleEvents(Solution::moduleID, 'OnAspro'.ucfirst(__FUNCTION__), true) as $arEvent) {
				ExecuteModuleEventEx($arEvent, array($arConfig, &$html));
			}

			echo $html;?>
		<?endif;?>
	<?}

	public static function showSectionGallery($arOptions = [])
	{
		global $APPLICATION;
		$arDefaultOptions = [
			'TYPE' => '',
			'WRAP_LINK' => true,
			'RETURN' => false,
			'ZOOM' => true,
			'ADDITIONAL_IMG_CLASS' => '',
			'RESIZE' => [
				'WIDTH' => 2000,
				'HEIGHT' => 2000,
			],
			'ITEM' => [],
			'PARAMS' => [],
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		if ($handler = SolutionFunctions::getCustomFunc(__FUNCTION__)) {
			return call_user_func_array($handler, [$arConfig]);
		}

		$arParams = $arConfig['PARAMS'];
		$arItem = $arConfig['ITEM'];
		$key = $arParams['GALLERY_KEY'] ? $arParams['GALLERY_KEY'] : 'GALLERY';
		$bReturn = $arConfig['RETURN'];
		$arResize = $arConfig['RESIZE'];
		$dopClassImg = $arConfig['ADDITIONAL_IMG_CLASS'];
		$bHasParentImg = (isset($arItem['PARENT_IMG']) && $arItem['PARENT_IMG']);

		if($arItem):?>
			<?ob_start();?>

				<?
				$jsImgSrc = '';
				if ($bHasParentImg) {
					$arItem['PARENT_IMG'] = is_array($arItem['PARENT_IMG'])
						? $arItem['PARENT_IMG']['SRC']
						: \CFile::GetPath($arItem['PARENT_IMG']);
					$jsImgSrc = 'data-js="'.$arItem['PARENT_IMG'].'"';
				}
				?>

				<?if($arItem[$key]):?>
					<?$count = count($arItem[$key]);?>
					<?if($arConfig['WRAP_LINK']):?>
						<?/*if($arConfig['ZOOM']):?>
							<a href="javascript:void(0)" rel="nofollow" class="image-list__link fancy-js">
						<?else:*/?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="image-list__link">
						<?//endif;?>
					<?endif;?>
						<span class="section-gallery-wrapper js-replace-gallery flexbox">
							<?foreach($arItem[$key] as $i => $arGalleryItem):?>
								<?
								if($arResize) {
									$resizeImage = \CFile::ResizeImageGet($arGalleryItem["ID"], array("width" => $arResize['WIDTH'], "height" => $arResize['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
									$arGalleryItem['SRC'] = $resizeImage['src'];
									$arGalleryItem['HEIGHT'] = $resizeImage['height'];
									$arGalleryItem['WIDTH'] = $resizeImage['width'];
								}?>
								<span class="section-gallery-wrapper__item<?=(!$i ? ' active' : '');?>">
									<span class="section-gallery-wrapper__item-nav<?=($count > 1 ? ' ' : ' section-gallery-wrapper__item_hidden ');?>"></span>
									<img class="img-responsive <?=$dopClassImg?>" src="<?=$arGalleryItem["SRC"];?>" <?=$jsImgSrc;?> data-big="<?=$arGalleryItem["SRC"]?>" alt="<?=$arGalleryItem["ALT"];?>" title="<?=$arGalleryItem["TITLE"];?>" />
								</span>
							<?endforeach;?>
						</span>
					<?if($arConfig['WRAP_LINK']):?>
						</a>
					<?endif;?>
					<?if ($count > 1):?>
						<span class="section-gallery-nav">
							<span class="section-gallery-nav__wrapper">
								<?foreach($arItem[$key] as $i => $arGalleryItem):?>
									<span class="section-gallery-nav__item bg-theme-hover<?=(!$i ? ' active' : '');?>"></span>
								<?endforeach;?>
							</span>
						</span>
					<?endif;?>
				<?else:?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="image-list__link"><img class="img-responsive <?=$dopClassImg?>" src="<?=SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg';?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" /></a>
				<?endif;?>
			<?$html = ob_get_contents();
			ob_end_clean();

			// event for manipulation
			foreach (GetModuleEvents(Solution::moduleID, 'OnAspro'.ucfirst(__FUNCTION__), true) as $arEvent) {
				ExecuteModuleEventEx($arEvent, array($arConfig, &$html));
			}

			if(!$bReturn)
				echo $html;
			else
				return $html?>
		<?endif;?>
	<?}
}?>
