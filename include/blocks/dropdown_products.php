<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproLite::showBlockHtml?>
<?
use CLite as Solution;
$arOptions = (array)$arConfig['PARAMS'];
$arItems = $arConfig['PARAMS']['ITEMS'];
$type = $arConfig['PARAMS']['TYPE'];
?>

<div class="top-hover-product-wrap">
	<div class="items_wrap cart dropdown dropdown--relative dropdown-product">
		
		<div class="items scrollbar dropdown-product__items">
			<? foreach ($arItems as $k => $arItem) :?>
				<div class="dropdown-product__item js-popup-block">
					<div class="line-block line-block--20 line-block--align-normal" data-item="<?=$arItem['JSON_DATA']?>">
						<div class="line-block__item">
							<div class="dropdown-product__item-image">
								<? if ($arItem["DETAIL_PAGE_URL"]) : ?><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="thumb"><? endif; ?>
								<? if ($arItem["IMAGE"]["src"]) { ?>
									<img src="<?= $arItem["IMAGE"]["src"] ?>" alt="<?= $arItem["NAME"]; ?>" title="<?= $arItem["NAME"]; ?>" />
								<? } else { ?>
									<img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/noimage_product.svg" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" width="72" height="72" />
								<? } ?>
								<? if ($arItem["DETAIL_PAGE_URL"]) : ?></a><? endif; ?>
							</div>
						</div>
						<div class="line-block__item flex-1 flexbox flexbox--row flexbox--align-center">
							<div class="dropdown-product__item-info">									
								<div class="font_14 dropdown-product__item-title">
									<? if ($arItem["DETAIL_PAGE_URL"]) : ?><a class="dark_link" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><? endif; ?><?= $arItem["NAME"] ?><? if ($arItem["DETAIL_PAGE_URL"]) : ?></a><? endif; ?>
								</div>
								
								<div class="dropdown-product__item-remove dropdown-product-action remove fill-dark-light-block fill-theme-use-svg-hover" title="<?=GetMessage('T_BUTTON_REMOVE_ITEM')?>" data-action="<?=$type?>" >
									<?=Solution::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons.svg#remove-16-16", "remove ", ['WIDTH' => 16,'HEIGHT' => 16]);?>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</div>

	<div class="foot dropdown dropdown--relative dropdown-product-foot dropdown-product-foot--one-btn">		
		<div class="buttons">
			<div class="wrap_button ">
				<a href="<?= $arConfig['PARAMS']['PATH_TO_ALL'] ?>" class="btn btn-default btn-wide"><span><?= $arConfig['PARAMS']['TITLE_TO_ALL'] ?></span></a>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		BX.loadCSS(['<?=$GLOBALS['APPLICATION']->oAsset->getFullAssetPath(SITE_TEMPLATE_PATH.'/css/basket.css');?>']);
	</script>
</div>