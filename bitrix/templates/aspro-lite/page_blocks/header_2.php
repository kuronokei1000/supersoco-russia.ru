<?
include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/settings.php');

global $arTheme;

$headerHeight = '161px';

/* set classes for header parts */
$headerInnerClasses = '';
if(!$bNarrowHeader) {
	$headerInnerClasses .= ' header__inner--paddings';
}

$topPartClasses = '';
$topPartClasses .= ' header__top-part--longer part-with-search';

$mainPartClasses = '';
if(!$bCatalogInBtn){
	$mainPartClasses .= ' header__main-part--short';
}

$innerClasses = 'relative';
?>

<header class="header_2 header <?=($arRegion ? 'header--with_regions' : '')?> <?=$bNarrowHeader ? 'header--narrow' : ''?> <?=TSolution::ShowPageProps('HEADER_COLOR')?>">
	<div class="header__inner <?=$headerInnerClasses?>">

		<?if($ajaxBlock == "HEADER_TOP_PART" && $bAjax) {
			$APPLICATION->restartBuffer();
		}?>

		<div class="header__top-part <?=$topPartClasses?>" data-ajax-load-block="HEADER_TOP_PART">
			<?if($bNarrowHeader):?>
				<div class="maxwidth-theme">
			<?endif;?>
				
			<div class="header__top-inner part-with-search__inner">

				<div class="header__top-item">
					<div class="line-block line-block--40">
						<?//show logo?>
						<div class="logo <?=$logoClass?> line-block__item no-shrinked">
							<?TSolution::ShowBufferedLogo();?>
						</div>
					</div>
				</div>

				<?if($arRegion):?>
					<?//regions?>
					<div class="header__top-item icon-block--with_icon">
						<?
						$arRegionsParams = array();
						if($bAjax) {
							$arRegionsParams['POPUP'] = 'N';
						}
						TSolution::ShowListRegions($arRegionsParams);?>
					</div>
				<?endif;?>

				<?
				$blockOptions = array(
					'PARAM_NAME' => 'HEADER_TOGGLE_SEARCH',
					'BLOCK_TYPE' => 'SEARCH',
					'IS_AJAX' => $bAjax,
					'AJAX_BLOCK' => $ajaxBlock,
					'VISIBLE' => $bShowSearch,
					'WRAPPER' => 'header__top-item header__search header__search--limited',
					'TYPE' => 'LINE',
				);
				?>
				<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

				<?//show phone and callback?>
				<?
				$blockOptions = array(
					'PARAM_NAME' => 'HEADER_TOGGLE_PHONE',
					'BLOCK_TYPE' => 'PHONE',
					'IS_AJAX' => $bAjax,
					'AJAX_BLOCK' => $ajaxBlock,
					'VISIBLE' => $bShowPhone && $bPhone,
					'WRAPPER' => 'header__top-item no-shrinked',
					'CALLBACK' => $bShowCallback && $bCallback,
					'CALLBACK_CLASS' => 'hide-1200',
					'MESSAGE' => GetMessage("S_CALLBACK"),
				);
				?>
				<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

				<?//show currency?>
				<?
				$blockOptions = array(
					'PARAM_NAME' => 'HEADER_TOGGLE_CURRENCY',
					'BLOCK_TYPE' => 'CURRENCY',
					'IS_AJAX' => $bAjax,
					'AJAX_BLOCK' => $ajaxBlock,
					'VISIBLE' => $bShowCurrency,
					'WRAPPER' => 'header__top-item',
				);
				?>
				<?//=\TSolution\Functions::showHeaderBlock($blockOptions);?>

				<?//show theme selector?>
				<?
				$blockOptions = array(
					'PARAM_NAME' => 'HEADER_TOGGLE_THEME_SELECTOR',
					'BLOCK_TYPE' => 'THEME_SELECTOR',
					'IS_AJAX' => $bAjax,
					'AJAX_BLOCK' => $ajaxBlock,
					'VISIBLE' => $bShowThemeSelector,
					'WRAPPER' => 'header__top-item',
				);
				?>
				<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

			</div>
			<?if($bNarrowHeader):?>
				</div>
			<?endif;?>
		</div>

		<?if($ajaxBlock == "HEADER_TOP_PART" && $bAjax) {
			die();
		}?>

		<?if($ajaxBlock == "HEADER_MAIN_PART" && $bAjax) {
			$APPLICATION->restartBuffer();
		}?>

		<div class="header__main-part  <?=$mainPartClasses?>"  data-ajax-load-block="HEADER_MAIN_PART">

			<?if($bNarrowHeader):?>
				<div class="maxwidth-theme">
			<?endif;?>

			<div class="header__main-inner <?=$innerClasses?>">

				<?if($bCatalogInBtn):?>
					<div class="header__main-item header__burger">
						<nav class="mega-menu">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/header/menu.only_catalog.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php",
									"NLO_MENU_CODE" => "menu-header",
									"USE_NLO_MENU" => $arTheme["NLO_MENU"]["VALUE"],
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</nav>
					</div>
				<?endif;?>

				<?if($bCatalogInBtn):?>
					<?//show menu?>
					<div class="header__main-item header-menu header-menu--longer header__bottom-menu flex-1 hide-dotted">
						<nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/header/menu_top.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</nav>
					</div>
				<?else:?>
					<div class="header__main-item flex-1 hide-dotted header-menu header__bottom-menu hide-dotted">
						<nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/header/menu.top_catalog_wide.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php",
								),
								false, array("HIDE_ICONS" => "Y")
							);
							$headerHeight = '137px';
							?>
						</nav>
					</div>
				<?endif;?>
				
				<div class="header__main-item hide-basket-message <?=!$bCabinet && !$bCompare && !$bFavorite && !$bOrder ? 'hidden' : ''?>">
					<div class="line-block line-block--40">
						<?$blockOptions = array(
							'PARAM_NAME' => 'HEADER_TOGGLE_CABINET',
							'BLOCK_TYPE' => 'CABINET',
							'IS_AJAX' => $bAjax,
							'AJAX_BLOCK' => $ajaxBlock,
							'VISIBLE' => $bCabinet,
							'WRAPPER' => 'line-block__item',
							'CABINET_PARAMS' => array(
								'TEXT_LOGIN' => 'login',
								'TEXT_NO_LOGIN' => GetMessage("LOGIN"),
								'CLASS_LINK' => 'fill-dark-light-block color-theme-hover banner-light-icon-fill banner-light-text flexbox flexbox--direction-row flexbox--align-center',
								'TEXT_RIGHT' => 'Y',
							),
						);?>
						<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

						<?$blockOptions = array(
							'PARAM_NAME' => 'HEADER_TOGGLE_COMPARE',
							'BLOCK_TYPE' => 'COMPARE',
							'IS_AJAX' => $bAjax,
							'AJAX_BLOCK' => $ajaxBlock,
							'VISIBLE' => $bCompare,
							'WRAPPER' => 'line-block__item',
							'MESSAGE' => '',
							'CLASS_LINK' => 'light-opacity-hover fill-theme-hover fill-dark-light-block color-theme-hover banner-light-icon-fill flexbox flexbox--direction-column flexbox--align-center',
							'CLASS_ICON' => 'menu-light-icon-fill fill-use-888',
						);?>
						<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

						<?$blockOptions = array(
							'PARAM_NAME' => 'HEADER_TOGGLE_FAVORITE',
							'BLOCK_TYPE' => 'FAVORITE',
							'IS_AJAX' => $bAjax,
							'AJAX_BLOCK' => $ajaxBlock,
							'VISIBLE' => $bFavorite,
							'WRAPPER' => 'line-block__item',
							'MESSAGE' => '',
							'CLASS_LINK' => 'light-opacity-hover color-theme-hover fill-theme-hover fill-dark-light-block color-theme-hover banner-light-icon-fill banner-light-text flexbox flexbox--direction-column flexbox--align-center no-shrinked',
							'CLASS_ICON' => 'menu-light-icon-fill ',
						);?>
						<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

						<?$blockOptions = array(
							'PARAM_NAME' => 'HEADER_TOGGLE_BASKET',
							'BLOCK_TYPE' => 'BASKET',
							'IS_AJAX' => $bAjax,
							'AJAX_BLOCK' => $ajaxBlock,
							'VISIBLE' => $bOrder && !TSolution::IsBasketPage() && !TSolution::IsOrderPage(),
							'WRAPPER' => 'line-block__item',
							'MESSAGE' => GetMessage("BASKET"),
							'CLASS_LINK' => 'flexbox flexbox--direction-column flexbox--align-center fill-dark-light-block no-shrinked'
						);?>
						<?=\TSolution\Functions::showHeaderBlock($blockOptions);?>

						
						
						

					</div>
				</div>

			</div>

			<?if($bNarrowHeader):?>
				</div>
			<?endif;?>	
		</div>

		<?if($ajaxBlock == "HEADER_MAIN_PART" && $bAjax) {
			die();
		}?>
	</div>
</header>
<?
\TSolution::$arCssVariables['--header-height'] = $headerHeight;
?>