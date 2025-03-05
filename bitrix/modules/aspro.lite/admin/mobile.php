<?
use Bitrix\Main\Web\Json,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Composite\Helper,
	Aspro\Lite\Mobile\Site as MobileSite;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$moduleClass = "CLite";
$moduleID = "aspro.lite";
Bitrix\Main\Loader::includeModule($moduleID);

$RIGHT = $APPLICATION->GetGroupRight($moduleID);
if ($RIGHT >= "R") {
	$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/style.css");
	$GLOBALS['APPLICATION']->SetTitle(GetMessage("LITE_MODULE_MOBILE_TITLE"));

	function SearchFilesInPublicRecursive($dir, $pattern, $flags = 0) {
		$arDirExclude = array('bitrix', 'upload');
		$pattern = str_replace('//', '/', str_replace('//', '/', $dir . '/') . $pattern);
		$files = glob($pattern, $flags);
		foreach (glob(dirname($pattern) . '/*') as $dir) {
			if (!in_array(basename($dir), $arDirExclude)) {
				$files = array_merge($files, SearchFilesInPublicRecursive($dir, basename($pattern), $flags));
			}
		}
		return $files;
	}

	function CreateBakFile($file, $label = '') {
		$file = trim($file);
		if (file_exists($file)) {
			$arPath = pathinfo($file);
			$backFile = $arPath['dirname'].'/_'.$arPath['basename'].$label.'.back';
			if (!file_exists($backFile)) {
				@copy($file, $backFile);
			}
		}
	}

	$by = "id";
	$sort = "asc";

	$arSites = array();
	$db_res = CSite::GetList($by, $sort, array("ACTIVE"=>"Y"));
	while($res = $db_res->Fetch()){
		$arSites[] = $res;
	}

	$arTabs = array();
	$arTabs[] = array(
		"DIV" => "edit",
		"TAB" => GetMessage("LITE_MODULE_MOBILE_TITLE"),
		"ICON" => "settings",
		"PAGE_TYPE" => "site_settings",
	);

	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	?>
	<?if(!count($arSites)):?>
		<div class="adm-info-message-wrap adm-info-message-red">
			<div class="adm-info-message">
				<div class="adm-info-message-title"><?=GetMessage("LITE_MODULE_NO_SITES")?></div>
				<div class="adm-info-message-icon"></div>
			</div>
		</div>
	<?else:?>
		<?if($REQUEST_METHOD == "POST" && strlen($AddCode.$RemoveCode.$_POST['action']) > 0 && $RIGHT >= "W" && check_bitrix_sessid()){
			if ($_POST['action']) {
				$APPLICATION->RestartBuffer();

				if (!$siteId = $_POST['siteId']) {
					echo Json::encode(['status' => 'error', 'message' => 'No site-id']);
					die();
				}

				$arTemplates = MobileSite::getTemplatesList($siteId);

				if (!$arTemplates) {
					echo Json::encode(['status' => 'error', 'message' => 'No templates']);
					die();
				}
				if (MobileSite::findMobileTemplate($arTemplates)) {
					MobileSite::removeMobileTemplate($siteId, $arTemplates);
					echo Json::encode(['status' => 'ok', 'action' => 'removed']);
					die();
				}

				MobileSite::addMobileTemplate($siteId, $arTemplates);
				echo Json::encode(['status' => 'ok']);

				die();
			} else {
				if (!$_POST['sites']) {
					echo CAdminMessage::ShowMessage(GetMessage("LITE_MODULE_NO_SITES_CHECKED"));
				} else {
					$arFiles = [];
					foreach ($arSites as $siteID => $arSite) {
						if (!in_array($arSite['ID'], $_POST['sites'])) continue;
	
						$arSite['DIR'] = str_replace('//', '/', '/'.$arSite['DIR']);
						if(!strlen($arSite['DOC_ROOT'])){
							$arSite['DOC_ROOT'] = $_SERVER['DOCUMENT_ROOT'];
						}
						$arSite['DOC_ROOT'] = str_replace('//', '/', $arSite['DOC_ROOT'].'/');
						$siteDir = str_replace('//', '/', $arSite['DOC_ROOT'].$arSite['DIR']);
						$arFiles = array_merge($arFiles, SearchFilesInPublicRecursive($siteDir, 'index.php'));
					}
					
					if ($arFiles) {
						$flag = 'ACTUAL';
						foreach ($arFiles as $file) {
							$content = file_get_contents($file);
							$fixContent = '<?/*aspro_fix_start*/?><?if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/aspro.lite/tools/fix_mobile_composite.php")) include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/aspro.lite/tools/fix_mobile_composite.php");?><?/*aspro_fix_end*/?>'.PHP_EOL;
							if ($AddCode && strpos($content, 'fix_mobile_composite.php') === false) {
								CreateBakFile($file);
					
								$content = preg_replace('/(<\?[\s]*require\(\$_SERVER\[["\']DOCUMENT_ROOT["\']\]\.["\']\/bitrix\/header\.php["\']\);?)/', $fixContent.'${1}', $content);
					
								file_put_contents($file, $content);
								$flag = 'ADDED';
							}
							if ($RemoveCode && strpos($content, $fixContent) !== false) {
								CreateBakFile($file, 'with_fix');
					
								$content = str_replace($fixContent, '', $content);
					
								file_put_contents($file, $content);
								$flag = 'REMOVED';
							}
						}
						
						if ($flag === 'ADDED') {
							CAdminNotify::DeleteByModule($moduleID);
							
							$compositeOptions = Helper::getOptions();
							if (strpos($compositeOptions["EXCLUDE_PARAMS"], "is_aspro_mobile") === false) {
								$arExclude = explode(";", $compositeOptions["EXCLUDE_PARAMS"]);
								array_push($arExclude, " is_aspro_mobile");
								$compositeOptions["EXCLUDE_PARAMS"] = implode(";", $arExclude);
		
								Helper::setOptions($compositeOptions);
								bx_accelerator_reset();
							}
						}
						$_SESSION['FLAG'] = $flag;
					}
					LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
				}
			}
			// $APPLICATION->RestartBuffer();
		}?>

		<?if(isset($_SESSION['FLAG']) && $_SESSION['FLAG']):?>
			<?echo CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage("LITE_MODULE_CODE_".$_SESSION['FLAG']), "TYPE" => "OK"));?>
			<?unset($_SESSION['FLAG']);?>
		<?endif;?>

		<form method="post" class="light" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
			<?=bitrix_sessid_post();?>
			<?$tabControl->Begin();?>

			<?foreach($arTabs as $key => $arTab){
				$tabControl->BeginNextTab();?>

				<tr>
					<td colspan="2">
						<div class="notes-block">
							<div align="center">
								<?=BeginNote('align="center"');?>
								<?=(Loc::getMessage('LITE_MODULE_COMPOSITE_INFO'))?>
								<?=EndNote();?>
							</div>
						</div>
					</td>
				</tr>
				
				<?foreach ($arSites as $arSite):?>
					<tr>
						<td width="50%" class="adm-detail-content-cell-l">
							<label class="" for="sites_<?=$arSite['SITE_ID'];?>" title=""><?="{$arSite['NAME']} ({$arSite['SITE_ID']})";?></label>
							<br />
							<a href="javascript:;" data-site_id="<?=$arSite['SITE_ID'];?>" class="add_template">
								<?$arTemplates = MobileSite::getTemplatesList($arSite['SITE_ID']);?>
								<?if (MobileSite::findMobileTemplate($arTemplates)):?>
									<?=GetMessage("LITE_MODULE_ADDED_MOBILE_TEMPLATE");?>
								<?else:?>
									<?=GetMessage("LITE_MODULE_ADD_MOBILE_TEMPLATE");?>
								<?endif;?>
							</a>
						</td>

						<td width="50%" class="adm-detail-content-cell-r">
							<input type="checkbox" id="sites_<?=$arSite['SITE_ID'];?>" name="sites[]" value="<?=$arSite['SITE_ID'];?>" 
						</td>
					</tr>
				<?endforeach;?>
			<?}?>

			<script>
				document.querySelector('.add_template').addEventListener('click', (e) => {
					e.preventDefault();

					const target = e.target

					const url = new URL(location.href);
					url.searchParams.append('sessid', sessid.value)

					const data = new FormData();
					data.append('action', 'add_mobile')
					data.append('siteId', target.dataset.site_id)

					fetch(url, {
						method: 'POST',
						body: data
					})
					 .then(result => result.json())
					 .then(data => {
						if (data.status && data.status === 'ok') {
							if (data.action && data.action === 'removed') {
								target.innerText = '<?=GetMessage('LITE_MODULE_ADD_MOBILE_TEMPLATE')?>';
							} else {
								target.innerText = '<?=GetMessage('LITE_MODULE_ADDED_MOBILE_TEMPLATE')?>';
							}
						}
					 })
					 .catch(data => console.error(data))
				})
			</script>

			<?$tabControl->Buttons();?>
			
			<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="AddCode" class="submit-btn adm-btn-save" value="<?=GetMessage("LITE_MODULE_ADD_CODE")?>">
			<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="RemoveCode" class="submit-btn adm-btn-save" value="<?=GetMessage("LITE_MODULE_REMOVE_CODE")?>">

			<?$tabControl->End();?>
		</form>
	<?endif;?>
<?} else {
	echo CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
}?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>