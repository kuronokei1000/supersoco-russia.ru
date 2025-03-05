<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?@include_once('under_footer_custom.php');?>

<!-- marketnig popups -->
<?$APPLICATION->IncludeComponent(
	"aspro:marketing.popup.lite", 
	".default", 
	array(),
	false, array('HIDE_ICONS' => 'Y')
);?>
<!-- /marketnig popups -->

<div class="bx_areas"><?TSolution::ShowPageType('bottom_counter');?></div>
<?TSolution::SetMeta();?>
<?TSolution::ShowPageType('search_title_component');?>
<?TSolution\Functions::showBottomPanel();?>
<?TSolution\Notice::showOnAuth();?>
<?TSolution::AjaxAuth();?>
<div id="popup_iframe_wrapper"></div>