<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация"); ?>
<? $APPLICATION->IncludeComponent(
	"aspro:auth.lite",
	"",
	[
		"SEF_MODE"          => "Y",
		"SEF_FOLDER"        => "/auth/",
		"SEF_URL_TEMPLATES" => [
			"auth"                 => "",
			"registration"         => "registration/",
			"forgot"               => "forgot-password/",
			"change"               => "change-password/",
			"confirm"              => "confirm-password/",
			"confirm_registration" => "confirm-registration/",
		],
		"PERSONAL"          => "/personal/",
	],
	false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>