<?
if($_SESSION['UF_VIEWTYPE_BRAND_'.$arParams['IBLOCK_ID']] === NULL){
	$arUserFieldViewType = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'IBLOCK_'.$arParams['IBLOCK_ID'].'_SECTION', 'FIELD_NAME' => $arParams["SECTION_DISPLAY_PROPERTY"]))->Fetch();
	$resUserFieldViewTypeEnum = CUserFieldEnum::GetList(array(), array('USER_FIELD_ID' => $arUserFieldViewType['ID']));
	while($arUserFieldViewTypeEnum = $resUserFieldViewTypeEnum->GetNext()){
		$_SESSION['UF_VIEWTYPE_BRAND_'.$arParams['IBLOCK_ID']][$arUserFieldViewTypeEnum['ID']] = $arUserFieldViewTypeEnum['XML_ID'];
	}
}

unset($_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))]);

$sort_default = $arParams['SORT_PROP_DEFAULT'] ? $arParams['SORT_PROP_DEFAULT'] : 'NAME';
$order_default = $arParams['SORT_DIRECTION'] ? $arParams['SORT_DIRECTION'] : 'asc';
$arPropertySortDefault = array('name', 'sort');

$arAvailableSort = array(
	'NAME' => array(
		'KEY' => 'NAME', // for array_search
		'SORT' => 'NAME',
		'ORDER_VALUES' => array(
			'asc' => GetMessage('sort_name_asc'),
			'desc' => GetMessage('sort_name_desc'),
		),
	),
	'SORT' => array(
		'KEY' => 'SORT', // for array_search
		'SORT' => 'SORT',
		'ORDER_VALUES' => array(
			'asc' => GetMessage('sort_sort_asc'),
			'desc' => GetMessage('sort_sort_desc'),
		)
	),
	'SHOWS' => array(
		'KEY' => 'SHOWS', // for array_search
		'SORT' => 'SHOWS',
		'ORDER_VALUES' => array(
			'asc' => GetMessage('sort_shows_asc'),
			'desc' => GetMessage('sort_shows_desc'),
		)
	),
);

if (Bitrix\Main\Loader::includeModule("catalog")) {
	$arAvailableSort['PRICES'] = array(
		'KEY' => 'PRICES',
		'SORT' => 'PRICE',
		'ORDER_VALUES' => array(
			'asc' => GetMessage('sort_price_asc'),
			'desc' => GetMessage('sort_price_desc'),
		),
	);

	if (in_array("PRICES", $arParams['SORT_PROP'])) {
		$arSortPrices = $arParams["SORT_PRICES"];
		if ($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE") {
			$arAvailableSort["PRICES"]["SORT"] = "PROPERTY_".$arSortPrices;
		} else {
			if ($arSortPrices == "REGION_PRICE") {
				global $arRegion;
				if ($arRegion) {
					if (!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component") {
						$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
						$arAvailableSort["PRICES"]["SORT"] = "CATALOG_PRICE_".$price["ID"];
					} else {
						$arAvailableSort["PRICES"]["SORT"] = "CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"];
					}
				} else {
					$price_name = ($arParams["SORT_REGION_PRICE"] ? $arParams["SORT_REGION_PRICE"] : "BASE");
					$price = CCatalogGroup::GetList(array(), array("NAME" => $price_name), false, false, array("ID", "NAME"))->GetNext();
					$arAvailableSort["PRICES"]["SORT"] = "CATALOG_PRICE_".$price["ID"];
				}
			} else {
				$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
				$arAvailableSort["PRICES"]["SORT"] = "CATALOG_PRICE_".$price["ID"];
			}
		}
	}

	$arAvailableSort['QUANTITY'] = array(
		'KEY' => 'QUANTITY',
		'SORT' => 'CATALOG_AVAILABLE',
		'ORDER_VALUES' => array(
			'asc' => GetMessage('sort_quantity_asc'),
			'desc' => GetMessage('sort_quantity_desc'),
		),
	);
}

foreach($arAvailableSort as $prop => $arProp){

	if(!in_array($prop, $arParams['SORT_PROP']) && $sort_default !== $prop){
		unset($arAvailableSort[$prop]);
	}
}

if($arParams['SORT_PROP']){
	if(!isset($_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))])){
		$sortElementField = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
		if (in_array("CUSTOM", $arParams['SORT_PROP']) && !array_key_exists($sortElementField, $arAvailableSort)) {
			$arAvailableSort[$sortElementField] = array(
				'KEY' => $sortElementField,
				'SORT' => $sortElementField,
				'ORDER_VALUES' => array(
					'asc' => GetMessage('sort_custom_asc'),
					'desc' => GetMessage('sort_custom_desc'),
				)
			);
			if ($sort_default === 'CUSTOM') {
				$sort_default = $sortElementField;
			}
		} else {
			foreach($arParams['SORT_PROP'] as $prop){
				if(!isset($arAvailableSort[$prop])){
					$propWithPrefix = 'PROPERTY_'.$prop;
					$dbRes = CIBlockProperty::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arParams['LINK_GOODS_IBLOCK_ID'], 'CODE' => $prop));
					while($arPropperty = $dbRes->Fetch()){
						$arAvailableSort[$propWithPrefix] = array(
							'KEY' => $propWithPrefix,
							'SORT' => $propWithPrefix,
							'ORDER_VALUES' => array(),
						);
	
						$arAvailableSort[$propWithPrefix]['ORDER_VALUES']['asc'] = GetMessage('sort_title_property', array('#CODE#' => $arPropperty['NAME'], '#ORDER#' => GetMessage('sort_prop_asc')));
						$arAvailableSort[$propWithPrefix]['ORDER_VALUES']['desc'] = GetMessage('sort_title_property', array('#CODE#' => $arPropperty['NAME'], '#ORDER#' => GetMessage('sort_prop_desc')));
					}
	
					if ($sort_default === $prop) {
						$sort_default = $propWithPrefix;
					}
				}
			}
		}
		$_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))] = $arAvailableSort;
	}
	else{
		$arAvailableSort = $_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))];
	}

}

$arDisplays = array("table", "list", "price");
if (
	array_key_exists('display', $_REQUEST) && 
	!empty($_REQUEST['display']) && 
	(in_array(trim($_REQUEST["display"]), $arDisplays))
) {
	setcookie('catalogBrandViewMode', $_REQUEST['display'], 0, SITE_DIR);
	$_COOKIE['catalogBrandViewMode'] = $_REQUEST['display'];
}
if (array_key_exists('sort', $_REQUEST) && !empty($_REQUEST['sort'])) {
	setcookie('catalogBrandSort', $_REQUEST['sort'], 0, SITE_DIR);
	$_COOKIE['catalogBrandSort'] = $_REQUEST['sort'];
}
if (array_key_exists('order', $_REQUEST) && !empty($_REQUEST['order'])) {
	setcookie('catalogBrandOrder', $_REQUEST['order'], 0, SITE_DIR);
	$_COOKIE['catalogBrandOrder'] = $_REQUEST['order'];
}
if (array_key_exists('show', $_REQUEST) && !empty($_REQUEST['show'])) {
	setcookie('catalogPageElementCount', $_REQUEST['show'], 0, SITE_DIR);
	$_COOKIE['catalogPageElementCount'] = $_REQUEST['show'];
}

if (isset($_COOKIE['catalogBrandViewMode']) && $_COOKIE['catalogBrandViewMode']) {
	$display = $_COOKIE['catalogBrandViewMode'];
} else {
	if (
		$arSection[$arParams["SECTION_DISPLAY_PROPERTY"]] && 
		isset($_SESSION[$arParams["SECTION_DISPLAY_PROPERTY"].'_'.$arParams['IBLOCK_ID']][$arSection[$arParams["SECTION_DISPLAY_PROPERTY"]]])
	) {
		$display = $_SESSION[$arParams["SECTION_DISPLAY_PROPERTY"].'_'.$arParams['IBLOCK_ID']][$arSection[$arParams["SECTION_DISPLAY_PROPERTY"]]];
	} else {
		$display = $arParams['VIEW_TYPE'];
	}
}

$bForceDisplay = false;	

if ($display) {
	if (!in_array(trim($display), $arDisplays)) {
		$display = "table";
	}
} else {
	$display = "table";
}

$show = !empty($_COOKIE['catalogPageElementCount']) ? $_COOKIE['catalogPageElementCount'] : $arParams['PAGE_ELEMENT_COUNT'];
$sort = !empty($_COOKIE['catalogBrandSort']) ? $_COOKIE['catalogBrandSort'] : $sort_default;
$order = !empty($_COOKIE['catalogBrandOrder']) ? $_COOKIE['catalogBrandOrder'] : $order_default;

$sortKey = array_search($sort, array_column($arAvailableSort, 'SORT', 'KEY')); // find by SORT field
if (!$sortKey) $sortKey = array_search($sort_default, array_column($arAvailableSort, 'KEY', 'KEY'));

$arDelUrlParams = array('sort', 'order', 'control_ajax', 'ajax_get_filter', 'ajax_get', 'linerow', 'display', 'is_aspro_mobile');
?>
<!-- noindex -->
<div class="filter-panel sort_header view_<?=$display?> flexbox flexbox--direction-row flexbox--justify-beetwen ">
	<div class="filter-panel__part-left">
		<div class="line-block filter-panel__main-info">
			<?if($arTheme['SHOW_SMARTFILTER']['VALUE'] !== 'N' && $arItems):?>
				<?$bActiveFilter = TSolution\Functions::checkActiveFilterPage([
					'SEF_URL' => $arParams["SEF_URL_TEMPLATES"]['smart_filter'],
					'GLOBAL_FILTER' => "arrGoodsFilter"
				]);?>
				<div class="line-block__item filter-panel__filter <?=($bHideLeftBlock && !$bShowCompactHideLeft ? 'filter-panel__filter--visible' : '');?>">
					<div class="fill-theme-hover dark_link">
						<div class="bx-filter-title filter_title <?=($bActiveFilter ? 'active-filter' : '')?>">
							<?=TSolution::showIconSvg("icon svg-inline-catalog fill-dark-light", SITE_TEMPLATE_PATH.'/images/svg/catalog/filter.svg', '', '', true, false);?>
							<span class="font_upper_md dotted font_bold"><?=\Bitrix\Main\Localization\Loc::getMessage("CATALOG_SMART_FILTER_TITLE");?></span>
						</div>
						<div class="controls-hr"></div>
					</div>
				</div>
			<?endif;?>

			<?if ($arAvailableSort):?>
				<?ob_start();?>
				<div class="line-block__item">
					<div class="filter-panel__sort">
						<div class="dropdown-select dropdown-select--with-dropdown">
							<div class="dropdown-select__title font_14 font_large fill-dark-light bordered rounded-x shadow-hovered shadow-no-border-hovered">
								<span>
									<?if($order && $sort):?>
										<?=$arAvailableSort[$sortKey]['ORDER_VALUES'][$order]?>
									<?else:?>
										<?=\Bitrix\Main\Localization\Loc::getMessage('NOTHING_SELECTED');?>
									<?endif;?>
									</span>
								<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/arrows.svg#down-7-5', 'dropdown-select__icon-down', ['WIDTH' => 7, 'HEIGHT' => 5]);?>
							</div>
							<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
								<div class="dropdown-menu-inner rounded-x">
									<?foreach($arAvailableSort as $arSort):?>
										<?$newSort = $arSort['SORT'];?>
										<?if(is_array($arSort['ORDER_VALUES'])):?>
											<?foreach($arSort['ORDER_VALUES'] as $newOrder => $sortTitle):?>
												<div class="dropdown-select__list-item font_15">
													<?
													$current_url = $APPLICATION->GetCurPageParam('sort='.$newSort.'&order='.$newOrder, $arDelUrlParams);
													$url = str_replace('+', '%2B', $current_url);?>
	
													<?if ($bCurrentLink = (
														($sort == $newSort || $sortKey == $arSort['KEY']) && $order == $newOrder)
													):?>
														<span class="dropdown-menu-item color_222 dropdown-menu-item--current">
													<?else:?>
														<a href="<?=$url;?>" class="dropdown-menu-item <?=$value?> <?=$key?> dark_link <?=($arParams['AJAX_CONTROLS'] == 'Y' ? ' js-load-link' : '');?>" data-url="<?=$url;?>" rel="nofollow prefetch">
													<?endif;?>
														<span>
															<?=$sortTitle?>
														</span>
													<?if($bCurrentLink):?>
														</span>
													<?else:?>
														</a>
													<?endif;?>
												</div>
											<?endforeach?>
										<?endif;?>
									<?endforeach;?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?$sortHTML = ob_get_clean();?>
				<?=$sortHTML;?>
			<?endif;?>
		</div>
		<?include_once(__DIR__."/filter.php");?>		
	</div>
	<?if (!$bForceDisplay):?>
		<div class="filter-panel__part-right">
				<div class="toggle-panel hide-600">
					<?foreach($arDisplays as $displayType):?>
						<?
						$current_url = '';
						$current_url = $APPLICATION->GetCurPageParam('display='.$displayType, $arDelUrlParams);
						$url = str_replace('+', '%2B', $current_url);
						?>
						<?if($display == $displayType):?>
							<span title="<?=\Bitrix\Main\Localization\Loc::getMessage("SECT_DISPLAY_".strtoupper($displayType))?>" class="toggle-panel__item toggle-panel__item--current"><?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/catalog/toggle_view.svg#'.$displayType, '', ['WIDTH' => '10px', 'HEIGHT' => '10px']);?></span>
						<?else:?>
							<a rel="nofollow prefetch" href="<?=$url;?>" data-url="<?=$url?>" title="<?=\Bitrix\Main\Localization\Loc::getMessage("SECT_DISPLAY_".strtoupper($displayType))?>" class="toggle-panel__item muted-use-no-hover <?=($arParams['AJAX_CONTROLS'] == 'Y' ? ' js-load-link' : '');?>"><?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/catalog/toggle_view.svg#'.$displayType, 'fill-dark-light', ['WIDTH' => '10px', 'HEIGHT' => '10px']);?></a>
						<?endif;?>
					<?endforeach;?>
				</div>
			</div>
			<?TSolution\Extensions::init('toggle_panel');?>
	<?endif;?>
</div>
<?TSolution\Extensions::init(['filter_panel', 'dropdown_select'])?>
<!-- /noindex -->
