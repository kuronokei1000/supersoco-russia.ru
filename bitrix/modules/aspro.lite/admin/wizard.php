<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
    CLite as Solution;
	
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

Loc::loadMessages(__FILE__);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
}

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$moduleID = 'aspro.lite';
$bxsender = $_REQUEST['bxsender'] ?? '';
$bInPopup = $bxsender === 'core_window_cdialog';
$site = $request->get('site') ?? '';

if (Loader::includeModule($moduleID)) {
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('LITE_WIZARD_TITLE'));
	}

	if ($bInPopup) {
		$GLOBALS['APPLICATION']->RestartBuffer();
	}

	CLite::setThemeColorsValues('s1');

	$RIGHT = $GLOBALS['APPLICATION']->GetGroupRight($moduleID);
	if ($RIGHT > 'R') {
		?>
		<div class="admin-content">
			<div class="admin-content-inner">
				<?
				$APPLICATION->IncludeComponent(
					"aspro:wizard.solution.lite",
					'',
					array(
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO",
						"IS_ADMIN" => "Y",
						"SITE_ID" => $site,
					),
					false
				);
				?>
			</div>
		</div>
		<?
	} else {
		if ($RIGHT == 'R') {
			CAdminMessage::ShowMessage(Loc::getMessage('LITE_NO_RIGHTS_FOR_WRITING'));
		} else {
			CAdminMessage::ShowMessage(Loc::getMessage('LITE_NO_RIGHTS_FOR_VIEWING'));
		}
	}	
	
	if ($bInPopup) {
		die();
	}
} else {
	CAdminMessage::ShowMessage(Loc::getMessage('LITE_MODULE_NOT_INCLUDED'));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
}
