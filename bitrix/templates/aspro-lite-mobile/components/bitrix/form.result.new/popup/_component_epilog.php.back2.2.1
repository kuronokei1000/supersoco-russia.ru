<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $USER;

TSolution\Extensions::init(['validate', 'uniform']);
?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();

	$fio = $USER->GetFullName();
	$phone = $arUser['PERSONAL_PHONE'];
	$email = $USER->GetEmail();
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		try{
			<?if ($fio):?>
				$('.popup.form input[data-sid=CLIENT_NAME], .popup.form input[data-sid=FIO], .popup.form input[data-sid=NAME]').val('<?=$USER->GetFullName()?>');
			<?endif;?>
			<?if ($phone):?>
				$('.popup.form input[data-sid=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
			<?endif;?>
			<?if ($email):?>
				$('.popup.form input[data-sid=EMAIL]').val('<?=$USER->GetEmail()?>');
			<?endif;?>
		}
		catch(e){
		}
	});
	</script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["WEB_FORM_ID"], "");?>