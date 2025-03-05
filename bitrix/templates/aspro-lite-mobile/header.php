<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? IncludeTemplateLangFile(__FILE__); ?>
<?global $APPLICATION, $arRegion, $arTheme;?>
<? $bIncludedModule = \Bitrix\Main\Loader::includeModule('aspro.lite'); ?>
<?use \Aspro\Lite\Mobile\General as MSolution?>

<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
	<head>
		<title><?$APPLICATION->ShowTitle()?></title>
		<?if($bIncludedModule):?><?MSolution::start();?><?endif;?>
	</head>
	<body class="site_<?=SITE_ID?> <?=($bIncludedModule ? MSolution::getConditionClass() : '')?>">
		<div class="bx_areas"><?if($bIncludedModule){TSolution::ShowPageType('header_counter');}?></div>

		<?if(!$bIncludedModule):?>
			<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_LITE_TITLE"));?>
			<?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?>
				</body></html>
			<?die();?>
		<?endif;?>

		<div class="layout">
			<div id="panel"><?$APPLICATION->ShowPanel();?></div>
			<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.lite", "", array(), false, ['HIDE_ICONS' => 'Y']);?>
			<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/aspro-lite/defines.php');?>

			<?TSolution::get_banners_position('TOP_HEADER');?>
			<div class="header-container">
				<div id="mobileheader">
					<?MSolution::showPageTypeFromSolution('header_mobile');?>
					<div id="mobilemenu" class="mobile-scroll scrollbar">
						<?MSolution::showPageTypeFromSolution('header_mobile_menu');?>
					</div>
				</div>
			</div>
			<div id="mobilefilter" class="scrollbar-filter"><?$APPLICATION->ShowViewContent('filter_content');?></div>
			<?TSolution::get_banners_position('TOP_UNDERHEADER');?>
			<main id="main">
				<?if(!$isIndex && !$is404 && !$isForm):?>
					<?$APPLICATION->ShowViewContent('section_bnr_content');?>
					<?if($APPLICATION->GetProperty("HIDETITLE")!=='Y'):?>
						<!--title_content-->
						<?MSolution::showPageTypeFromSolution('page_title');?>
						<!--end-title_content-->
					<?endif;?>
					<?$APPLICATION->ShowViewContent('top_section_filter_content');?>
					<?$APPLICATION->ShowViewContent('top_detail_content');?>
				<?endif; // if !$isIndex && !$is404 && !$isForm?>
				
				<?if(!$isIndex):?>
					<?if($APPLICATION->GetProperty("FULLWIDTH")!=='Y'):?>
						<div class="maxwidth-theme">
					<?endif;?>
					<?TSolution::get_banners_position('CONTENT_TOP');?>
				<?endif;?>
				<?TSolution::checkRestartBuffer();?>