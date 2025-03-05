<?
use Bitrix\Main\Localization\Loc,
Bitrix\Main\Config\Option;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global  $APPLICATION;
IncludeModuleLangFile(__FILE__);

$moduleClass = "CLite";
$moduleID = "aspro.lite";
\Bitrix\Main\Loader::includeModule($moduleID);

$APPLICATION->SetTitle(Loc::getMessage('TABS_SETTINGS_TITLE'));

$RIGHT = $APPLICATION->GetGroupRight($moduleID);
if($RIGHT >= "R"){
	$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/style.css");

	$by = "id";
	$sort = "asc";

	$arSites = array();
	$db_res = CSite::GetList($by, $sort, array("ACTIVE" => "Y"));
	while($res = $db_res->Fetch()){
		$arSites[] = $res;
	}

	$arTabs = array(
		array(
			"DIV" => "edit1",
			"TAB" => Loc::getMessage("TABS_SETTINGS_TITLE"),
			"ICON" => "settings",
			"PAGE_TYPE" => "site_settings",
		)
	);

	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	if(
		$REQUEST_METHOD == "POST" &&
		strlen($Apply) && 
		$RIGHT >= "W" &&
		check_bitrix_sessid()
	){
		$arOption = array();
		foreach($_POST as $key => $value) {
			if(strpos($key, 'site_') !== false) {
				$arOption[] = str_replace('site_', '', $key);
			}
		}

		$optionTmp = implode(',', $arOption);
		Option::set($moduleID, 'TABS_FOR_VIEW_ASPRO_LITE', $optionTmp);

		if(strlen($_REQUEST["back_url_settings"])){
			LocalRedirect($_REQUEST["back_url_settings"]);
		}
		else{
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		}
	}

	$arTabsForView = explode(',', Option::get($moduleID, 'TABS_FOR_VIEW_ASPRO_LITE', ''));
	?>
	<style>
	
	</style>
	<form class="max_options views" method="post" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?=bitrix_sessid_post();?>
		<?$tabControl->Begin();?>

		<?foreach($arTabs as $key => $arTab):?>
			<?$tabControl->BeginNextTab();?>

			<tr>
				<td colspan="2">
					<div class="notes-block">
						<div align="center">
							<?=BeginNote('align="center"');?>
							<?=(Loc::getMessage('TABS_SETTINGS_PREVIEW'))?>
							<?=EndNote();?>
						</div>
					</div>
				</td>
			</tr>
			
			<?foreach($arSites as $arSite):?>
				<?
				if($arTabsForView) {
					$value = (in_array($arSite['ID'], $arTabsForView) ? 'checked' : '');
				}
				?>
				<tr>
					<td width="50%" class="adm-detail-content-cell-l"><label for="site_<?=$arSite['ID']?>"><?=$arSite['NAME'].' ('.$arSite['ID'].')'?></label></td>
					<td width="50%" class="adm-detail-content-cell-r"><input type="checkbox" id="site_<?=$arSite['ID']?>" name="site_<?=$arSite['ID']?>" <?=$value?> /></td>
				</tr>
			<?endforeach;?>
		<?endforeach;?>

		<?$tabControl->Buttons();?>
		<input type="submit" name="Apply" value="<?=Loc::getMessage('TABS_SETTINGS_SAVE')?>">
		<?$tabControl->End();?>
	</form>
	<?
}
else{
	echo CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
?>