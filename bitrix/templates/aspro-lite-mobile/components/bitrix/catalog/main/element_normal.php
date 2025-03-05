<?
use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;

global $arTheme, $APPLICATION;

$APPLICATION->AddViewContent('right_block_class', 'catalog_page ');
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');

// cart
$bOrderViewBasket = (trim($arTheme['ORDER_VIEW']['VALUE']) === 'Y');

if($arSection){
	$arInherite = TSolution::getSectionInheritedUF(array(
		'sectionId' => $arSection['ID'],
		'iblockId' => $arSection['IBLOCK_ID'],
		'select' => array(
			'UF_ELEMENT_DETAIL',
			'UF_OFFERS_TYPE',
			'UF_GALLERY_SIZE',
		),
		'filter' => array(
			'GLOBAL_ACTIVE' => 'Y', 
		),
		'enums' => array(
			'UF_ELEMENT_DETAIL',
			'UF_OFFERS_TYPE',
			'UF_GALLERY_SIZE',
		),
	));
}

TSolution::CheckComponentTemplatePageBlocksParams($arParams, __DIR__);

$arParams['OID'] = 0;
if ($oidParam = TSolution::GetFrontParametrValue('CATALOG_OID')) {
	$context=\Bitrix\Main\Context::getCurrent();
	$request=$context->getRequest();
	if ($oid = $request->getQuery($oidParam)) {
		$arParams['OID'] = $oid;
	}
}

$sViewElementTemplate = TSolution\Functions::getValueWithSection([
	'CODE' => 'CATALOG_PAGE_DETAIL',
	'SECTION_VALUE' => $arInherite['UF_ELEMENT_DETAIL'],
	'CUSTOM_VALUE' => ($arParams['ELEMENT_TYPE_VIEW'] === 'FROM_MODULE' ? $arTheme['CATALOG_PAGE_DETAIL']['VALUE'] : $arParams['ELEMENT_TYPE_VIEW']),
]);
$typeSKU = TSolution\Functions::getValueWithSection([
	'CODE' => 'CATALOG_PAGE_DETAIL_SKU',
	'SECTION_VALUE' => $arInherite['UF_OFFERS_TYPE']
]);
$gallerySize = TSolution\Functions::getValueWithSection([
	'CODE' => 'CATALOG_PAGE_DETAIL_GALLERY_SIZE',
	'SECTION_VALUE' => $arInherite['UF_GALLERY_SIZE']
]);

// is need left block or sticky panel?
$APPLICATION->SetPageProperty('MENU', 'N');
$bWithStickyBlock = false;
if(strpos($sViewElementTemplate, 'element_1') !== false){
	$bShowLeftBlock = false;
	$bWithStickyBlock = true;
} else {
	$bShowLeftBlock = $arTheme['LEFT_BLOCK_CATALOG_DETAIL']['VALUE'] === 'Y';
}
$bShowLeftBlock &= !defined('ERROR_404');
?>
<div class="main-wrapper flexbox flexbox--direction-row <?= $bShowLeftBlock || $bWithStickyBlock ? '' : 'catalog-maxwidth'?>">
	<div class="section-content-wrapper flex-1">
		<?TSolution::AddMeta(
			array(
				'og:description' => $arElement['PREVIEW_TEXT'],
				'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
			)
		);?>

		<?if($arParams['AJAX_MODE'] == 'Y' && strpos($_SERVER['REQUEST_URI'], 'bxajaxid') !== false):?>
			<script type="text/javascript">
				setStatusButton();
			</script>
		<?endif;?>

		<div class="product-container detail <?=$sViewElementTemplate;?> clearfix" itemscope itemtype="http://schema.org/Product">
			<?
			// cross sales for product
			global $arCrossItems;
			$oCrossSales = new \Aspro\Lite\CrossSales($arElement['ID'], $arParams);
			$arRules = $oCrossSales->getRules();
			$arCrossItems = [];
			$bUseAssociated = $bUseExpandables = false;

			// similar goods from cross sales
			if($arRules['ASSOCIATED'])
			{
				$arCrossItems['ASSOCIATED'] = $oCrossSales->getItems('ASSOCIATED');
				if(!empty($arCrossItems['ASSOCIATED'])){
					$bUseAssociated = true;
				}
			}

			// accessories goods from cross sales
			if($arRules['EXPANDABLES'])
			{
				$arCrossItems['EXPANDABLES'] = $oCrossSales->getItems('EXPANDABLES');
				if(!empty($arCrossItems['EXPANDABLES'])){
					$bUseExpandables = true;
				}
			}
			
			?>

			<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
		</div>

		<div class="bottom-links-block">
			<?// back url?>
			<?TSolution\Functions::showBackUrl(
				array(
					'URL' => ((isset($arSection) && $arSection) ? $arSection['SECTION_PAGE_URL'] : $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']),
					'TEXT' => ($arParams['T_PREV_LINK'] ? $arParams['T_PREV_LINK'] : GetMessage('BACK_LINK')),
				)
			);?>
		</div>
	</div>
</div>