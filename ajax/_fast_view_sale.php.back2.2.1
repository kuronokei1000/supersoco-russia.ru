<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	die();
}

$iblockId = isset($_REQUEST['iblock_id']) ? intval($_REQUEST['iblock_id']) : false;
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : false;
?>
<?if(
	$iblockId > 0 && 
	$id > 0 &&
	\Bitrix\Main\Loader::includeModule(VENDOR_MODULE_ID)
):?>
	<?$GLOBALS['APPLICATION']->ShowAjaxHead();?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.detail",
		"popup-sale",
		Array(
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"IBLOCK_TYPE" => "aspro_lite_content",
			"IBLOCK_ID" => $iblockId,
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",
				2 => "PREVIEW_PICTURE",
				3 => "DETAIL_PICTURE",
				4 => "DATE_ACTIVE_FROM",
				5 => "ACTIVE_TO",
				6 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "PERIOD",
				1 => "SALE_NUMBER",
				2 => "REDIRECT",
				3 => "",
			),
			"DETAIL_URL"	=>	"",
			"SECTION_URL"	=>	"",
			"META_KEYWORDS" => "",
			"META_DESCRIPTION" => "",
			"BROWSER_TITLE" => "",
			"DISPLAY_PANEL" => "N",
			"SET_CANONICAL_URL" => "N",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_ELEMENT_CHAIN" => "N",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600000",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
			"USE_PERMISSIONS" => "N",
			"GROUP_PERMISSIONS" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_SHOW_ALL" => "N",
			"CHECK_DATES" => "N",
			"ELEMENT_ID" => $id,
			"ELEMENT_CODE" => "",
			"IBLOCK_URL" => "",
		),
		false
	);?>
<?else:?>
	<div style="padding: 40px 40px 15px 40px;">
		<div class="alert alert-danger">
			<?=\Bitrix\Main\Localization\Loc::getMessage('ERROR_ID_SALE');?>
		</div>
	</div>
<?endif;?>
