<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

TSolution\Extensions::init(['personal', 'profile']);
?>
<div class="lk-page">
	<div class="subscribe-edit-main form">
		<?if($arResult["MESSAGE"] || $arResult["ERROR"]):?>
			<div class="top-form messages bordered_block">
				<?if($arResult["MESSAGE"]):?>
					<div class="alert alert-success"><?=implode('<br />', $arResult["MESSAGE"]);?></div>
				<?endif;?>

				<?if($arResult["ERROR"]):?>
					<div class="alert alert-danger"><?=implode('<br />', $arResult["ERROR"]);?></div>
				<?endif;?>
			</div>
		<?endif;?>
		<?
		//whether to show the forms
		if($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"]))
		{
			//show confirmation form
			if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y")
			{
				include("confirmation.php");
			}
			
			//show current authorization section
			if($USER->IsAuthorized() && ($arResult["ID"] == 0 || $arResult["SUBSCRIPTION"]["USER_ID"] == 0))
			{
				include("authorization.php");
			}
			//show authorization section for new subscription
			if($arResult["ID"]==0 && !$USER->IsAuthorized())
			{
				if($arResult["ALLOW_ANONYMOUS"]=="N" || ($arResult["ALLOW_ANONYMOUS"]=="Y" && $arResult["SHOW_AUTH_LINKS"]=="Y"))
				{
					include("authorization_new.php");
				}
			}
			//setting section
			include("setting.php");
			//status and unsubscription/activation section
			if($arResult["ID"]>0)
			{
				include("status.php");
			}?>
		<?}?>
	</div>
</div>