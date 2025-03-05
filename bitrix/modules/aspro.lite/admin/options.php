<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	CLite as Solution;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$moduleClass = "CLite";
$moduleAdminClass = "\Aspro\Functions\CAsproLiteAdmin";
$moduleID = "aspro.lite";
Loader::includeModule($moduleID);

$arHideProps = array(
	"YANDEX_MARKET_MAIN",
	"YANDEX_MARKET_SORT",
	"YANDEX_MARKET_GRADE",
);

$RIGHT = $APPLICATION->GetGroupRight($moduleID);
if($RIGHT >= "R"){
	$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/style.css");
	$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/spectrum.css");
	$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/js/main/loader/loader.css");

	$context=\Bitrix\Main\Application::getInstance()->getContext();
	$request=$context->getRequest();
	$arPost = $request->getPostList()->toArray();
	$arPost = $APPLICATION->ConvertCharsetArray($arPost, 'UTF-8', LANG_CHARSET);

	if(isset($arPost["q"]))
	{
		$arPost["q"] = ltrim($arPost["q"]);
		$arPost["q"] = rtrim($arPost["q"]);
	}

	$bSearchMode = false;
	$bFunctionExists = (function_exists('mb_strtolower'));
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" && $arPost["q"])
	{
		$strSearchWord = ($bFunctionExists ? mb_strtolower($arPost["q"], LANG_CHARSET) : strtolower($arPost["q"]));
		$bSearchMode = true;
	}

	$arSiteFilter = array("ACTIVE"=>"Y");
	if($bSearchMode){
		$arSiteFilter["LID"] = $arPost["site"];
	}

	$by = "id";
	$sort = "asc";

	$arSites = array();
	$db_res = CSite::GetList($by, $sort, $arSiteFilter);
	while($res = $db_res->Fetch()){
		$arSites[] = $res;
	}

	$arTabsForView = COption::GetOptionString($moduleID, 'TABS_FOR_VIEW_ASPRO_LITE', '');
	if($arTabsForView){
		$arTabsForView = explode(',' , $arTabsForView);
	}
	
	$arTabs = array();

	// save initial webforms options
	$arWebFormsOptions = array(
		'init' => Solution::$arParametrsList['WEB_FORMS']['OPTIONS'],
	);

	foreach($arSites as $key => $arSite){
		if(
			(
				$arTabsForView &&
				in_array($arSite['ID'], $arTabsForView)
			)
			||
			(
				!$arTabsForView &&
				Option::get($moduleID, "SITE_INSTALLED", "N", $arSite["ID"]) == 'Y'
			)
		){
			$arBackParametrs = Solution::GetBackParametrsValues($arSite["ID"], '', false);

			// save webforms options for site
			$arWebFormsOptions[$arSite["ID"]] = Solution::$arParametrsList['WEB_FORMS']['OPTIONS'];
			// restore initial webforms options
			Solution::$arParametrsList['WEB_FORMS']['OPTIONS'] = $arWebFormsOptions['init'];

			$arTabs[] = array(
				"DIV" => "edit".($key+1),
				"TAB" => GetMessage("MAIN_OPTIONS_SITE_TITLE", array("#SITE_NAME#" => $arSite["NAME"], "#SITE_ID#" => $arSite["ID"])),
				"ICON" => "settings",
				"PAGE_TYPE" => "site_settings",
				"SITE_ID" => $arSite["ID"],
				"SITE_DIR" => $arSite["DIR"],
				"TEMPLATE" => Solution::GetSiteTemplate($arSite["ID"]),
				"OPTIONS" => $arBackParametrs,
			);
			
		}
	}

	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT >= "W" && check_bitrix_sessid()){
		global $APPLICATION, $CACHE_MANAGER;

		if(strlen($RestoreDefaults) > 0){
			Option::delete(Solution::moduleID);
			Option::delete(Solution::moduleID, array("name" => "NeedGenerateCustomTheme"));
			Option::delete(Solution::moduleID, array("name" => "NeedGenerateCustomThemeBG"));
			$APPLICATION->DelGroupRight(Solution::moduleID);
		}
		else{
			Option::delete(Solution::moduleID, array("name" => "sid"));
			
			foreach($arTabs as $key => $arTab){
				$optionsSiteID = $arTab["SITE_ID"];

				// reset all SESSION values exclude THEME_VIEW_COLOR
				if (
					is_array($_SESSION['THEME']) &&
					is_array($_SESSION['THEME'][$optionsSiteID])
				) {
					$themeViewColor = $_SESSION['THEME'][$optionsSiteID]['THEME_VIEW_COLOR'] ?? 'DEFAULT';
					$_SESSION['THEME'][$optionsSiteID] = [
						'THEME_VIEW_COLOR' => $themeViewColor,
					];
				}

				$asproAdminController = new $moduleAdminClass($_REQUEST, $optionsSiteID, $arTab["OPTIONS"]);

				// restore webforms options for site
				Solution::$arParametrsList['WEB_FORMS']['OPTIONS'] = $arWebFormsOptions[$optionsSiteID];
				
				foreach(Solution::$arParametrsList as $blockCode => $arBlock){
					if(in_array($blockCode, $arHideProps)) continue;

					foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
						if($arOption['TYPE'] === 'array'){
							$arOptionsRequiredKeys = array();
							$arOptionsKeys = array_keys($arOption['OPTIONS']);
							$itemsKeysCount = Option::get($moduleID, $optionCode, '0', $optionsSiteID);
							$fullKeysCount = 0;

							if($arOption['OPTIONS'] && is_array($arOption['OPTIONS'])){
								foreach($arOption['OPTIONS'] as $_optionCode => $_arOption){
									if(strlen($_arOption['REQUIRED']) && $_arOption['REQUIRED'] === 'Y'){
										$arOptionsRequiredKeys[] = $_optionCode;
									}
								}

								for($itemKey = 0, $cnt = $itemsKeysCount + 50; $itemKey <= $cnt; ++$itemKey){
									$bFull = true;
									if($arOptionsRequiredKeys){
										foreach($arOptionsRequiredKeys as $_optionCode){
											if(!strlen($_REQUEST[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID])){
												$bFull = false;
												break;
											}
										}
									}
									if($bFull){
										foreach($arOptionsKeys as $_optionCode){
											$newOptionValue = $_REQUEST[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID];
											Option::set($moduleID, $optionCode.'_array_'.$_optionCode.'_'.$fullKeysCount, $newOptionValue, $optionsSiteID);
											unset($_REQUEST[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID]);
											unset($_FILES[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID]);
										}

										++$fullKeysCount;
									}
								}
							}

							Option::set($moduleID, $optionCode, $fullKeysCount, $optionsSiteID);
						}
						else{
							$newVal = $_REQUEST[$optionCode."_".$optionsSiteID];
							$asproAdminController->setOption($optionCode, $arOption, '', $newVal);
						}
					}
				}

				$arTab['OPTIONS'] = $asproAdminController->tabOptions;

				CBitrixComponent::clearComponentCache('bitrix:catalog.element', $optionsSiteID);
				CBitrixComponent::clearComponentCache('bitrix:form.result.new', $optionsSiteID);
				CBitrixComponent::clearComponentCache('bitrix:catalog.section', $optionsSiteID);
				CBitrixComponent::clearComponentCache('bitrix:catalog.section.list', $optionsSiteID);
				CBitrixComponent::clearComponentCache('bitrix:news.list', $optionsSiteID);
				CBitrixComponent::clearComponentCache('bitrix:news.detail', $optionsSiteID);
				CBitrixComponent::clearComponentCache('bitrix:menu', $optionsSiteID);
				CBitrixComponent::clearComponentCache('aspro:com.banners.lite', $optionsSiteID);
				CBitrixComponent::clearComponentCache('aspro:social.info.lite', $optionsSiteID);
				$arTabs[$key] = $arTab;
			}
		}

		// clear composite cache
		if($compositeMode = Solution::IsCompositeEnabled())
		{
			$arHTMLCacheOptions = Solution::GetCompositeOptions();
			$obCache = new CPHPCache();
			$obCache->CleanDir('', 'html_pages');
			Solution::EnableComposite($compositeMode === 'AUTO_COMPOSITE', $arHTMLCacheOptions);
		}

		// send statistics
		if(\Aspro\Lite\GS::isEnabled()){
			if(\Aspro\Lite\GS::register()){
				\Aspro\Lite\GS::sendData(
					\Aspro\Lite\GS::mkData(array('sites', 'options'))
				);
			}
		}

		$APPLICATION->RestartBuffer();
	}

	CJSCore::Init(array("jquery"));
	$GLOBALS['APPLICATION']->AddHeadScript("/bitrix/js/".$moduleID."/spectrum.js");
	$GLOBALS['APPLICATION']->AddHeadScript("/bitrix/js/".$moduleID."/jquery.splendid.textchange.js");
	$GLOBALS['APPLICATION']->AddHeadScript("/bitrix/js/".$moduleID."/sort/Sortable.js");
	?>
	<?if(!count($arTabs)):?>
		<div class="adm-info-message-wrap adm-info-message-red">
			<div class="adm-info-message">
				<div class="adm-info-message-title"><?=GetMessage("ASPRO_ALLCORP_3_NO_SITE_INSTALLED", array("#SESSION_ID#"=>bitrix_sessid_get()))?></div>
				<div class="adm-info-message-icon"></div>
			</div>
			<a href="aspro.lite_options_tabs.php?lang=<?=LANGUAGE_ID?>" id="tabs_settings" target="_blank">
				<span>
					<?=GetMessage('TABS_SETTINGS')?>
				</span>
			</a>
		</div>
	<?else:?>
		<?
		$context = new CAdminContextMenu(
			array(
				array(
					'TEXT' => Loc::GetMessage('TABS_SETTINGS'),
					'LINK' => 'aspro.lite_options_tabs.php?lang='.LANGUAGE_ID,
				)
			)
		);
		$context->Show();
		?>
		<form method="post" class="max_options views" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?=bitrix_sessid_post();?>
		<?$tabControl->Begin();?>
		<?
		if( CModule::IncludeModule('sale') ) {
			$arPersonTypes = $arDeliveryServices = $arPaySystems = $arCurrency = $arOrderPropertiesByPerson = $arS = $arC = $arN = array();
			$dbRes = CSalePersonType::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'), false, false, array());
			while($arItem = $dbRes->Fetch()){
				if($arItem['LIDS'] && is_array($arItem['LIDS'])){
					foreach($arItem['LIDS'] as $site_id){
						$arPersonTypes[$site_id][$arItem['ID']] = '['.$arItem['ID'].'] '.$arItem['NAME'].' ('.$site_id.')';
					}
				}
				$arS[$arItem['ID']] = array('FIO', 'PHONE', 'EMAIL');
				$arN[$arItem['ID']] = array(
					'FIO' => GetMessage('ONECLICKBUY_PROPERTIES_FIO'),
					'PHONE' => GetMessage('ONECLICKBUY_PROPERTIES_PHONE'),
					'EMAIL' => GetMessage('ONECLICKBUY_PROPERTIES_EMAIL'),
				);
			}

			foreach($arTabs as $key => $arTab)
			{
				if($arTab["SITE_ID"])
				{
					$dbRes = CSaleDelivery::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y', 'LID' => $arTab["SITE_ID"]), false, false, array());
					while($arItem = $dbRes->Fetch())
					{
						$arDeliveryServices[$arTab["SITE_ID"]][$arItem['ID']] = '['.$arItem['ID'].'] '.$arItem['NAME'].' ('.$arTab["SITE_ID"].')';
					}
				}
			}

			$dbRes = CSalePaySystem::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'), false, false, array());
			while($arItem = $dbRes->Fetch())
			{
				$arPaySystems[$arItem['ID']] = '['.$arItem['ID'].'] '.$arItem['NAME'];
			}

			$dbRes = CCurrency::GetList(($by = "sort"), ($order = "asc"), LANGUAGE_ID);
			while($arItem = $dbRes->Fetch())
			{
				$arCurrency[$arItem['CURRENCY']] = $arItem['FULL_NAME'].' ('.$arItem['CURRENCY'].')';
			}

			$dbRes = CSaleOrderProps::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'), false, false, array('ID', 'CODE', 'NAME', 'PERSON_TYPE_ID', 'TYPE', 'IS_PHONE', 'IS_EMAIL', 'IS_PAYER'));
			while($arItem = $dbRes->Fetch())
			{
				if($arItem['TYPE'] === 'TEXT' || $arItem['TYPE'] === 'FILE' && strlen($arItem['CODE']))
				{
					$arN[$arItem['PERSON_TYPE_ID']][$arItem['CODE']] = $arItem['NAME'];
					if($arItem['IS_PAYER'] === 'Y')
						$arS[$arItem['PERSON_TYPE_ID']][0] = $arItem['CODE'];
					elseif($arItem['IS_PHONE'] === 'Y')
						$arS[$arItem['PERSON_TYPE_ID']][1] = $arItem['CODE'];
					elseif($arItem['IS_EMAIL'] === 'Y')
						$arS[$arItem['PERSON_TYPE_ID']][2] = $arItem['CODE'];
					else
						$arS[$arItem['PERSON_TYPE_ID']][] = $arItem['CODE'];
				}
			}
			if($arS && $arN)
			{
				foreach($arS as $PERSON_TYPE_ID => $arCodes)
				{
					if($arCodes)
					{
						foreach($arCodes as $CODE)
							$arOrderPropertiesByPerson[$PERSON_TYPE_ID][$CODE] = $arN[$PERSON_TYPE_ID][$CODE];

						$arOrderPropertiesByPerson[$PERSON_TYPE_ID]['COMMENT'] = GetMessage('ONECLICKBUY_PROPERTIES_COMMENT');
					}
				}
			}
		}

		// sku sort fields
		$arSort = \CIBlockParameters::GetElementSortFields(
			array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
			array('KEY_LOWERCASE' => 'Y')
		);

		if (Solution::isSaleMode()) {
			// replace one click buy option only for admin page!!!
			if (Solution::$arParametrsList['ONECLICKBUY']) {
				unset(Solution::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SHOW_ONE_CLICK_BUY']['GROUP_BLOCK']);

				Solution::$arParametrsList['ONECLICKBUY']['OPTIONS'] = array_merge(
					[
						'SHOW_ONE_CLICK_BUY' => Solution::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SHOW_ONE_CLICK_BUY'],
					],
					Solution::$arParametrsList['ONECLICKBUY']['OPTIONS']
				);
				unset(Solution::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SHOW_ONE_CLICK_BUY']);
			}

			// catalog sku sort fields
			if (Loader::includeModule('catalog')) {
				$arSort = array_merge(
					$arSort,
					\CCatalogIBlockParameters::GetCatalogSortFields(),
				);
				unset($arSort['CATALOG_AVAILABLE']);
			}
		}

		Solution::$arParametrsList['CATALOG_PAGE']['OPTIONS']['CATALOG_SKU_SORT_FIELD']['LIST'] = $arSort;
		Solution::$arParametrsList['CATALOG_PAGE']['OPTIONS']['CATALOG_SKU_SORT_FIELD2']['LIST'] = $arSort;

		$bGroupsBlockContact = $bGroupsBlockCounters = false;
		foreach(Solution::$arParametrsList as $keyGroup => $arGroup)
		{
			if($arGroup["OPTIONS"])
			{
				foreach($arGroup["OPTIONS"] as $keyOption => $arTmpOption)
				{
					if($bSearchMode)
					{
						if($keyOption == "PAGE_CONTACTS")
						{
							$arTmpOption["TITLE"] = GetMessage("BLOCK_VIEW_TITLE");
							Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["TITLE"] = GetMessage("BLOCK_VIEW_TITLE");
						}

						//find items
						$strTitle = ($bFunctionExists ? mb_strtolower($arTmpOption["TITLE"], LANG_CHARSET) : strtolower($arTmpOption["TITLE"]));
						if(stripos($strTitle, $strSearchWord) !== false)
						{
							$arTmpOption["SEARCH_FIND"] = true;
							Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;

							if(strpos($keyOption, "CONTACTS") !== false)
							{
								if($keyOption == "CONTACTS_MAP_NOTE" || $keyOption == "CONTACTS_USE_FEEDBACK")
								{
									if(in_array(Option::get($moduleID, "CONTACTS_MAP_NOTE", 1, $arPost['site']), [1, 2]))
										$bGroupsBlockContact = true;
								}
								else
									$bGroupsBlockContact = true;
							}
						}

						// add find item for dependent groups
						if(isset($arTmpOption["DEPENDENT_PARAMS"]))
						{
							$bFind = false;
							foreach($arTmpOption["DEPENDENT_PARAMS"] as $keyOption2 => $arTmpOption2)
							{
								$strTitle = ($bFunctionExists ? mb_strtolower($arTmpOption2["TITLE"], LANG_CHARSET) : strtolower($arTmpOption2["TITLE"]));
								if(stripos($strTitle, $strSearchWord) !== false)
								{
									$arTmpOption2["SEARCH_FIND"] = true;
									$arTmpOption["DEPENDENT_PARAMS"][$keyOption2]["SEARCH_FIND"] = true;
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["DEPENDENT_PARAMS"][$keyOption2]["SEARCH_FIND"] = true;
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
									$bFind = true;
								}
							}
							if(strpos($keyOption, "YA_GOALS") !== false && $bFind)
							{
								$arTmpOption["SEARCH_FIND"] = true;
							}
						}

						// add find item for social group
						if($keyGroup == "SOCIAL")
						{
							$strTitle = ($bFunctionExists ? mb_strtolower($arGroup["TITLE"], LANG_CHARSET) : strtolower($arGroup["TITLE"]));
							if(stripos($strTitle, $strSearchWord) !== false)
							{
								$arTmpOption["SEARCH_FIND"] = true;
								Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
							}
						}

						if(isset($arTmpOption["SUB_PARAMS"]))
						{
							$strGroupTitle = GetMessage("SUB_PARAMS");
							$strTitle = ($bFunctionExists ? mb_strtolower($strGroupTitle, LANG_CHARSET) : strtolower($strGroupTitle));
							if(stripos($strTitle, $strSearchWord) !== false)
							{
								$arTmpOption["SEARCH_FIND"] = true;
								Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
							}

							$strGroupTitle = GetMessage("FRONT_TEMPLATE_GROUP");
							$strTitle = ($bFunctionExists ? mb_strtolower($strGroupTitle, LANG_CHARSET) : strtolower($strGroupTitle));
							if(stripos($strTitle, $strSearchWord) !== false)
							{
								$arTmpOption["SEARCH_FIND"] = true;
								Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
							}
							$indexType = Option::get($moduleID, "INDEX_TYPE", "index1", $arPost['site']);
							foreach($arTmpOption["SUB_PARAMS"][$indexType] as $keyOption2 => $arTmpOption2)
							{
								$strTitle = ($bFunctionExists ? mb_strtolower($arTmpOption2["TITLE"], LANG_CHARSET) : strtolower($arTmpOption2["TITLE"]));
								if(stripos($strTitle, $strSearchWord) !== false)
								{
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SUB_PARAMS"][$keyOption2]["SEARCH_FIND"] = true;
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
								}
								if(isset($arTmpOption2["TEMPLATE"]))
								{
									$strTitle = ($bFunctionExists ? mb_strtolower($arTmpOption2["TEMPLATE"]["TITLE"], LANG_CHARSET) : strtolower($arTmpOption2["TEMPLATE"]["TITLE"]));
									if(stripos($strTitle, $strSearchWord) !== false)
									{
										Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
									}
								}
							}
						}
					}

					if(isset($arTmpOption["GROUP_BLOCK"]))
					{
						$strGroupTitle = GetMessage($arTmpOption["GROUP_BLOCK"]);
						if(!Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption["GROUP_BLOCK"]])
						{
							Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption["GROUP_BLOCK"]]["TITLE"] = $strGroupTitle;
							if(isset($arTmpOption["GROUP_BLOCK_LINE"]))
								Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption["GROUP_BLOCK"]]["ONE_BLOCK"] = "Y";
						}
						if($bSearchMode)
						{
							$strTitle = ($bFunctionExists ? mb_strtolower($strGroupTitle, LANG_CHARSET) : strtolower($strGroupTitle));
							if(stripos($strTitle, $strSearchWord) !== false)
							{
								$arTmpOption["SEARCH_FIND"] = true;
							}
						}
						Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption["GROUP_BLOCK"]]["ITEMS"][$keyOption] = $arTmpOption;
						unset(Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]);
					}
					if(isset($arTmpOption["DEPENDENT_PARAMS"]))
					{
						foreach($arTmpOption["DEPENDENT_PARAMS"] as $keyOption2 => $arTmpOption2)
						{
							if(isset($arTmpOption2["GROUP_BLOCK"]))
							{
								$strGroupTitle = GetMessage($arTmpOption2["GROUP_BLOCK"]);
								if($bSearchMode)
								{
									$strTitle = ($bFunctionExists ? mb_strtolower($strGroupTitle, LANG_CHARSET) : strtolower($strGroupTitle));
									if(stripos($strTitle, $strSearchWord) !== false)
									{
										$arTmpOption2["SEARCH_FIND"] = true;
									}
								}

								if(!Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption2["GROUP_BLOCK"]])
								{
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption2["GROUP_BLOCK"]]["TITLE"] = $strGroupTitle;
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption2["GROUP_BLOCK"]]["PARENT"] = $keyOption;
									if(isset($arTmpOption2["GROUP_BLOCK_LINE"]))
										Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption2["GROUP_BLOCK"]]["ONE_BLOCK"] = "Y";
								}

								Solution::$arParametrsList[$keyGroup]["OPTIONS"][$arTmpOption2["GROUP_BLOCK"]]["ITEMS"][$keyOption2] = $arTmpOption2;
								unset(Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["DEPENDENT_PARAMS"][$keyOption2]);
							}
						}
					}
				}
			}
		}

		unset(Solution::$arParametrsList["YANDEX_MARKET_MAIN"]);
		unset(Solution::$arParametrsList["YANDEX_MARKET_SORT"]);
		unset(Solution::$arParametrsList["YANDEX_MARKET_GRADE"]);

		Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP2"]["ONE_BLOCK"] = "Y";
		Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP4"]["ONE_BLOCK"] = "Y";

		/* required props */
		$arRequiredOptions = array(
			"BASE_COLOR_GROUP" => Solution::$arParametrsList["MAIN"]["OPTIONS"]["BASE_COLOR_GROUP"],
			"LOGO_GROUP" => Solution::$arParametrsList["MAIN"]["OPTIONS"]["LOGO_GROUP"],
			"HEADER_PHONES" => Solution::$arParametrsList["HEADER"]["OPTIONS"]["HEADER_PHONES"],
			"SOCIAL" => array(
				"TITLE" => GetMessage("SOCIAL_OPTIONS"),
				"ONE_BLOCK" => "Y",
				"ITEMS" => Solution::$arParametrsList["SOCIAL"]["OPTIONS"]
			),
			"SOCIAL_VIBER_OPTIONS" => array(
				"TITLE" => GetMessage("SOCIAL_VIBER_OPTIONS"),
				"ITEMS" => Solution::$arParametrsList["SOCIAL_VIBER_OPTIONS"]["OPTIONS"],
			),
			"SOCIAL_WHATS_OPTIONS" => array(
				"TITLE" => GetMessage("SOCIAL_WHATS_OPTIONS"),
				"ITEMS" => Solution::$arParametrsList["SOCIAL_WHATS_OPTIONS"]["OPTIONS"],
			),
			"SECTION_CONTACTS_GROUP" => Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP"],
			"SECTION_CONTACTS_GROUP3" => Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP3"],
			"SECTION_CONTACTS_GROUP4" => Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP4"],
			"SECTION_CONTACTS_GROUP2" => Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP2"],
			"COUNTERS_GOALS_GROUP" => Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP"],
			"COUNTERS_GOALS_GROUP2" => Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP2"],
			"COUNTERS_GOALS_GROUP3" => Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP3"],
			"COUNTERS_GOALS_GROUP4" => Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP4"],
		);

		unset(Solution::$arParametrsList["MAIN"]["OPTIONS"]["BASE_COLOR_GROUP"]);
		unset(Solution::$arParametrsList["MAIN"]["OPTIONS"]["LOGO_GROUP"]);
		unset(Solution::$arParametrsList["HEADER"]["OPTIONS"]["HEADER_PHONES"]);
		unset(Solution::$arParametrsList["SOCIAL"]);

		unset(Solution::$arParametrsList["SOCIAL_VIBER_OPTIONS"]);
		unset(Solution::$arParametrsList["SOCIAL_WHATS_OPTIONS"]);

		unset(Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP"]);
		unset(Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP2"]);
		unset(Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP3"]);
		unset(Solution::$arParametrsList["SECTION"]["OPTIONS"]["SECTION_CONTACTS_GROUP4"]);
		unset(Solution::$arParametrsList["COUNTERS_GOALS"]);
		unset(Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP2"]);
		unset(Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP3"]);
		unset(Solution::$arParametrsList["COUNTERS_GOALS"]["OPTIONS"]["COUNTERS_GOALS_GROUP4"]);

		array_unshift(Solution::$arParametrsList, array(
			"TITLE" => GetMessage("ASPRO_SOLUTION_REQUIRED_FIELDS"),
			"CODE" => "REQUIRED",
			"OPTIONS" => $arRequiredOptions,
		));

		if($bSearchMode)
		{
			foreach(Solution::$arParametrsList as $keyGroup => $arGroup)
			{
				if($arGroup["OPTIONS"])
				{
					foreach($arGroup["OPTIONS"] as $keyOption => $arTmpOption)
					{
						$strTitle = ($bFunctionExists ? mb_strtolower($arGroup["TITLE"], LANG_CHARSET) : strtolower($arGroup["TITLE"]));
						if(isset($arTmpOption["ITEMS"]))
						{
							foreach($arTmpOption["ITEMS"] as $keyOption2 => $arTmpOption2)
							{
								// add find item for contact group
								if($bGroupsBlockContact && strpos($keyOption2, "CONTACTS") !== false)
								{
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["ITEMS"][$keyOption2]["SEARCH_FIND"] = true;
								}

								if(stripos($strTitle, $strSearchWord) !== false)
								{
									Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["ITEMS"][$keyOption2]["SEARCH_FIND"] = true;
								}

								/*if($keyOption == "GOOGLE_RECAPTCHA_GROUP" && $keyOption2 == "USE_GOOGLE_RECAPTCHA")
								{
									$bGroupsBlockGcaptcha = false;
									foreach($arTmpOption["ITEMS"] as $keyOption22 => $arTmpOption22)
									{
										if($arTmpOption22["SEARCH_FIND"])
											$bGroupsBlockGcaptcha = true;
									}
									if($bGroupsBlockGcaptcha)
									{
										Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["ITEMS"][$keyOption2]["SEARCH_FIND"] = true;
									}
								}*/

								if($arTmpOption2["DEPENDENT_PARAMS"])
								{
									/*if($keyOption2 == "GOOGLE_ECOMERCE")
									{*/
										foreach($arTmpOption2["DEPENDENT_PARAMS"] as $keyOption3 => $arTmpOption3)
										{
											$strGroupTitle = $arTmpOption3["TITLE"];

											$strTitle = ($bFunctionExists ? mb_strtolower($strGroupTitle, LANG_CHARSET) : strtolower($strGroupTitle));
											if(stripos($strTitle, $strSearchWord) !== false)
											{
												Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["ITEMS"][$keyOption2]["SEARCH_FIND"] = true;
											}
										}
									//}
								}

								if(isset($arTmpOption2["LIST"]) && $arTmpOption2["LIST"])
								{
									foreach($arTmpOption2["LIST"] as $key => $value)
									{
										$value = ((is_array($value) && isset($value["TITLE"])) ? $value["TITLE"] : $value);
										$strTitle = ($bFunctionExists ? mb_strtolower($value, LANG_CHARSET) : strtolower($value));
										if(stripos($strTitle, $strSearchWord) !== false)
										{
											Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["ITEMS"][$keyOption2]["SEARCH_FIND"] = true;
										}
									}
								}
							}
						}
						else
						{
							if(stripos($strTitle, $strSearchWord) !== false)
							{
								Solution::$arParametrsList[$keyGroup]["OPTIONS"][$keyOption]["SEARCH_FIND"] = true;
							}
						}
					}
				}
			}
		}

		foreach($arTabs as $key => $arTab)
		{
			$tabControl->BeginNextTab();
			if($arTab["SITE_ID"])
			{
				$optionsSiteID = $arTab["SITE_ID"];
				$optionsSiteDir = $arTab["SITE_DIR"];

				// restore webforms options for site
				Solution::$arParametrsList['WEB_FORMS']['OPTIONS'] = array();
				foreach($arWebFormsOptions[$optionsSiteID] as $optionCode => $arOption){
					if(strpos($optionCode, 'EXPRESS_BUTTON') !== false){
						if(!isset(Solution::$arParametrsList['WEB_FORMS']['OPTIONS']['EXPRESS_BUTTON_GROUP'])){
							Solution::$arParametrsList['WEB_FORMS']['OPTIONS']['EXPRESS_BUTTON_GROUP'] = array(
								'TITLE' => GetMessage('EXPRESS_BUTTON_GROUP'),
								'ONE_BLOCK' => 'Y',
								'ITEMS' => array()
							);
						}

						Solution::$arParametrsList['WEB_FORMS']['OPTIONS']['EXPRESS_BUTTON_GROUP']['ITEMS'][$optionCode] = $arOption;
					}
					elseif( strpos($optionCode, 'ASPRO_LITE_') !== false ){
						if(!isset(Solution::$arParametrsList['WEB_FORMS']['OPTIONS']['FORMS_OPTIONS_GROUP'])){
							Solution::$arParametrsList['WEB_FORMS']['OPTIONS']['FORMS_OPTIONS_GROUP'] = array(
								'TITLE' => GetMessage('FORMS_OPTIONS_GROUP'),
								'ONE_BLOCK' => 'N',
								'ITEMS' => array()
							);
						}
						Solution::$arParametrsList['WEB_FORMS']['OPTIONS']['FORMS_OPTIONS_GROUP']['ITEMS'][$optionCode] = $arOption;
					}
					else{
						Solution::$arParametrsList['WEB_FORMS']['OPTIONS'][$optionCode] = $arOption;
					}
				}
				?>
				<tr>
					<td colspan="2" class="site_<?=$optionsSiteID;?>" data-siteid="<?=$optionsSiteID;?>" data-sitedir="<?=$optionsSiteDir;?>">

						<?/* set border color from site */?>
						<?$themeColor = '#546772';
						$colorBase = \Bitrix\Main\Config\Option::get($moduleID, 'BASE_COLOR', '', $optionsSiteID);
						$colorCustom = \Bitrix\Main\Config\Option::get($moduleID, 'BASE_COLOR_CUSTOM', '', $optionsSiteID);
						if($colorBase !== 'CUSTOM')
							$themeColor = $arRequiredOptions["BASE_COLOR_GROUP"]["ITEMS"]["BASE_COLOR"]["LIST"][$colorBase]["COLOR"];
						else
							$themeColor = $colorCustom;
						$themeColor = str_replace('#', '', $themeColor);?>
						<?if($themeColor):?>
							<?$APPLICATION->AddHeadString('<style>.site_'.$optionsSiteID.' .status-block.current,.site_'.$optionsSiteID.' .current .status-block{border-color:#'.$themeColor.' !important;}.site_'.$optionsSiteID.' .tabs-wrapper .tabs-heading > .head.active:before,.site_'.$optionsSiteID.' .colored_theme_bg{background:#'.$themeColor.' !important;}</style>',true)?>
						<?endif;?>
						<?/**/?>

						<div class="tabs-wrapper">
							<div class="search_wrapper">
								<div class="search_wrapper_inner">
									<input type="text" size="" maxlength="255" value="" name="SEARCH_CONFIG" data-site="<?=$optionsSiteID;?>" placeholder="<?=GetMessage("FILTER_SEARCH");?>">
									<div class="buttons">
										<div class="search" title="<?=GetMessage("SEARCH_CLICK");?>"></div>
										<div class="delete" title="<?=GetMessage("REMOVE_CLICK");?>"></div>
									</div>
								</div>
							</div>

							<?if($bSearchMode):?>
								<?//$APPLICATION->RestartBuffer();?>
							<?endif;?>

							<div class="tabs">
								<div class="main-grid-loader-container">
									<div class="main-ui-loader main-ui-show">
										<svg class="main-ui-loader-svg" viewBox="25 25 50 50"><circle class="main-ui-loader-svg-circle" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
									</div>
								</div>
								<div class="main-grid-empty-inner"><div class="main-grid-empty-image"></div><div class="main-grid-empty-text"><?=GetMessage("NOTHING_FOUND");?></div></div>

								<div class="tabs-heading-wrapper">
									<div class="tabs-heading <?=($bSearchMode ? 'searched' : '');?>">
										<div class="head<?=($_COOKIE['activeTabShare_site_'.$optionsSiteID] ? ' active' : '')?>" data-code="SHARE"><?=Loc::getMessage('SHAREPRESET_TITLE')?></div>
									</div>

									<div class="tabs-heading <?=($bSearchMode ? 'searched' : '');?>">
										<?$i = 0;?>
										<?foreach(Solution::$arParametrsList as $blockCode => $arBlock):?>
											<?if(isset($arBlock["CODE"]) && !$blockCode)
												$blockCode = "REQUIRED";?>

											<?if(in_array($blockCode, $arHideProps)) continue;?>

											<?$bTabActive = !$_COOKIE['activeTabShare_site_'.$optionsSiteID] && ((!$_COOKIE['activeTab_site_'.$optionsSiteID] && !$i) || $_COOKIE['activeTab_site_'.$optionsSiteID] == $i);?>
											<div class="head <?=($bTabActive ? "active" : "")?>" data-code="<?=$blockCode?>">
												<?=$arBlock["TITLE"]?>
											</div>
											<?$i++;?>
										<?endforeach;?>
									</div>
								</div>

								<div class="tabs-content <?=($bSearchMode ? 'searched' : '');?>">
									<div class="tab tab-sharepreset<?=($_COOKIE['activeTabShare_site_'.$optionsSiteID] ? ' active' : '')?>" data-prop_code="SHARE">
										<div class="form">
											<?ob_start();?>
											<div class="options">
												<?$arBlockCodes = array();?>
												<?foreach(Solution::$arParametrsList as $blockCode => $arBlock):?>
													<?if($arBlock['THEME'] === 'Y'):?>
														<?$arBlockCodes[] = $blockCode;?>
														<div class="inner_wrapper checkbox">
															<div class="title_wrapper">
																<div class="subtitle"><label for="sharepreset_block_<?=$blockCode.'_'.$optionsSiteID?>"><?=$arBlock['TITLE']?></label></div>
															</div>
															<div class="value_wrapper">
																<input type="checkbox" value="<?=$blockCode?>" id="sharepreset_block_<?=$blockCode.'_'.$optionsSiteID?>" checked />
															</div>
														</div>
													<?endif;?>
												<?endforeach;?>
												<input type="hidden" name="sharepreset_blocks_<?=$optionsSiteID?>" value="<?=implode(',', $arBlockCodes)?>" maxlength="1000" />
											</div>
											<?$htmlBlocks = ob_get_clean();?>

											<div class="title bg"><?=Loc::getMessage('SHAREPRESET_TITLE')?></div>

											<div class="notes-block">
												<div align="center">
													<?=BeginNote('align="center"');?>
													<?=(Loc::getMessage('SHAREPRESET_IMPORT_NOTE'))?>
													<?=EndNote();?>
												</div>
											</div>

											<div class="sharepreset-error" style="display:none;"><?=CAdminMessage::ShowMessage('Error')?></div>

											<div class="groups_block block tab-share--export">
												<div class="title"><?=Loc::getMessage('SHAREPRESET_EXPORT_TITLE')?></div>
												<div class="block_wrapper">
													<div class="item js_block text">
														<div class="js_block1">
															<div class="title_wrapper">
																<a href="" class="sharepreset-blocks-toggle"><?=Loc::getMessage(
																	'SHAREPRESET_EXPORT_BLOCKS_TOGGLE',
																	array(
																		'#COUNT#' => count($arBlockCodes),
																		'#ALLCOUNT#' => count($arBlockCodes),
																	)
																)?></a>
															</div>

															<div class="sharepreset-blocks" style="display:none;">
																<div class="sharepreset-blocks-inner">
																	<div class="sharepreset-blocks__actions">
																		<a href="" rel="nofollow" class="dark_link sharepreset-blocks__action sharepreset-blocks__action--select-all"><span class="dotted"><?=Loc::getMessage('SHAREPRESET_EXPORT_BLOCKS_SELECT_ALL')?></span></a>
																		<a href="" rel="nofollow" class="dark_link sharepreset-blocks__action sharepreset-blocks__action-reset-all"><span class="dotted"><?=Loc::getMessage('SHAREPRESET_EXPORT_BLOCKS_RESET_ALL')?></span></a>
																	</div>
																</div>
																<?=$htmlBlocks?>
															</div>
														</div>
													</div>
													<div class="item js_block text">
														<div class="js_block1">
															<div class="title_wrapper">
																<div class="subtitle"><?=Loc::getMessage('SHAREPRESET_EXPORT_FILE')?></div>
															</div>
															<a href="" class="adm-btn" data-action="exportToFile"><?=Loc::getMessage('SHAREPRESET_EXPORT_FILE_BUTTON')?></a>
														</div>
													</div>
												</div>
											</div>
											<div class="groups_block block tab-share--import">
												<div class="title"><?=Loc::getMessage('SHAREPRESET_IMPORT_TITLE')?></div>
												<div class="block_wrapper">
													<div class="item js_block text">
														<div class="js_block1">
															<div class="title_wrapper">
																<div class="subtitle"><?=Loc::getMessage('SHAREPRESET_IMPORT_LINK')?></div>
															</div>
															<div class="value_wrapper">
																<input type="text" name="sharepreset_link_<?=$optionsSiteID?>" value="" autocomplete="off" placeholder="" maxlength="50" />
															</div>
															<a href="" class="adm-btn" data-action="importFromLink"><?=Loc::getMessage('SHAREPRESET_IMPORT_APPLY_BUTTON')?></a>
														</div>
													</div>
													<div class="item js_block text with-hint">
														<div class="js_block1">
															<div class="title_wrapper">
																<div class="subtitle"><?=Loc::getMessage('SHAREPRESET_IMPORT_FILE')?></div>
															</div>
															<div class="value_wrapper">
																<?=\CFileInput::Show('sharepreset_file_'.$optionsSiteID, false,
																	array(),
																	array(
																		'upload' => true,
																		'medialib' => false,
																		'file_dialog' => false,
																		'cloud' => false,
																		'del' => false,
																		'description' => false,
																	)
																);?>
															</div>
															<a href="" class="adm-btn" data-action="importFromFile"><?=Loc::getMessage('SHAREPRESET_IMPORT_APPLY_BUTTON')?></a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<?$i = 0;?>
									<?foreach(Solution::$arParametrsList as $blockCode => $arBlock){?>
										<?if(isset($arBlock["CODE"]) && !$blockCode)
											$blockCode = "REQUIRED";?>

										<?if(in_array($blockCode, $arHideProps)) continue;?>

										<?$bTabActive = !$_COOKIE['activeTabShare_site_'.$optionsSiteID] && ((!$_COOKIE['activeTab_site_'.$optionsSiteID] && !$i) || $_COOKIE['activeTab_site_'.$optionsSiteID] == $i);?>
										<div class="tab <?=($bTabActive ? "active" : "")?>" data-prop_code="<?=$blockCode?>">
											<div class="title bg"><?=$arBlock["TITLE"]?></div>
											<?foreach($arBlock["OPTIONS"] as $optionCode => $arOption){?>
												<?if($arOption['TYPE'] === 'backButton') continue;?>

												<?if(isset($arOption["ITEMS"])):?>
													<?$style = '';
													if(isset($arOption["PARENT"]))
													{
														if(\Bitrix\Main\Config\Option::get($moduleID, $arOption["PARENT"], "N", $optionsSiteID) == "N")
															$style = "style='display:none;'";
													}?>
													<div class="groups_block block <?=$optionCode?> <?=(isset($arOption["PARENT"]) ? "depend-block" : "");?>" <?=(isset($arOption["PARENT"]) ? "data-parent='".$arOption["PARENT"]."_".$optionsSiteID."'" : "");?> <?=$style;?>>
														<?if($arOption["TITLE"]):?>
															<div class="title"><?=$arOption["TITLE"];?></div>
														<?endif;?>

														<?if(isset($arOption["ONE_BLOCK"]) && $arOption["ONE_BLOCK"] == "Y"):?>
															<div class="block_wrapper">
														<?endif;?>

														<?foreach($arOption["ITEMS"] as $optionCode2 => $arOption2):?>
															<?=Solution::showAllAdminRows($optionCode2, $arTab, $arOption2, $module_id, $arPersonTypes, $optionsSiteID, $arDeliveryServices, $arPaySystems, $arCurrency, $arOrderPropertiesByPerson, $bSearchMode);?>
														<?endforeach;?>

														<?if(isset($arOption["ONE_BLOCK"]) && $arOption["ONE_BLOCK"] == "Y"):?>
															</div>
														<?endif;?>
													</div>
												<?else:?>
													<div class="block">
														<?=Solution::showAllAdminRows($optionCode, $arTab, $arOption, $module_id, $arPersonTypes, $optionsSiteID, $arDeliveryServices, $arPaySystems, $arCurrency, $arOrderPropertiesByPerson, $bSearchMode);?>
													</div>
												<?endif;?>
											<?}?>
										</div>

										<?$i++;?>
									<?}?>
								</div>
							</div>

							<?if($bSearchMode):?>
								<?die();?>
							<?endif;?>

						</div>
					</td>
				</tr>
			<?}
		}?>
		<?
		if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && check_bitrix_sessid())
		{
			if(strlen($Update) && strlen($_REQUEST["back_url_settings"]))
				LocalRedirect($_REQUEST["back_url_settings"]);
			else
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		}?>
			<?$tabControl->Buttons();?>
			<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="Apply" class="submit-btn" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
			<?if(strlen($_REQUEST["back_url_settings"]) > 0): ?>
				<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
				<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
			<?endif;?>
			<?if(Solution::IsCompositeEnabled()):?>
				<div class="adm-info-message"><?=GetMessage("WILL_CLEAR_HTML_CACHE_NOTE")?></div><div style="clear:both;"></div>
			<?endif;?>
			<script type="text/javascript">
				var arOrderPropertiesByPerson = <?=CUtil::PhpToJSObject($arOrderPropertiesByPerson, false)?>;

				// onAdminFixerUnfix
				BX.addCustomEvent(window, "onFixedNodeChangeState", function(){
					/*if($('#tabControl_tabs').hasClass('bx-fixed-top'))
					{
						$('.tabs-wrapper')
					}*/
				});

				$(window).scroll(function(){
					var scroll = BX.GetWindowScrollPos();
					// console.log($('#tabControl_tabs').attr('class'));
					// BX.adminPanel.isFixed()
				})

				$(document).ready(function() {
					BX.message({
						SHAREPRESET_EXPORT_BLOCKS_TOGGLE: '<?=Loc::getMessage('SHAREPRESET_EXPORT_BLOCKS_TOGGLE')?>',
					});

					$(document).on('click', '.sharepreset-blocks-toggle', function(e){
						e.preventDefault();

						var $td = $(this).closest('.tabs-wrapper').closest('td');
						var $form = $td.find('.tab-sharepreset .form');
						
						$(this).toggleClass('sharepreset-blocks-toggle--open');
						$form.find('.sharepreset-blocks').slideToggle();
					});
					
					$(document).on('click', '.sharepreset-blocks__action--select-all', function(e){
						e.preventDefault();

						var $td = $(this).closest('.tabs-wrapper').closest('td');
						var $form = $td.find('.tab-sharepreset .form');

						$form.find('.sharepreset-blocks input[type=checkbox]').prop('checked', true);
					
						var codes = [];
						$form.find('.sharepreset-blocks input[type=checkbox]:checked').map(function(i, e){codes.push($(e).val())});
						$form.find('input[name^=sharepreset_blocks]').val(codes.join(','));

						$form.find('.sharepreset-blocks-toggle').text(
							BX.message('SHAREPRESET_EXPORT_BLOCKS_TOGGLE').replace('#COUNT#', codes.length).replace('#ALLCOUNT#', $form.find('.sharepreset-blocks input[type=checkbox]').length)
						);
					});

					$(document).on('click', '.sharepreset-blocks__action-reset-all', function(e){
						e.preventDefault();

						var $td = $(this).closest('.tabs-wrapper').closest('td');
						var $form = $td.find('.tab-sharepreset .form');

						$form.find('.sharepreset-blocks input[type=checkbox]').prop('checked', false);

						$form.find('.sharepreset-blocks-toggle').text(
							BX.message('SHAREPRESET_EXPORT_BLOCKS_TOGGLE').replace('#COUNT#', 0).replace('#ALLCOUNT#', $form.find('.sharepreset-blocks input[type=checkbox]').length)
						);

						$form.find('input[name^=sharepreset_blocks]').val('');						
					});

					$(document).on('change', '.sharepreset-blocks .options input[type=checkbox]', function(e){
						e.preventDefault();

						var $td = $(this).closest('.tabs-wrapper').closest('td');
						var $form = $td.find('.tab-sharepreset .form');
												
						var codes = [];
						$form.find('.sharepreset-blocks input[type=checkbox]:checked').map(function(i, e){codes.push($(e).val())});
						$form.find('input[name^=sharepreset_blocks]').val(codes.join(','));

						$form.find('.sharepreset-blocks-toggle').text(
							BX.message('SHAREPRESET_EXPORT_BLOCKS_TOGGLE').replace('#COUNT#', codes.length).replace('#ALLCOUNT#', $form.find('.sharepreset-blocks input[type=checkbox]').length)
						);
					});

					$('.adm-btn[data-action=exportToFile],.adm-btn[data-action=importFromLink],.adm-btn[data-action=importFromFile]').click(function(e){
						e.preventDefault();

						if($(this).hasClass('adm-btn-disabled')){
							return;
						}

						var $button = $(this);
						var $td = $button.closest('.tabs-wrapper').closest('td');
						var $form = $td.find('.tab-sharepreset .form');
						if($form.hasClass('sending')){
							return;
						}

						$button.addClass('adm-btn-load');
						$form.addClass('sending');
						$form.find('.sharepreset-error').hide();

						var action = $button.data('action');
						
						var siteId = $td.data('siteid');
						var siteDir = $td.data('sitedir');
						var moduleId = '<?=$moduleID?>';
						var charset = '<?=SITE_CHARSET?>';

						var fd = new FormData();
						fd.append('siteId', siteId);
						fd.append('siteDir', siteDir);
						fd.append('moduleId', moduleId);
						fd.append('charset', charset);
						fd.append('front', 0);
						fd.append('sessid', BX.message('bitrix_sessid'));

						if(action === 'importFromLink'){
							fd.append('link', $form.find('input[name=sharepreset_link_' + siteId + ']').val());
						}
						else if(action === 'importFromFile'){
							var fileInput = $form.find('.adm-input-file-new .adm-input-file input[name=sharepreset_file_' + siteId + ']')[0];
							if(fileInput){
								var files = fileInput.files || [fileInput.value];
								fd.append('file', files[0]);
							}
						}
						else if(action === 'exportToFile'){
							fd.append('blocks', $form.find('input[name=sharepreset_blocks_' + siteId + ']').val());
						}

						var moduleName = 'aspro:sharepreset';
						var componentName = '<?=Solution::partnerName?>:theme.<?=Solution::solutionName?>';

						var componentAction = action;
						var promise = BX.ajax.runComponentAction(
							componentName,
							componentAction,
							{
								mode: 'ajax',
								data: fd
							}
						);

						promise.then(
							function(response){
								if(action === 'exportToFile'){
									$form.removeClass('sending');
									$button.removeClass('adm-btn-load');

									if(response.data.code){
										location.href = '/bitrix/services/main/ajax.php?mode=ajax&c=' + encodeURIComponent(componentName) +'&action=downloadFile&sessid=' + BX.message('bitrix_sessid') + '&siteId=' + siteId + '&siteDir=' + siteDir + '&charset=' + charset + '&code=' + response.data.code;
									}
								}
								else if(action === 'importFromLink'){
									if(response.data.preset){
										fd.append('preset', JSON.stringify(response.data.preset));

										var promise = BX.ajax.runComponentAction(
											componentName,
											'importFromPreset',
											{
												mode: 'ajax',
												data: fd
											}
										).then(
											function(response){
												location.href = location.href;
											},
											function(response){
												console.error(response);
												var error = response.errors[0];

												$form.removeClass('sending');
												$button.removeClass('adm-btn-load');
												$form.find('.sharepreset-error').show().find('.adm-info-message-title').text(error.message);
											},
										);
									}
								}
								else if(action === 'importFromFile'){
									location.href = location.href;
								}
							},
							function(response){
								console.error(response);
								var error = response.errors[0];

								$form.removeClass('sending');
								$button.removeClass('adm-btn-load');
								$form.find('.sharepreset-error').show().find('.adm-info-message-title').text(error.message);
							}
						);
					});
					
					$('input[name^="THEME_SWITCHER"]').change(function(){
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined'){
							if(!confirm("<?=GetMessage("NO_COMPOSITE_NOTE")?>")){
								$(this).removeAttr('checked');
							}
						}
					});
					
					$(".tabs-wrapper .search_wrapper .delete").click(function(){
						//$(".tabs-wrapper .search_wrapper input").val('');
						$(this).parents('.search_wrapper').find('input').val('');
						searchParams(this);
						$(this).parent().find('div').hide();
					});

					$(".tabs-wrapper .search_wrapper .search").click(function(){
						searchParams(this);
					});
					
					//$(".tabs-wrapper .search_wrapper input").on('textchange',delayTextChange(searchParams, 1000));
					var timerAjaxOptions;
					$(".tabs-wrapper .search_wrapper input").on('textchange', function (e) {
						clearTimeout(timerAjaxOptions);
						timerAjaxOptions = setTimeout(function () {
							searchParams(e.target);
						}, 1000);
					});

					/*set active tab*/
					$('.tabs-wrapper .tabs-heading .head').click(function(){
						var _this = $(this);
						_this.closest('.tabs-heading-wrapper').find('.tabs-heading .head').removeClass('active');
						_this.addClass('active');
						_this.closest('.tabs-wrapper').find('.tabs-content .tab').removeClass('active');
						_this.closest('.tabs-wrapper').find('.tabs-content .tab[data-prop_code='+_this.data('code')+']').addClass('active');

						if(!!document.cookie){
							document.cookie = 'activeTabShare_'+_this.closest('td').attr('class')+'='+(_this.data('code') === 'SHARE' ? 1 : 0)+';path=<?=$GLOBALS['APPLICATION']->GetCurPage(true)?>';
							
							if(_this.data('code') !== 'SHARE'){
								document.cookie = 'activeTab_'+_this.closest('td').attr('class')+'='+_this.index()+';path=<?=$GLOBALS['APPLICATION']->GetCurPage(true)?>';
							}
						}
					})

					/*set active color*/
					$('.bases_block .base_color').click(function(){
						var _this = $(this);
						_this.siblings().removeClass('current');
						_this.addClass('current');
						_this.closest('.bases_block').find('input[type="hidden"]').val(_this.data('value'));

						_this.closest('.groups_block').find('.base_color_custom').removeClass('current');
						_this.closest('.groups_block').find('.base_color_custom .click_block').removeAttr('style');
						_this.closest('.groups_block').find('.base_color_custom .click_block .vals').text('#');
						_this.closest('.groups_block').find('.base_color_custom input[type="hidden"]').val('');
					});

					/*spectrum*/
					if($('.base_color_custom input[type=hidden]').length)
					{
						$('.base_color_custom input[type=hidden]').each(function(){
							var _this = $(this),
								parent = $(this).closest('.base_color_custom');
							_this.spectrum({
								preferredFormat: 'hex',
								showButtons: true,
								showInput: true,
								showPalette: false,
								appendTo: parent,
								chooseText: '<?=GetMessage('CUSTOM_COLOR_CHOOSE');?>',
								cancelText: '<?=GetMessage('CUSTOM_COLOR_CANCEL');?>',
								containerClassName: 'custom_picker_container',
								replacerClassName: 'custom_picker_replacer',
								clickoutFiresChange: false,
								move: function(color) {
									var colorCode = color.toHexString();
									parent.find('span span.bg').attr('style', 'background:' + colorCode);
								},
								hide: function(color) {
									var colorCode = color.toHexString();
									parent.find('span span.bg').attr('style', 'background:' + colorCode);
								},
								change: function(color) {
									var colorCode = color.toHexString();
									parent.addClass('current').siblings().removeClass('current');

									parent.find('span span.vals').text(colorCode);
									parent.find('span.animation-all').attr('style', 'border-color:' + colorCode);

									$('input[name=' + parent.find('.click_block').data('option-id') + ']').val(parent.find('.click_block').data('option-value'));
									$('input[name=' + parent.find('.click_block').data('option-id') + ']').siblings().removeClass('current');
								}
							});
						})
					}

					$('.base_color_custom').click(function(e) {
						e.preventDefault();
						$('input[name='+$(this).data('name')+']').spectrum('toggle');
						return false;
					});
					/**/

					/* sort order for phones */
					$('.adm-detail-content .item .aspro-admin-item').each(function(){
						(function(sort_block){
							Sortable.create(sort_block,{
								handle: '.drag',
								animation: 150,
								forceFallback: true,
								filter: '.no_drag',
								onChoose: function (evt) {
									$(sort_block).addClass('sortable-started');
								},
								onUnchoose: function(evt) {
									$(sort_block).removeClass('sortable-started');
								},
								onStart: function(evt){
									$(sort_block).addClass('sortable-started');
									window.getSelection().removeAllRanges();
								},
								onEnd: function(evt){
									$(sort_block).removeClass('sortable-started');
								},
								onMove: function(evt){
									return evt.related.className.indexOf('no_drag') === -1;
								},
								onUpdate: function (evt) {
									try{
										var current_type = $(sort_block).data('key');
										if(!current_type){
											var keys = [];
											var inputsNames = [];
											var rows = Array.prototype.slice.call(sort_block.querySelectorAll('.wrapper'));
											for(var j in rows){
												keys.push(j * 1);

												var names = [];
												var inputs = Array.prototype.slice.call(rows[j].querySelectorAll('input'));
												for(var k in inputs){
													names.push(inputs[k].getAttribute('name'));
												}
												inputsNames.push(names);
											}

											var k = evt.oldIndex;
											do{
												keys[k] = (k == evt.oldIndex ? evt.newIndex : (evt.newIndex > evt.oldIndex ? k - 1 : k + 1)) ;
												evt.newIndex > evt.oldIndex ? ++k : --k;
											}
											while(evt.newIndex > evt.oldIndex ? k <= evt.newIndex : k >= evt.newIndex);

											for(var j in rows){
												if(keys[j] != j){
													var inputs = Array.prototype.slice.call(rows[j].querySelectorAll('input'));
													for(var k in inputs){
														inputs[k].setAttribute('name', inputsNames[keys[j]][k]);
													}
												}
											}
										}
										else{
											var order = [];
											var itemEl = evt.item;
											var current_site = $(sort_block).data('site');
											$(sort_block).find('.block').each(function(){
												order.push($(this).find('.value_wrapper input[type="checkbox"]').attr('name').replace(current_type+'_', '').replace('_'+current_site, ''))
											})
											$(sort_block).closest('.parent-wrapper').find('input[name^=SORT_ORDER_INDEX_TYPE_'+current_type+']').val(order.join(','));
										}
									}
									catch(e){
										console.error(e);
									}
								},
							});
						})(this);
					})

					$(document).on('click', '.item.array .aspro-admin-item .remove', function(){
						var $array = $(this).closest('.item.array');
						$(this).closest('.wrapper').remove();
						if(!$array.find('.aspro-admin-item .wrapper:not(.has_title)').length){
							$array.addClass('empty_block');
						}
					})

					$('.item.array .adm-btn-save.adm-btn-add').click(function(){
						var _this = $(this);
						var newItemHtml = _this.closest('.item.array').find('.new-item-html').html();
						var $array = _this.closest('.item.array');
						newItemHtml = newItemHtml.replace(/#INDEX#/g, $array.find('.aspro-admin-item .wrapper').length);
						$(newItemHtml).appendTo($array.find('.aspro-admin-item'));
						$array.removeClass('empty_block');
					})
					/**/

					/*set active page*/
					$('.block_with_img .link-item').on('click', function(){
						var _this = $(this);
						_this.closest('.rows').find('.link-item').removeClass('current');
						_this.addClass('current');
						_this.closest('.block_with_img').find('input[type="hidden"]').val(_this.data('value')).change();

						if (_this.closest('[data-class="FILTER_VIEW"]').length) {
							_this.closest('.item').find('.depend-block').hide();
							_this.closest('.item').find('.depend-block[data-show="'+_this.data('value')+'"]').show();
						}

						/*index page*/
						if(_this.closest('div[data-optioncode="INDEX_TYPE"]').length)
						{
							_this.closest('.item').find('.js-sub').fadeOut();
							_this.closest('.item').find('.block_'+_this.data('value')+'_'+_this.data('site')+' div.block').show();
							_this.closest('.item').find('.block_'+_this.data('value')+'_'+_this.data('site')).fadeIn();
						}
					});

					/*scroll btn action*/
					$('select[name^="SCROLLTOTOP_TYPE"]').change(function() {
						var isNone = $(this).val().indexOf('NONE') != -1;
						var posSelect = $(this).closest('.tab').find('select[name^="SCROLLTOTOP_POSITION"]');
						var posSelectTr = posSelect.closest('.item');
						var posRightTr = $(this).closest('.tab').find('input[name^="SCROLLTOTOP_POSITION_RIGHT"]').closest('.item');
						var posBottomTr = $(this).closest('.tab').find('input[name^="SCROLLTOTOP_POSITION_BOTTOM"]').closest('.item');
						if (isNone) {
							if(posRightTr.is(':visible')) {
								posRightTr.fadeOut();
							}

							if(posBottomTr.is(':visible')) {
								posBottomTr.fadeOut();
							}

							if(posSelectTr.is(':visible')) {
								posSelectTr.fadeOut();
							}
						} else {
							if(!posBottomTr.is(':visible')) {
								posBottomTr.fadeIn();
							}

							if(!posSelectTr.is(':visible')){
								posSelectTr.fadeIn();
							}

							if(posSelect.length){
								var isRound = $(this).val().indexOf('ROUND') != -1;
								var isTouch = posSelect.val().indexOf('TOUCH') != -1;
								if(isRound && !!posSelect) {
									posSelect.find('option[value^="TOUCH"]').attr('disabled', 'disabled');

									if(isTouch){
										posSelect.val(posSelect.find('option[value^="PADDING"]').first().attr('value'));
									}

									if(!posRightTr.is(':visible')) {
										posRightTr.fadeIn();
									}
								}
								else {
									posSelect.find('option[value^="TOUCH"]').removeAttr('disabled');
								}
							} else {
								if(!posRightTr.is(':visible')) {
									posRightTr.fadeIn();
								}
							}
						}
					});

					$('select[name^="SCROLLTOTOP_POSITION"]').change(function() {
						var isTouch = $(this).val().indexOf('TOUCH') != -1;
						var posRightTr = $(this).closest('.tab').find('input[name^="SCROLLTOTOP_POSITION_RIGHT"]').closest('.item');
						if (isTouch) {
							if(posRightTr.is(':visible')) {
								posRightTr.fadeOut();
							}
						} else {
							if(!posRightTr.is(':visible')) {
								posRightTr.fadeIn();
							}
						}
					});

					$('select[name^="SCROLLTOTOP_TYPE"]').change();
					$('select[name^="SCROLLTOTOP_POSITION"]').change();
					$('.block_with_img .link-item.current').trigger('click');
				});


				// function delayTextChange(callback, ms, input) {
				//   var timer = 0;		  
				  
				// 	return function() {//console.log(arguments);
				// 		var context = this, args = arguments; 
				// 		clearTimeout(timer);
				// 		timer = setTimeout(function () {
				// 			callback.apply(context, args);
				// 		}, ms || 0);
				// 	};
				// }

				function searchParams(curInput){
					var _this;
					if($(curInput).length) {
						_this = $(curInput).parents('.search_wrapper').find('input');
					} else {
						_this = $(".tabs-wrapper .search_wrapper input")
					}
					var val = _this.val(),
						site = _this.data('site'),
						wrapper = _this.closest('.tabs-wrapper');

					if(!val || val.length > 2)
						wrapper.addClass('loading');

					wrapper.find('.main-grid-empty-inner').hide();
					wrapper.find('.search_wrapper .delete').show();


					$.ajax({
						type: 'POST',
						dataType: 'html',
						data: {q: val, site: site},
						success: function(html){
							val = val.replace(/^\s+/g, '');
							wrapper.removeClass('loading');
							if(val)
							{
								if(val.length > 2)
								{
									wrapper.addClass('searched');
									wrapper.find('.search_wrapper .search').show();

									$(html).find('.tabs-content .js_block').each(function(){
										var _this2 = $(this),
											item = wrapper.find('.tabs .tabs-content .js_block[data-class="'+_this2.data('class')+'"]');

										if(_this2.data('search'))
										{
											if(item.find(' > div').attr('style') == undefined)
											{
												/*console.log(item);
												console.log(item.attr('style'));*/
												if(item.attr('style') == undefined || (item.attr('style') && item.attr('style').indexOf('block') !== -1)) //fix fo hidden elements
												{
													item.addClass(_this2.data('search'));
													if(item.data('class').indexOf('GOOGLE') !== -1)
														wrapper.find('.tabs-content .js_block[data-class="USE_GOOGLE_RECAPTCHA"]').addClass(_this2.data('search'));
												}

											}
											else
												item.removeClass('visible_block');
										}
										else
										{
											item.removeClass('visible_block');
										}
									})

									wrapper.find('.tabs-content .js_block').each(function(){
										var _this2 = $(this);

										if(_this2.hasClass('includefile') && _this2.data('class') != 'ALL_COUNTERS' && _this2.data('class') != 'LICENCE_TEXT' && _this2.data('class') != 'LOGO_IMAGE_SVG')
										{
											if(!_this2.is(':visible'))
												_this2.removeClass('visible_block');
										}

										if(_this2.closest('.block').find('.js_block.visible_block').length)
											_this2.closest('.block').addClass('visible_block');
										else
											_this2.closest('.block').removeClass('visible_block');
									})

									wrapper.find('.tabs-content > .tab').each(function(){
										var _this2 = $(this);

										if(_this2.find(' > .block.visible_block').length)
										{
											_this2.addClass('visible_block');
											wrapper.find('.tabs-heading .head:eq('+_this2.index()+')').addClass('visible_block');
										}
										else
										{
											_this2.removeClass('visible_block');
											wrapper.find('.tabs-heading .head:eq('+_this2.index()+')').removeClass('visible_block');
										}
									})

									wrapper.find('.tabs-heading').each(function(){
										var _this2 = $(this);
										if(_this2.find(' > .head.visible_block').length)
											_this2.addClass('visible_block');
										else
											_this2.removeClass('visible_block');
									})

									if(wrapper.find('.tabs-heading').hasClass('visible_block'))
									{
										if(!wrapper.find('.tabs-heading .head.active').hasClass('visible_block'))
										{
											wrapper.find('.tabs-heading .head').removeClass('active');
											wrapper.find('.tabs-heading .head.visible_block:first').addClass('active');

											wrapper.find('.tabs-content .tab').removeClass('active');
											wrapper.find('.tabs-content .tab.visible_block:first').addClass('active');
										}
										wrapper.find('.main-grid-empty-inner').hide();
									}
									else
									{
										wrapper.find('.main-grid-empty-inner').fadeIn();
									}
								}
								else
								{
									if(!wrapper.find('.tabs-heading').hasClass('visible_block') && wrapper.hasClass('searched'))
										wrapper.find('.main-grid-empty-inner').fadeIn();
								}
							}
							else
							{
								wrapper.removeClass('searched');
								// wrapper.find('.tabs-content .block').removeAttr('style');
								wrapper.find('.tabs-content .item').removeClass('visible_block');
							}
							var btnWrap = $('.max_options.views .adm-detail-content-btns-wrap');
							if(btnWrap.length) {
								btnWrap.each(function(i, el) {
									if( el.BXFIXER !== undefined ) {
										el.BXFIXER._recalc_pos();
									}
								})
							}
						},
						error: function(jqXHR){
							console.log(jqXHR);
						}
					})
				}


				function CheckActive(){
					$('select[name^="BUYMISSINGGOODS"]').each(function() {
						var select = this;
						var BuyMissingGoodsVal = $(select).val();
						var tab = $(select).parents('.adm-detail-content-item-block');
						tab.find('input[name^="EXPRESSION_SUBSCRIBE_BUTTON"]').attr('disabled', 'disabled');
						tab.find('input[name^="EXPRESSION_SUBSCRIBED_BUTTON"]').attr('disabled', 'disabled');
						tab.find('input[name^="EXPRESSION_ORDER_BUTTON"]').attr('disabled', 'disabled');
						if(BuyMissingGoodsVal == 'SUBSCRIBE')
						{
							tab.find('input[name^="EXPRESSION_SUBSCRIBE_BUTTON"]').removeAttr('disabled');
							tab.find('input[name^="EXPRESSION_SUBSCRIBED_BUTTON"]').removeAttr('disabled');
						}
						else if(BuyMissingGoodsVal == 'ORDER')
						{
							tab.find('input[name^="EXPRESSION_ORDER_BUTTON"]').removeAttr('disabled');
						}
					});
				}

				function checkGoalsNote(){
					var inUAC = $('.adm-detail-content-table:visible').first().find('.item input[id^=YA_GOALS]');
					var itrYACID = $('.adm-detail-content-table:visible').first().find('div.YA_COUNTER_ID');
					var itrGNote = $('.adm-detail-content-table:visible').first().find('div.GOALS_NOTE');
					var itrUFG = $('.adm-detail-content-table:visible').first().find('div.USE_FORMS_GOALS');
					var itrUBG = $('.adm-detail-content-table:visible').first().find('div.USE_BASKET_GOALS');
					var itrU1CG = $('.adm-detail-content-table:visible').first().find('div.USE_1CLICK_GOALS');
					var itrUQOG = $('.adm-detail-content-table:visible').first().find('div.USE_FASTORDER_GOALS');
					var itrUFOG = $('.adm-detail-content-table:visible').first().find('div.USE_FULLORDER_GOALS');
					var itrUFVOG = $('.adm-detail-content-table:visible').first().find('div.USE_FAVORITE_GOALS');
					var itrUCOG = $('.adm-detail-content-table:visible').first().find('div.USE_COMPARE_GOALS');
					var itrUDG = $('.adm-detail-content-table:visible').first().find('div.USE_DEBUG_GOALS');

					if(inUAC.length && inUAC.attr('checked'))
					{
						var bShowNote = 6;

						if(itrUFG.find('select').val().indexOf('NONE') == -1)
						{
							itrGNote.find('[data-goal=form]').show();
						}
						else
						{
							itrGNote.find('[data-goal=form]').hide();
							--bShowNote;
						}

						if(itrUBG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=basket]').show();
						}
						else
						{
							itrGNote.find('[data-goal=basket]').hide();
							--bShowNote;
						}

						if(itrU1CG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=1click]').show();
						}
						else
						{
							itrGNote.find('[data-goal=1click]').hide();
							--bShowNote;
						}

						if(itrUQOG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=fastorder]').show();
						}
						else
						{
							itrGNote.find('[data-goal=fastorder]').hide();
							--bShowNote;
						}

						if(itrUFOG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=fullorder]').show();
						}
						else
						{
							itrGNote.find('[data-goal=fullorder]').hide();
							--bShowNote;
						}

						if(itrUFVOG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=favorite]').show();
						}
						else
						{
							itrGNote.find('[data-goal=favorite]').hide();
							--bShowNote;
						}

						if(itrUCOG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=compare]').show();
						}
						else
						{
							itrGNote.find('[data-goal=compare]').hide();
							--bShowNote;
						}

						if(itrUDG.find('input').attr('checked'))
						{
							itrGNote.find('[data-goal=debug]').show();
						}
						else
						{
							itrGNote.find('[data-goal=debug]').hide();
							--bShowNote;
						}

						if(bShowNote)
						{
							itrGNote.find('.inner_wrapper').show();
						}
						else
						{
							itrGNote.find('.inner_wrapper').hide();
						}
					}
					else
					{
						itrGNote.find('.inner_wrapper').hide();
					}
				}
			</script>
			<script type="text/javascript">
				$(document).ready(function(){
					CheckActive();

					$('form.max_options').submit(function(e) {
						$(this).attr('id', 'max_options');
						$(this).find('input').removeAttr('disabled');
					});

					$('input.depend-check').change(function() {
						var ischecked = $(this).prop('checked'),
							depend_block = $('.depend-block[data-parent='+$(this).attr('id')+']');
						if(depend_block.length && $(this).attr('id').indexOf('YA_GOALS') < 0)
						{
							if(typeof(depend_block.data('show')) != 'undefined')
							{
								if(depend_block.data('show') == 'Y')
								{
									if(ischecked)
										depend_block.fadeIn();
									else
										depend_block.fadeOut();
								}
								else
								{
									if(ischecked)
										depend_block.fadeOut();
									else
										depend_block.fadeIn();
								}
							}
						}
					});

					$('select.depend-check').change(function() {

						var value = $(this).prop('value'),
							arBlocks = ''

						if ($(this).attr('name').indexOf('YA_GOALS') < 0) {
							if (arBlocks = $(this).closest('.item').find('.depend-block[data-parent='+$(this).attr('name')+']')) {
								var timer;
								arBlocks.each(function(){
									var depend_block = $(this);
									if (typeof(depend_block.data('show')) != 'undefined') {
										if (depend_block.data('show') == value) {
											depend_block.fadeIn(300);
										} else {
											depend_block.fadeOut(100);
										}
									}
								})
							}
						}
					});
				})

				$('input[name^="SHOW_BG_BLOCK"]').change(function(){
					if($(this).attr('checked') != 'checked')
					{
						$(this).closest('.groups_block').find('div[data-class="BGCOLOR_THEME"]').fadeOut();
						$(this).closest('.groups_block').find('div[data-class="CUSTOM_BGCOLOR_THEME"]').fadeOut();
					}
					else
					{
						$(this).closest('.groups_block').find('div[data-class="BGCOLOR_THEME"]').fadeIn();
						$(this).closest('.groups_block').find('div[data-class="CUSTOM_BGCOLOR_THEME"]').fadeIn();
					}
				});

				$('select[name^="USE_FORMS_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked')){
						var isNone = $(this).val().indexOf('NONE') != -1;
						var isCommon = $(this).val().indexOf('COMMON') != -1;
						var itrGNote = $(this).closest('.tab').find('div.GOALS_NOTE');
						if(!isNone)
						{
							if(isCommon)
							{
								itrGNote.find('[data-value=common]').show();
								itrGNote.find('[data-value=single]').hide();
							}
							else
							{
								itrGNote.find('[data-value=common]').hide();
								itrGNote.find('[data-value=single]').show();
							}
							itrGNote.find('[data-goal=form]').show();
						}
						else
						{
							itrGNote.find('[data-goal=form]').hide();
						}
					}

					checkGoalsNote();
				});

				$('input[name^="USE_BASKET_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked'))
					{
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=basket]').show();
						else
							itrGNote.find('[data-goal=basket]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="USE_1CLICK_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked')){
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=1click]').show();
						else
							itrGNote.find('[data-goal=1click]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="USE_FASTORDER_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked'))
					{
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=fastorder]').show();
						else
							itrGNote.find('[data-goal=fastorder]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="USE_FULLORDER_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked'))
					{
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=fullorder]').show();
						else
							itrGNote.find('[data-goal=fullorder]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="USE_FAVORITE_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked'))
					{
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=favorite]').show();
						else
							itrGNote.find('[data-goal=favorite]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="USE_COMPARE_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked'))
					{
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=compare]').show();
						else
							itrGNote.find('[data-goal=compare]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="USE_DEBUG_GOALS"]').change(function() {
					var parent = $(this).closest('.depend-block').data('parent');
					var inUAC = $(this).closest('.tab').find('input#'+parent);
					if(inUAC.length && inUAC.attr('checked'))
					{
						var itrGNote = $(this).closest('.tab').find('div[data-optioncode=GOALS_NOTE]');
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined')
							itrGNote.find('[data-goal=debug]').show();
						else
							itrGNote.find('[data-goal=debug]').hide();
					}

					checkGoalsNote();
				});

				$('input[name^="YA_GOALS"]').change(function(){
					var tab = $(this).closest('.tab');
					var itrYACID = tab.find('div.YA_COUNTER_ID');
					var itrUFG = tab.find('div.USE_FORMS_GOALS');
					var itrUBG = tab.find('div.USE_BASKET_GOALS');
					var itrU1CG = tab.find('div.USE_1CLICK_GOALS');
					var itrUQOG = tab.find('div.USE_FASTORDER_GOALS');
					var itrUFOG = tab.find('div.USE_FULLORDER_GOALS');
					var itrUFVG = tab.find('div.USE_FAVORITE_GOALS');
					var itrUCG = tab.find('div.USE_COMPARE_GOALS');
					var itrUDG = tab.find('div.USE_DEBUG_GOALS');
					var itrGNote = tab.find('div.GOALS_NOTE');
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined')
					{
						itrYACID.fadeIn();
						itrUFG.fadeIn();
						var valUFG = itrUFG.find('select').val();

						if(valUFG.indexOf('NONE') == -1)
						{
							var isCommon = valUFG.indexOf('COMMON') != -1;
							if(isCommon)
							{
								itrGNote.find('[data-value=common]').show();
								itrGNote.find('[data-value=single]').hide();
							}
							else
							{
								itrGNote.find('[data-value=common]').hide();
								itrGNote.find('[data-value=single]').show();
							}
							itrGNote.fadeIn();
						}
						itrUBG.fadeIn();
						itrU1CG.fadeIn();
						itrUQOG.fadeIn();
						itrUFOG.fadeIn();
						itrUFVG.fadeIn();
						itrUCG.fadeIn();
						itrUDG.fadeIn();
					}
					else
					{
						itrYACID.fadeOut();
						itrUFG.fadeOut();
						itrUBG.fadeOut();
						itrU1CG.fadeOut();
						itrUQOG.fadeOut();
						itrUFOG.fadeOut();
						itrUFVG.fadeOut();
						itrUCG.fadeOut();
						itrUDG.fadeOut();
						itrGNote.fadeOut();
					}
					checkGoalsNote();
				});

				$('select[name^="BUYMISSINGGOODS"]').change(function() {
					CheckActive();
				});

				/*$('select[name^="SHOW_SECTION_DESCRIPTION"]').change(function(){
					if($(this).val() != 'BOTH')
						$(this).closest('.block').find('select[name*="SECTION_DESCRIPTION_POSITION"]').closest('.item').fadeOut();
					else
						$(this).closest('.block').find('select[name*="SECTION_DESCRIPTION_POSITION"]').closest('.item').fadeIn();
				});*/

				$('input[name^="USE_PRIORITY_SECTION_DESCRIPTION_SOURCE"]').change(function(){
					if($(this).prop('checked'))
						$(this).closest('.block').find('input[name^="PRIORITY_SECTION_DESCRIPTION_SOURCE"]').closest('.item:not(.array)').fadeIn();
					else
						$(this).closest('.block').find('input[name^="PRIORITY_SECTION_DESCRIPTION_SOURCE"]').closest('.item:not(.array)').fadeOut();
				});

				$('select[name^="EXPRESS_BUTTON_ACTION"]').change(function(){
					var val = $(this).val();
					if(val === 'FORM'){
						$(this).closest('.adm-detail-content-table').find('div[data-class^="EXPRESS_BUTTON_LINK"]').hide();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="EXPRESS_BUTTON_FORM"]').fadeIn();
					}
					else{
						$(this).closest('.adm-detail-content-table').find('div[data-class^="EXPRESS_BUTTON_FORM"]').hide();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="EXPRESS_BUTTON_LINK"]').fadeIn();
					}
				});

				$('select[name^="EXPRESS_BUTTON_CLASS"]').change(function(){
					var val = $(this).val();
					if(val === 'custom'){
						$(this).closest('.adm-detail-content-table').find('div[data-class^="EXPRESS_BUTTON_CUSTOM_CLASS"]').fadeIn();
					}
					else{
						$(this).closest('.adm-detail-content-table').find('div[data-class^="EXPRESS_BUTTON_CUSTOM_CLASS"]').fadeOut();
					}
				});

				$('select[name^="SHOW_QUANTITY_FOR_GROUPS"]').change(function() {
					var val = $(this).val();
					var tab = $(this).parents('.adm-detail-content-item-block');
					var sqcg = tab.find('select[name^="SHOW_QUANTITY_COUNT_FOR_GROUPS"]');

					var isAll = false;
					if(val)
						isAll = val.indexOf('2') !== -1;

					if(!isAll)
					{
						$(this).find('option').each(function() {
							if($(this).attr('selected') != 'selected')
								sqcg.find('option[value="' + $(this).attr('value') + '"]').removeAttr('selected');
						});
					}
				});

				$('select[name^="SHOW_QUANTITY_COUNT_FOR_GROUPS"]').change(function(e) {
					e.stopPropagation();
					var val = $(this).val();
					var tab = $(this).parents('.adm-detail-content-item-block');
					var sqg_val = tab.find('select[name^="SHOW_QUANTITY_FOR_GROUPS"]').val();

					if(!sqg_val)
					{
						$(this).find('option').removeAttr('selected');
						return;
					}

					var isAll = false;
					if(sqg_val)
						isAll = sqg_val.indexOf('2') !== -1;

					if(!isAll && val)
					{
						for(i in val)
						{
							var g = val[i];
							if(sqg_val.indexOf(g) === -1)
								$(this).find('option[value="' + g + '"]').removeAttr('selected');
						}
					}
				});

				$('select[name^="ONECLICKBUY_PERSON_TYPE"]').change(function() {
					if(typeof arOrderPropertiesByPerson !== 'undefined'){
						var table = $(this).closest('.tab');
						var value = $(this).val();
						var site = $(this).data('site');
						if(typeof value !== 'undefined' && typeof arOrderPropertiesByPerson[value] !== 'undefined')
						{
							var arSelects = [table.find('div[data-optioncode="ONECLICKBUY_PROPERTIES"] .props'), table.find('div[data-optioncode="ONECLICKBUY_REQUIRED_PROPERTIES"] .props')];
							for(var i in arSelects)
							{
								var $fields = arSelects[i];
								var code = arSelects[i].closest('.item').find(' > div').data('optioncode');
								var $fields2 = $fields.next();
								if($fields.length && $fields2.length)
								{
									var fields = $fields2.val();
									$fields2.find('option').remove();

									if(fields)
									{
										if(fields.indexOf('FIO') !== -1 && fields.indexOf('CONTACT_PERSON') === -1)
											fields.push('CONTACT_PERSON');
										else if(fields.indexOf('FIO') === -1 && fields.indexOf('CONTACT_PERSON') !== -1)
											fields.push('FIO');
									}

									for(var j in arOrderPropertiesByPerson[value])
									{
										var selected = '';
										if(fields)
										{
											selected = (fields.indexOf(j) !== -1 ? ' selected="selected"' : '');
										}
										$fields2.append('<option value="' + j + '"' + selected + '>' + arOrderPropertiesByPerson[value][j] + '</option>');
									}
									/*$fields.find('option').eq(0).attr('selected', 'selected');
									$fields.find('option').eq(1).attr('selected', 'selected');*/

									$fields.html('');
									for(var j in arOrderPropertiesByPerson[value])
									{
										var selected = '';
										var input_id = code+'_'+site+'_'+j;
										if(fields)
											selected = (fields.indexOf(j) !== -1 ? ' checked' : '');
										$fields.append('<div class="outer_wrapper '+selected+'">'+
											'<div class="inner_wrapper checkbox">'+
												'<div class="title_wrapper">'+
													'<div class="subtitle"><label for="'+input_id+'">'+arOrderPropertiesByPerson[value][j]+'</label></div>'+
												'</div>'+
												'<div class="value_wrapper">'+
													'<input type="checkbox" class="adm-designed-checkbox" id="'+input_id+'" name="tmp_'+code+'_'+site+'[]" value="'+j+'" '+selected+'><label for="'+input_id+'" title="" class="adm-designed-checkbox-label"></label><label for="'+input_id+'"></label>'+
												'</div>'+
											'</div>'+
										'</div>');
									}
								}
							}
						}
					}
				});

				/*$('select[name^="ONECLICKBUY_PROPERTIES"]').change(function() {
					var table = $(this).parents('table').first();
					$(this).find('option').eq(0).attr('selected', 'selected');
					$(this).find('option').eq(1).attr('selected', 'selected');
					var fiedsValue = $(this).val();
					var $requiredFields = table.find('select[name^=ONECLICKBUY_REQUIRED_PROPERTIES]');
					var requiredFieldsValue = $requiredFields.val();
					for(var i in requiredFieldsValue)
					{
						if(fiedsValue === null || fiedsValue.indexOf(requiredFieldsValue[i]) === -1)
							$requiredFields.find('option[value=' + requiredFieldsValue[i] + ']').removeAttr('selected');
					}
				});

				$('select[name^="ONECLICKBUY_REQUIRED_PROPERTIES"]').change(function() {
					var table = $(this).parents('table').first();
					$(this).find('option').eq(0).attr('selected', 'selected');
					$(this).find('option').eq(1).attr('selected', 'selected');
					var requiredFieldsValue = $(this).val();
					var $fieds = table.find('select[name^=ONECLICKBUY_PROPERTIES]');
					var fiedsValue = $fieds.val();
					var $FIO = $(this).find('option[value^=FIO]');
					var $PHONE = $(this).find('option[value^=PHONE]');
					for(var i in requiredFieldsValue)
					{
						if(fiedsValue === null || fiedsValue.indexOf(requiredFieldsValue[i]) === -1)
							$(this).find('option[value=' + requiredFieldsValue[i] + ']').removeAttr('selected');
					}
				});*/

				$(document).on('change', 'input[name^="tmp_ONECLICKBUY"]', function(){
					var parent = $(this).closest('.outer_wrapper'),
						index = parent.index();

					if($(this).is(':checked'))
					{
						$(this).closest('.outer_wrapper').addClass('checked');
						parent.closest('.inner_wrapper').find('select option:eq('+index+')').attr('selected', 'selected');
					}
					else
					{
						$(this).closest('.outer_wrapper').removeClass('checked');
						parent.closest('.inner_wrapper').find('select option:eq('+index+')').removeAttr('selected');
					}

				});

				$(document).on('change', 'input[name^="PAGE_CONTACTS"]', function(){
					var value = $(this).val();
					if(value > 1){
						if($(this).closest('.adm-detail-content-table').find('input[name^="CONTACTS_USE_MAP"]').prop('checked')){
							$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_USE_TABS"]').fadeIn();
						}
					}
					else{
						$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_USE_TABS"]').fadeOut();
					}
				});

				$('input[name^="CONTACTS_USE_MAP"]').change(function(){
					if($(this).attr('checked') != 'checked'){
						$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_USE_TABS"]').fadeOut();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_TYPE_MAP"]').fadeOut(function(){
							$(this).closest('.adm-detail-content-table').find('.SECTION_CONTACTS_GROUP4').hide();
						});
						$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_MAP"]').fadeOut();
						$(this).closest('.adm-detail-content-table').find('div[data-option_code^="CONTACTS_MAP_NOTE"]').fadeOut();
					}
					else{
						if($(this).closest('.adm-detail-content-table').find('input[name^="PAGE_CONTACTS"]').val() > 1){
							$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_USE_TABS"]').fadeIn();
						}

						$(this).closest('.adm-detail-content-table').find('.SECTION_CONTACTS_GROUP4').show();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_TYPE_MAP"]').fadeIn();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="CONTACTS_MAP"]').fadeIn();
						$(this).closest('.adm-detail-content-table').find('div[data-option_code^="CONTACTS_MAP_NOTE"]').fadeIn();
					}
				});

				$('input[name^="USE_MORE_COLOR"]').change(function(){
					if($(this).prop('checked')){
						$(this).closest('.adm-detail-content-table').find('div[data-class^="MORE_COLOR"]').fadeIn();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="MORE_COLOR_CUSTOM"]').fadeIn();
					}
					else{
						$(this).closest('.adm-detail-content-table').find('div[data-class^="MORE_COLOR"]').fadeOut();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="MORE_COLOR_CUSTOM"]').fadeOut();
					}
				});

				$('input[name^="USE_GOOGLE_RECAPTCHA"]').change(function(){
					if($(this).prop('checked'))
					{
						$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_VERSION"]').fadeIn();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_PUBLIC_KEY"]').fadeIn();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_PRIVATE_KEY"]').fadeIn();
						$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_MASK_PAGE"]').fadeIn();

						var ver = $(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_VERSION"] select').val();
						if(ver == '3'){
							$(this).closest('.adm-detail-content-table').find('div[name^="GOOGLE_RECAPTCHA_NOTE"] div[data-version=3]').css('display','');
							$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_MIN_SCORE"]').fadeIn();
						}
						else{
							var size = $(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_SIZE"] select').val();
							$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_COLOR"]').fadeIn();
							$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_SIZE"]').fadeIn();
							if(size === 'INVISIBLE'){
								$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_SHOW_LOGO"]').fadeIn();
								$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA_BADGE"]').fadeIn();
							}
						}
					}
					else
					{
						$(this).closest('.adm-detail-content-table').find('div[name^="GOOGLE_RECAPTCHA_NOTE"] div[data-version=3]').css('display','none');
						$(this).closest('.adm-detail-content-table').find('div[data-class^="GOOGLE_RECAPTCHA"]').each(function(){
							$(this).fadeOut();
						});
					}

					$('select[name^="GOOGLE_RECAPTCHA_SIZE"]').change();
					$('select[name^="GOOGLE_RECAPTCHA_VERSION"]').change();
				});

				$('select[name^="GOOGLE_RECAPTCHA_SIZE"]').change(function() {
					var val = $(this).val();
					var tab = $(this).parents('.adm-detail-content-item-block');
					if(tab.find('input[name^="USE_GOOGLE_RECAPTCHA"]').prop('checked'))
					{
						if(val != 'INVISIBLE')
						{
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_SHOW_LOGO"]').fadeOut();
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_BADGE"]').fadeOut();
						}
						else
						{
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_SHOW_LOGO"]').fadeIn();
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_BADGE"]').fadeIn();
						}
					}
					else
					{
						tab.find('div[data-class^="GOOGLE_RECAPTCHA_SHOW_LOGO"]').fadeOut();
						tab.find('div[data-class^="GOOGLE_RECAPTCHA_BADGE"]').fadeOut();
					}
				});

				$('select[name^="GOOGLE_RECAPTCHA_VERSION"]').change(function() {
					var val = $(this).val();
					var tab = $(this).parents('.adm-detail-content-item-block');
					if(tab.find('input[name^="USE_GOOGLE_RECAPTCHA"]').prop('checked'))
					{
						if(val == '3')
						{
							tab.find('div[name^="GOOGLE_RECAPTCHA_NOTE"] div[data-version=3]').css('display','');
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_COLOR"]').fadeOut();
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_SIZE"]').fadeOut();
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_SHOW_LOGO"]').fadeOut();
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_BADGE"]').fadeOut();
							setTimeout(function(){
								tab.find('div[data-class^="GOOGLE_RECAPTCHA_MIN_SCORE"]').fadeIn();
							}, 400);
						}
						else
						{
							tab.find('div[name^="GOOGLE_RECAPTCHA_NOTE"] div[data-version=3]').css('display','none');
							tab.find('div[data-class^="GOOGLE_RECAPTCHA_MIN_SCORE"]').fadeOut();
							setTimeout(function(){
								tab.find('div[data-class^="GOOGLE_RECAPTCHA_COLOR"]').fadeIn();
								tab.find('div[data-class^="GOOGLE_RECAPTCHA_SIZE"]').fadeIn();
								tab.find('div[data-class^="GOOGLE_RECAPTCHA_SIZE"] select').trigger('change');
							}, 400);
						}
					}
				});

				$('.tabs-wrapper .tabs-content .subtitle.link').click(function() {
					var _this = $(this);
					var tab = _this.data('tab');
					if(tab) {
						var targetTab = $('.tabs-heading .head[data-code='+tab+']');
						if(targetTab.length) {
							targetTab.trigger('click');
						}
					}
				});

				$('select[name^="ONECLICKBUY_PERSON_TYPE"]').change();
				$('input[name^="YA_GOALS"]').change();
				$('select[name^="USE_FORMS_GOALS"]').change();
				$('input[name^="USE_BASKET_GOALS"]').change();
				$('input[name^="USE_1CLICK_GOALS"]').change();
				$('input[name^="USE_FASTORDER_GOALS"]').change();
				$('input[name^="USE_FULLORDER_GOALS"]').change();
				$('input[name^="USE_DEBUG_GOALS"]').change();

				$('input[name^="USE_MORE_COLOR"]').change();
				$('input[name^="CONTACTS_USE_MAP"]').change();
				$('input[name^="USE_GOOGLE_RECAPTCHA"]').change();
				$('select[name^="GOOGLE_RECAPTCHA_SIZE"]').change();
				$('select[name^="GOOGLE_RECAPTCHA_VERSION"]').change();
				$('select[name^="SHOW_SECTION_DESCRIPTION"]').change();
				$('input[name^="USE_PRIORITY_SECTION_DESCRIPTION_SOURCE"]').change();
				$('select[name^="EXPRESS_BUTTON_ACTION"]').change();
				$('select[name^="EXPRESS_BUTTON_CLASS"]').change();
			</script>
		<?$tabControl->End();?>
		</form>
	<?endif;?>
<?}
else{
	echo CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
}?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>