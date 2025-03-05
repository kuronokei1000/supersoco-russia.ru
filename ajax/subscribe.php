<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$itemID = (int)$_GET['id'];
?>
<form class="form popup subform">
	<input type="hidden" name="manyContact" value="N">
	<?=bitrix_sessid_post();?>
	<input type="hidden" name="itemId" value="<?=$itemID;?>">
	<input type="hidden" name="siteId" value="s1">
	<input type="hidden" name="contactFormSubmit" value="Y">
	<div class="form-header">
		<div class="text">
			<div class="title font_24 color_222"><?=\Bitrix\Main\Localization\Loc::getMessage('SUBSCRIBE_ITEM');?></div>
		</div>
	</div>
	<div class="form-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="font_14"><span><?=\Bitrix\Main\Localization\Loc::getMessage('SUBSCRIBE_ITEM_EMAIL');?>&nbsp;<span class="star">*</span></span></label>
					<div class="input">
						<input type="text" class="form-control inputtext input-filed" data-sid="CLIENT_NAME" required="" name="contact[1][user]" value="">
					</div>
					<div class="mess"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-footer clearfix">
		<div>
			<button type="submit" class="btn btn-default" value="<?=\Bitrix\Main\Localization\Loc::getMessage('SUBSCRIBE_SEND');?>" name="web_form_submit">
				<?=\Bitrix\Main\Localization\Loc::getMessage('SUBSCRIBE_SEND');?>
			</button>
		</div>
	</div>
</form>
<script type="text/javascript">
$('input[name="siteId"]').val(arAsproOptions['SITE_ID']);

$('form.subform').validate({
	highlight: function( element ){
		$(element).parent().addClass('error');
		$('form.subform .form-body .mess').html('');
	},
	unhighlight: function( element ){
		$(element).parent().removeClass('error');
		$('form.subform .form-body .mess').html('');
	},
	submitHandler: function( form ){
		$('form.subform .form-body .mess').html('');

		if($('form.subform').valid()){
			setTimeout(function() {
				$(form).find('button[type="submit"]').attr("disabled", "disabled");
			}, 300);

			BX.ajax.submitAjax($('form.subform')[0], {
				method : 'POST',
				url: '/bitrix/components/bitrix/catalog.product.subscribe/ajax.php',
				processData : true,
				onsuccess: function(response){
					resultForm = BX.parseJSON(response, {});
					if (resultForm.success) {
						var email = $('form.subform input.email').val();
						$('form.subform .form-body').html('<div class="success">' + resultForm.message + '</div>');
						$('form.subform .form-footer').html('');
						$('form.subform .form-footer').hide();

						$.ajax({
							url: arAsproOptions.SITE_DIR + 'ajax/subscribe_sync.php',
							dataType: "json",
							type: "POST",
							data: BX.ajax.prepareData({
								sessid: BX.bitrix_sessid(),
								subscribe: 'Y',
								itemId: '<?=$itemID;?>',
								itemEmail: email,
								siteId: arAsproOptions['SITE_ID']
							}),
							success: function(id){
								
							},
						});

						reloadCounters();							
					}
					else if (resultForm.error) {
						var errorMessage = resultForm.message;
						if(resultForm.hasOwnProperty('typeName')){
							errorMessage = resultForm.message.replace('USER_CONTACT',
								resultForm.typeName);
						}

						$('form.subform .form-body .mess').text(errorMessage);
					}
				}
			});
		}
	},
	errorPlacement: function( error, element ){
		error.insertAfter(element);
	},
	/*messages:{
		licenses_popup: {
		required : BX.message('JS_REQUIRED_LICENSES')
		}
	}*/
});
</script>
