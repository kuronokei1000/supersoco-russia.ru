<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('viewed-block');?>
<div class="catalog-viewed">
	<div class="catalog-viewed__inner">
		<?$APPLICATION->IncludeComponent(
			"aspro:catalog.viewed.lite",
			"main",
			array(
				"TITLE_BLOCK" => "",
				"SHOW_MEASURE" => "Y",
				"CACHE_TYPE" => "N",
			),
			false
		);?>
	</div>
</div>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('viewed-block', '');?>