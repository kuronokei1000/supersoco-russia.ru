<?
global $arTheme;
$url = ltrim($arTheme['PERSONAL_PAGE_URL']['VALUE'] ?: SITE_DIR.'personal/', SITE_DIR);
?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("footer-subscribe");?>
	<?if(\Bitrix\Main\ModuleManager::isModuleInstalled("subscribe")):?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:subscribe.edit", 
			$options['SUBSCRIBE_TEMPLATE'] ?? "footer",
			array(
				"AJAX_MODE" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_SHADOW" => "Y",
				"AJAX_OPTION_STYLE" => "Y",
				"ALLOW_ANONYMOUS" => "Y",
				"CACHE_TIME" => "36000000",
				"CACHE_TYPE" => "A",
				"COMPOSITE_FRAME_MODE" => "A",
				"COMPOSITE_FRAME_TYPE" => "AUTO",
				"PAGE" => $url."/subscribe/",
				"SET_TITLE" => "N",
				"SHOW_AUTH_LINKS" => "N",
				"SHOW_HIDDEN" => "N",
				"COMPONENT_TEMPLATE" => "footer"
			),
			false
		);?>
	<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("footer-subscribe", "");?>