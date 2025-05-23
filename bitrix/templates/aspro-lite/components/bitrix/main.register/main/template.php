<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

TSolution\Extensions::init(['profile']);

if($arResult['SHOW_SMS_FIELD']){
	CJSCore::Init('phone_auth');
	TSolution\Extensions::init('phonecode');
}

global $arTheme;

// get phone auth params
list($bPhoneAuthSupported, $bPhoneAuthShow, $bPhoneAuthRequired, $bPhoneAuthUse) = TSolution\PhoneAuth::getOptions();
?>
<div class="registraion-page pk-page">
	<?if (
		$USER->IsAuthorized() ||
		(
			empty($arResult['ERRORS']) &&
			!empty($_POST['register_submit_button']) &&
			$arResult['USE_EMAIL_CONFIRMATION'] === 'N' &&
			!$arResult['SHOW_SMS_FIELD']
		)
	):?>
		<?
		LocalRedirect($arParams['PERSONAL_PAGE']);
		die();
		?>
	<?endif;?>

	<?if($arResult['SHOW_SMS_FIELD']):?>
		<div class="form form--send-sms">
			<div class="form-header">
				<div class="text">
					<div class="title switcher-title font_24 color_222"><?=GetMessage('REGISTER_FIELD_SMS_SENDED_TITLE')?></div>
					<div class="form_desc font_16"><?=GetMessage('main_register_sms_sended', ['#PHONE_NUMBER#' => $arResult["VALUES"]['PHONE_NUMBER']])?></div>
				</div>
			</div>
			<form id="registraion-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform">
				<?if($arResult["BACKURL"] <> ''):?>
					<input type="hidden" name="backurl" value="<?=htmlspecialcharsbx($arResult['BACKURL'])?>" />
				<?endif;?>
				
				<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult['SIGNED_DATA'])?>" />

				<div class="form_body">
					<div class="form-group fill-animate phone_code">
						<?
						if(array_key_exists('SMS_CODE', $arResult['ERRORS'])){
							$class = 'class="error"';
						}
						?>
						<label class="font_14" for="input_SMS_CODE"><?=GetMessage('REGISTER_FIELD_SMS_CODE')?> <span class="required-star">*</span></label>
						<div class="input">
							<input id="input_SMS_CODE" class="form-control required" size="30" type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult['SMS_CODE'])?>" autocomplete="off" <?=$class?> />
						</div>
					</div>
				</div>
				<div class="form-footer hidden">
					<button class="btn btn-default btn-lg btn-wide" type="submit" name="code_submit_button" value="Y"><?=GetMessage('main_register_sms_send')?></button>
				</div>
			</form>
			<div id="bx_register_error" style="display:none"><?ShowError('error')?></div>
			<div id="bx_register_resend"></div>
			<script>
			document.regform.SMS_CODE.focus();

			$(document).ready(function(){
				$("#registraion-page-form").validate({
					highlight: function( element ){
						$(element).parent().addClass('error');
					},
					unhighlight: function( element ){
						$(element).parent().removeClass('error');
					},
					submitHandler: function( form ){
						if($(form).valid()){
							var $button = $(form).find('button[type=submit]');
							if($button.length){
								if(!$button.hasClass('loadings')){
									$button.addClass('loadings');

									var eventdata = {type: 'form_submit', form: form, form_name: 'REGISTER'};
									BX.onCustomEvent('onSubmitForm', [eventdata]);
								}
							}
						}
					},
					errorPlacement: function( error, element ){
						error.insertAfter(element);
					},
				});

				$("#registraion-page-form .phone_code input[type=text]").phonecode(
					<?=CUtil::PhpToJSObject(
						[
							'USER_ID' => $arResult['VALUES']['USER_ID'],
							'USER_PHONE_NUMBER' => $arResult['VALUES']['PHONE_NUMBER'],
						]
					)?>,
					function(input, data, response) {
						if (
							typeof response !== 'undefined' &&
							response === 'true'
						) {
							let $form = $(input).closest('form');
	
							if (
								$form.length &&
								!$form.find('button[type=submit].loadings').length
							) {
								$form.find('button[type=submit]').trigger('click');
							}
						}
					}
				);
			});

			new BX.PhoneAuth({
				containerId: 'bx_register_resend',
				errorContainerId: 'bx_register_error',
				interval: <?=$arResult['PHONE_CODE_RESEND_INTERVAL']?>,
				data:
					<?=CUtil::PhpToJSObject([
						'signedData' => $arResult['SIGNED_DATA'],
					])?>,
				onError:
					function(response)
					{
						var errorDiv = BX('bx_register_error');
						var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
						errorNode.innerHTML = '';
						for(var i = 0; i < response.errors.length; i++)
						{
							errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
						}
						errorDiv.style.display = '';
					}
			});
			</script>
		</div>
	<?else:?>
		<div class="form">
			<?if ($arResult['ERRORS']):?>
				<?
				foreach ($arResult['ERRORS'] as $key => $error) {
					if (intval($key) == 0 && $key !== 0) {
						$arResult['ERRORS'][$key] = str_replace('#FIELD_NAME#', $key.'&quot;'.GetMessage('REGISTER_FIELD_'.$key).'&quot;', $error);
					}
				}
				?>
				<div class="alert alert-danger"><?ShowError(implode('<br />', $arResult['ERRORS']))?></div>
			<?endif;?>

			<?if (
				empty($arResult['ERRORS']) &&
				!empty($_POST['register_submit_button']) &&
				$arResult['USE_EMAIL_CONFIRMATION'] === 'Y'
			):?>
				<div class="alert alert-success"><?=GetMessage('REGISTER_EMAIL_WILL_BE_SENT')?></div>
			<?else:?>
				<form id="registraion-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" >
					<?if (TSolution::checkContentFile(SITE_DIR."include/register_description.php")):?>
						<div class="top-text font_16">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/register_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("REGISTER_INCLUDE_AREA"), ));?>
						</div>
					<?endif;?>

					<?if($arResult["BACKURL"] <> ''):?>
						<input type="hidden" name="backurl" value="<?=htmlspecialcharsbx($arResult["BACKURL"])?>" />
					<?endif;?>

					<input type="hidden" name="register_submit_button" value="reg" />
					
					<?
					$arTmpField=$arFields=$arUFields=array();
					$arTmpField=array_combine($arResult['SHOW_FIELDS'], $arResult['SHOW_FIELDS']);
					unset($arTmpField["PASSWORD"]);
					unset($arTmpField["CONFIRM_PASSWORD"]);

					if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"){
						foreach($arParams["USER_PROPERTY"] as $name){
							$arUFields[$name]=$arResult["USER_PROPERTIES"]["DATA"][$name];
						}
					}

					if($arParams["SHOW_FIELDS"]){
						foreach($arParams["SHOW_FIELDS"] as $name){
							$arFields[$arTmpField[$name]]=$name;
						}
					}else{
						$arFields=$arTmpField;
					}
					$arFields["PASSWORD"]="PASSWORD";
					$arFields["CONFIRM_PASSWORD"]="CONFIRM_PASSWORD";
					$arFields["LOGIN"]="LOGIN";
					$class = "form-control";

					if($arTheme['CABINET']['DEPENDENT_PARAMS']["PERSONAL_ONEFIO"]["VALUE"] != "N"){
						$arResult["VALUES"]['NAME'] = trim(implode(' ', [$arResult["VALUES"]['LAST_NAME'], $arResult["VALUES"]['NAME'], $arResult["VALUES"]['SECOND_NAME']]));

						unset($arFields["LAST_NAME"]);
						unset($arFields["SECOND_NAME"]);
					}
					?>
					<div class="form-body">
						<?foreach ($arFields as $FIELD):?>
							<?if($FIELD === 'PHONE_NUMBER'):?>
								<?continue;?>
							<?endif;?>

							<?if(
								$arTheme['CABINET']['DEPENDENT_PARAMS']["LOGIN_EQUAL_EMAIL"]["VALUE"] != "Y" ||
								(
									$FIELD != "LOGIN" &&
									$arTheme['CABINET']['DEPENDENT_PARAMS']["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y"
								)
							):?>
								<div class="form-group fill-animate <?=($arResult["VALUES"][$FIELD] ? 'input-filed' : '');?>">
									<label class="font_14" for="input_<?=$FIELD?>">
										<?=(($arTheme['CABINET']['DEPENDENT_PARAMS']["PERSONAL_ONEFIO"]["VALUE"] != "N" && $FIELD == "NAME") ? GetMessage("REGISTER_FIELD_ONENAME") : GetMessage("REGISTER_FIELD_".$FIELD));?> <?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="required-star">*</span><?endif;?>
									</label>

									<?if(array_key_exists($FIELD, $arResult["ERRORS"])):?>
										<?$class.=' error'?>
									<?endif;?>
									<div class="input">
							<?endif;?>
											<?switch ($FIELD){
												case "PASSWORD":?>
													<input size="30" type="password" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="form-control password <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>"  />

												<?break;
												case "CONFIRM_PASSWORD":?>
													<input size="30" type="password" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="form-control confirm_password <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>" />

												<?break;
												case "PERSONAL_GENDER":?>
													<select name="REGISTER[<?=$FIELD?>]" id="input_<?=$FIELD;?>">
														<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
														<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
														<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
													</select>
													<?break;
												case "PERSONAL_COUNTRY":
												case "WORK_COUNTRY":?>
													<select name="REGISTER[<?=$FIELD?>]" id="input_<?=$FIELD;?>">
														<?foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value){?>
															<option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
														<?}?>
													</select>
													<?break;
												case "PERSONAL_PHOTO":
												case "WORK_LOGO":?>
													<input size="30" type="file" class="form-control" id="input_<?=$FIELD;?>" name="REGISTER_FILES_<?=$FIELD?>" />
													<?break;
												case "PERSONAL_NOTES":
												case "WORK_NOTES":?>
													<textarea cols="30" rows="5" class="form-control" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>

												<?case "PERSONAL_STREET":?>
													<textarea cols="30" rows="5" class="form-control" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>
													<?break;?>
												<?case "EMAIL":?>
													<input size="30" type="email" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" <?=($arResult["EMAIL_REQUIRED"] || in_array($FIELD, $arResult["REQUIRED_FIELDS"]) ? "required" : "");?> value="<?=$arResult["VALUES"][$FIELD]?>" class="<?=$class?>" id="emails"/>
												<?break;?>
												<?case "NAME":?>
													<input size="30" type="text" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=$arResult["VALUES"][$FIELD]?>" class="<?=$class?>"/>
												<?break;?>
												<?case "PERSONAL_PHONE":?>
													<input size="30" type="text" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" class="form-control phone_input <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=$arResult["VALUES"][$FIELD]?>" />
												<?break;?>
												<?break;
												default:?>
													<?// hide login?>
													<input size="30" id="input_<?=$FIELD;?>" class="form-control" <?=(($FIELD == "LOGIN" && $arTheme['CABINET']['DEPENDENT_PARAMS']["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y") ? 'type="hidden" value="1"' : 'type="text"');?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" />
													<?if ($FIELD == "PERSONAL_BIRTHDAY"){?>
														<?$APPLICATION->IncludeComponent(
															'bitrix:main.calendar',
															'',
															array(
																'SHOW_INPUT' => 'N',
																'FORM_NAME' => 'regform',
																'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
																'SHOW_TIME' => 'N'
															),
															null,
															array("HIDE_ICONS"=>"Y")
														);?>
													<?}?>
													<?break;?>
											<?}?>
							<?if(
								$arTheme['CABINET']['DEPENDENT_PARAMS']["LOGIN_EQUAL_EMAIL"]["VALUE"] != "Y" ||
								(
									$FIELD != "LOGIN" &&
									$arTheme['CABINET']['DEPENDENT_PARAMS']["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y"
								)
							):?>
									</div>

									<?if(array_key_exists($FIELD, $arResult["ERRORS"])):?>
										<label class="error"><?=GetMessage("REGISTER_FILL_IT")?></label>
									<?endif;?>
									<div class="text_block font_13">
										<?if(
											$arTheme['CABINET']['DEPENDENT_PARAMS']["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y" &&
											$FIELD == 'EMAIL'
										):?>
											<?=GetMessage("REGISTER_FIELD_TEXT_".$FIELD.'_EQUAL');?>
										<?else:?>
											<?=GetMessage("REGISTER_FIELD_TEXT_".$FIELD);?>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>

						<?if($arUFields):?>
							<?foreach($arUFields as $arUField):?>
								<div class="r">
									<label><span><?=$arUField["EDIT_FORM_LABEL"];?>&nbsp;<?if ($arUField["MANDATORY"] == "Y"):?><span class="required-star">*</span><?endif;?></span></label>
									<?$APPLICATION->IncludeComponent(
									"bitrix:system.field.edit",
									$arUField["USER_TYPE"]["USER_TYPE_ID"],
									array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));?>
								</div>
							<?endforeach;?>
						<?endif;?>

						<?if($arResult["USE_CAPTCHA"] == "Y"):?>
							<div class="clearboth"></div>
							<div class="form-control captcha-row clearfix">
								<label for="captcha_word" class="font_14"><span><?=(TSolution\ReCaptcha::checkRecaptchaActive() ? GetMessage("FORM_GENERAL_RECAPTCHA") : GetMessage("REGISTER_CAPTCHA_PROMT"))?>&nbsp;<span class="required-star">*</span></span></label>
								<div class="captcha_image">
									<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHA_CODE"])?>" class="captcha_img" border="0" />
									<input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHA_CODE"])?>" />
									<div class="captcha_reload"></div>
									<span class="refresh"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
								</div>
								<div class="captcha_input">
									<input type="text" class="inputtext form-control captcha" name="captcha_word" size="30" maxlength="50" value="" required />
								</div>
							</div>
							<div class="clearboth"></div>
						<?endif;?>
					</div>

					<div class="form-footer">
						<button class="btn btn-default btn-lg btn-wide" type="submit" name="register_submit_button1" value="<?=GetMessage("AUTH_REGISTER")?>">
							<?=GetMessage("REGISTER_REGISTER")?>
						</button>
						<div class="clearboth"></div>

						<?if(COption::GetOptionString("aspro.lite", "SHOW_LICENCE", "N") == "Y"):?>
							<div class="licence_block">
								<label for="licenses_reg">
									<span><?include(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/licenses_text.php"));?></span>
								</label>
								<input type="checkbox" id="licenses_reg" name="licenses_popup" checked required value="Y" class="form-checkbox__input">
							</div>
						<?endif;?>
					</div>
				</form>

				<script>
				$(document).ready(function(){
					$.validator.addClassRules({
						'phone_input':{
							regexp: arAsproOptions['THEME']['VALIDATE_PHONE_MASK']
						}
					});

					<?if($bPhoneAuthSupported && $bPhoneAuthShow):?>
					$('#registraion-page-form').submit(function(){
						$(this).find('[name=PHONE_NUMBER]').remove();
						var $phone = $('#input_PERSONAL_PHONE');
						if($phone.length){
							var phone = $phone.val();
							if(phone.length){
								$(this).append('<input type="hidden" name="REGISTER[PHONE_NUMBER]" value="' + phone + '" />');
							}
						}
					});
					<?endif;?>

					$('form#registraion-page-form').validate({
						rules:{emails: 'email'},
						highlight: function( element ){
							$(element).parent().addClass('error');
						},
						unhighlight: function( element ){
							$(element).parent().removeClass('error');
						},
						submitHandler: function( form ){
							if($(form).valid()){
								var $button = $(form).find('button[type=submit]');
								if($button.length){
									if(!$button.hasClass('loadings')){
										$button.addClass('loadings');

										var eventdata = {type: 'form_submit', form: form, form_name: 'REGISTER'};
										BX.onCustomEvent('onSubmitForm', [eventdata]);
									}
								}
							}
						},
						errorPlacement: function( error, element ){
							error.insertAfter(element);
						},
						messages: {
							'captcha_word': {
								remote: '<?=GetMessage("VALIDATOR_CAPTCHA")?>'
							},
							licenses_popup: {
								required : BX.message('JS_REQUIRED_LICENSES')
							}
						},
					});

					$('#input_LOGIN').rules("add", {
						required: true,
						minlength: 3,
						messages:{
							minlength: jQuery.validator.format(BX.message('LOGIN_LEN'))
						}
					});

					$("form[name=bx_auth_servicesform_inline]").validate();

					setTimeout(function(){
						$('#registraion-page-form').find('input:visible').eq(0).focus();
					}, 50);

					if(arAsproOptions['THEME']['PHONE_MASK'].length){
						var base_mask = arAsproOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
						$('form#registraion-page-form input.phone_input').inputmask('mask', {'mask': arAsproOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
						$('form#registraion-page-form input.phone_input').blur(function(){
							if( $(this).val() == base_mask || $(this).val() == '' ){
								if( $(this).hasClass('required') ){
									$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
								}
							}
						});
					}
				});
				</script>

				<div class="social_block">
					<?$APPLICATION->IncludeComponent(
						"bitrix:system.auth.form",
						"popup",
						array(
							"TITLE" => "",
							"PROFILE_URL" => SITE_DIR."auth/",
							"SHOW_ERRORS" => "Y",
							"POPUP_AUTH" => "Y",
						)
					);?>
				</div>
			<?endif;?>
		</div>
	<?endif;?>
</div>