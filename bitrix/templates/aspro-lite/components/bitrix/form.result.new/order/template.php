<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?use \Bitrix\Main\Localization\Loc;?>
<div class="form order<?=($arResult['FORM_NOTE'] ? ' success' : '')?><?=($arResult['isFormErrors'] == 'Y' ? ' error' : '')?>">
	<!--noindex-->
	<?if($arResult["isFormErrors"] == "Y"):?>
		<div class="form-error alert alert-danger"><?=$arResult["FORM_ERRORS_TEXT"]?></div>
	<?endif;?>
	<?=$arResult["FORM_HEADER"]?>
	<?=bitrix_sessid_post();?>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<?if($arResult["isFormDescription"] == "Y"):?>
					<div class="description"><?=$arResult["FORM_DESCRIPTION"]?></div>
				<?endif;?>
			</div>
			<div class="col-md-12 col-sm-12">
				<?if(is_array($arResult["QUESTIONS"])):?>
					<?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
						<?if($FIELD_SID == "ORDER_LIST"):?>
							<div class="hidden">
						<?endif;?>
						<?TSolution::drawFormField($FIELD_SID, $arQuestion, 'ORDER', $arParams);?>
						<?if($FIELD_SID == "ORDER_LIST"):?>
							</div>
						<?endif;?>
					<?endforeach;?>
				<?endif;?>
				<?if($arResult["isUseCaptcha"] == "Y"):?>
					<div class="form-control captcha-row clearfix">
						<label class="font_14"><span><?=GetMessage("FORM_CAPRCHE_TITLE")?>&nbsp;<span class="star">*</span></span></label>
						<div class="captcha_image">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" border="0" />
							<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
							<div class="captcha_reload"></div>
						</div>
						<div class="captcha_input">
							<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
						</div>
					</div>
				<?elseif($arParams["HIDDEN_CAPTCHA"] == "Y"):?>
					<textarea name="nspm" style="display:none;"></textarea>
				<?endif;?>
				<div class="form-footer">
					<div>
						<input type="submit" class="btn btn-default btn-lg btn-wide" value="<?=$arResult["arForm"]["BUTTON"]?>" name="web_form_submit">
					</div>
					<?if($arParams["SHOW_LICENCE"] == "Y"):?>
						<div class="licence_block">
							<input type="hidden" name="aspro_lite_form_validate">
							<label for="licenses_inline_<?=$arResult["arForm"]["ID"];?>">
								<span><?include(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/licenses_text.php"));?></span>
							</label>
							<input type="checkbox" class="form-checkbox__input" id="licenses_inline_<?=$arResult["arForm"]["ID"];?>" checked name="licenses_popup" required value="Y">
						</div>
					<?endif;?>
				</div>
			</div>
		</div>
	<?=$arResult["FORM_FOOTER"]?>
	<!--/noindex-->
	<script type="text/javascript">
	BX.message({
            FORM_FILE_DEFAULT: '<?= Loc::getMessage('FORM_FILE_DEFAULT') ?>',
		});
	$(document).ready(function(){
		if(arAsproOptions['THEME']['USE_FULLORDER_GOALS'] !== 'N'){
			var eventdata = {goal: 'goal_order_begin'};
			BX.onCustomEvent('onCounterGoals', [eventdata]);
		}
		
		var sessionID = '<?=bitrix_sessid()?>';
		$('input[data-sid=SESSION_ID]').val(sessionID);

		$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').validate({
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').valid() ){
					setTimeout(function() {
						$(form).find('button[type="submit"]').attr("disabled", "disabled");
					}, 300);
					var eventdata = {type: 'form_submit', form: form, form_name: '<?=$arResult["arForm"]["VARNAME"]?>'};
					BX.onCustomEvent('onSubmitForm', [eventdata]);
				}
			},
			errorPlacement: function( error, element ){
				let uploader = element.closest('.uploader');
				if (uploader.length) {
					error.insertAfter(uploader);
				}
				else {
					error.insertAfter(element);
				}
			},
			messages:{
				licenses_popup: {
				required : BX.message('JS_REQUIRED_LICENSES')
				}
			}
		});
		
		if(arAsproOptions['THEME']['PHONE_MASK'].length){
			var base_mask = arAsproOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input.phone').inputmask('mask', {'mask': arAsproOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
			$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('div.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}
		
		if(arAsproOptions['THEME']['DATE_MASK'].length)
		{
			$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input.date').inputmask('datetime', {
				'inputFormat':  arAsproOptions['THEME']['DATE_MASK'],
				'placeholder': arAsproOptions['THEME']['DATE_PLACEHOLDER'],
				'showMaskOnHover': false
			});
		}

		if(arAsproOptions['THEME']['DATETIME_MASK'].length)
		{
			$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input.datetime').inputmask('datetime', {
				'inputFormat':  arAsproOptions['THEME']['DATETIME_MASK'],
				'placeholder': arAsproOptions['THEME']['DATETIME_PLACEHOLDER'],
				'showMaskOnHover': false
			});
		}

		$('.jqmClose').on('click', function(e){
			e.preventDefault();
			$(this).closest('.jqmWindow').jqmHide();
		});

		$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('FORM_FILE_DEFAULT')});
		$(document).on('change', 'form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input[type=file]', function(){
			if($(this).val())
			{
				$(this).closest('.uploader').addClass('files_add');
				$(this).valid();
			}
			else
			{
				$(this).closest('.uploader').removeClass('files_add');
			}
		});

		$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] .add_file').on('click', function(){
			var index = $(this).closest('.input').find('input[type=file]').length+1;
			$('<input type="file" id="POPUP_FILE'+index+'" name="FILE_n'+index+'"   class="inputfile" value="" />').insertBefore($(this));
			$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"] input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('FORM_FILE_DEFAULT')});
		});
	});
	</script>
</div>