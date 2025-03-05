<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

use \Bitrix\Main\Loader;

$this->setFrameMode(true);

$context = \Bitrix\Main\Context::getCurrent();
$request = $context->getRequest();
$sBufferKey = $arParams['COMPONENT_TEMPLATE']."_".$arParams['COMPONENT_CALL_INDEX'];
$bAjax = TSolution::checkAjaxRequest() && $request['AJAX_REQUEST'] === 'Y';

if (
	$arResult["TABS"] &&
	(
		!$bAjax ||
		(
			$bAjax &&
			(
				$request['TABS_REQUEST'] === 'Y' ||
				TSolution::checkRequestBlock($arResult['BLOCK_CODE'])
			)
		)
	)
):?>
	<?
	$arParams['SET_TITLE'] = 'N';
	$arParams['SET_BROWSER_TITLE'] = 'N';

	$arTmp = reset($arResult["TABS"]);
	$arParams["FILTER_HIT_PROP"] = $arTmp["CODE"];
	$arParams["SHOW_FAVORITE"] = TSolution::GetFrontParametrValue('SHOW_FAVORITE');
	$arParams["SHOW_DISCOUNT_TIME"] = TSolution::GetFrontParametrValue('SHOW_DISCOUNT_TIME');
	$arParams["SHOW_DISCOUNT_PERCENT"] = TSolution::GetFrontParametrValue('SHOW_DISCOUNT_PERCENT');
	$arParams["SHOW_OLD_PRICE"] = TSolution::GetFrontParametrValue('SHOW_OLD_PRICE');
	$arParams["HIDE_NOT_AVAILABLE"] = TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE');
	$arParams["HIDE_NOT_AVAILABLE_OFFERS"] = TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE_OFFERS');
	$arParams["SHOW_RATING"] = Loader::includeModule('blog') ? TSolution::GetFrontParametrValue('SHOW_RATING') : false;
	$arParams["CONVERT_CURRENCY"] = TSolution::GetFrontParametrValue('CONVERT_CURRENCY');
	$arParams["CURRENCY_ID"] = TSolution::GetFrontParametrValue('CURRENCY_ID');
	$arParams["PRICE_VAT_INCLUDE"] = TSolution::GetFrontParametrValue('PRICE_VAT_INCLUDE');
	$arParams["OFFERS_LIMIT"] = TSolution::GetFrontParametrValue('CATALOG_SKU_LIMIT');
	?>
	<div class="element-list <?=$blockClasses?> <?=$templateName?>-template" data-block_key="<?=$sBufferKey?>">
		<?$navHtml = '';?>
		<?if (count($arResult["TABS"]) > 1):?>
			<?ob_start();?>
				<div class="tab-nav-wrapper">
					<div class="tab-nav font_14 font_12--to-600 line-block line-block--8 relative mobile-scrolled mobile-scrolled--items-auto mobile-offset" data-template="<?=$arParams['TYPE_TEMPLATE']?>" >
						<?$i = 0;?>
						<?foreach ($arResult["TABS"] as $key => $arItem):?>
							<div class="line-block__item"><div class="chip chip--sm-to-600 tab-nav__item bg-theme-active color-theme-hover-no-active <?=(!$i ? 'active clicked' : '');?>" data-code="<?=$key;?>"><span class="chip__label"><?=$arItem['TITLE']?></span></div></div>
							<?++$i;?>
						<?endforeach;?>
					</div>
				</div>
			<?$navHtml = ob_get_clean();?>
		<?endif;?>
		
		<?=TSolution\Functions::showTitleBlock([
			'PATH' => 'elements-list',
			'PARAMS' => $arParams,
			'CENTER_BLOCK' => $navHtml
		]);?>

		<?if($arParams['NARROW']):?>
			<div class="maxwidth-theme">
		<?elseif($arParams['ITEMS_OFFSET']):?>
			<div class="maxwidth-theme maxwidth-theme--no-maxwidth">
		<?endif;?>
				<div class="line-block line-block--align-flex-stretch line-block--block">
					<div class="wrapper_tabs line-block__item flex-1">
						<span class="js-request-data request-data" data-value=""<?=($arResult['BLOCK_FILE'] ? ' data-action="'.$arResult['BLOCK_FILE'].'"' : '')?>></span>
						<div class="js-tabs-ajax">
							<?$i = 0;?>
							<?foreach ($arResult["TABS"] as $key => $arItem):?>
								<div class="tab-content-block <?=(!$i ? 'active ' : 'loading-state');?>" data-code="<?=$key?>" data-filter="<?=($arItem["FILTER"] ? urlencode(serialize($arItem["FILTER"])) : '');?>">
									<?
										if ($bAjax) {
											$GLOBALS['APPLICATION']->RestartBuffer();
										}

										if (!$i) {
											if (!$bAjax && $arItem["FILTER"]) {
												// save original global filter & replace by tab filter
												$oldGlobalFilter = $GLOBALS[$arParams["FILTER_NAME"]];
												$GLOBALS[$arParams["FILTER_NAME"]] = $arItem["FILTER"];
											}

											include __DIR__."/page_blocks/".$arParams['TYPE_TEMPLATE'].".php";

											if (!$bAjax && $arItem["FILTER"]) {
												// restore original global filter
												$GLOBALS[$arParams["FILTER_NAME"]] = $oldGlobalFilter;
											}
										}

										if ($bAjax) {
											die();
										}
									?>
								</div>
								<?++$i;?>
							<?endforeach;?>
						</div>
					</div>
				</div>
		<?if($arParams['NARROW'] || $arParams['ITEMS_OFFSET']):?>
		</div>
		<?endif;?>
	</div>
<?endif;?>