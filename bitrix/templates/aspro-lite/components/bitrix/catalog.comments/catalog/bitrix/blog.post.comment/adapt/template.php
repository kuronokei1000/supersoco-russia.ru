<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @global CMain $APPLICATION */
CJSCore::Init(array("image"));
require_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/vendor/php/solution.php";

$application = \Bitrix\Main\Application::getInstance();
$request = $application->getContext()->getRequest();
$post = $request->getPostList();

$bAjaxPost = $arResult["is_ajax_post"] === 'Y';
?>

<div class="comments-block__inner-wrapper">
<? if ($arResult['IMAGES'] && !$bAjaxPost): ?>
	<div class="reviews-gallery-block reviews-gallery-block--top" >
		<div class="reviews-gallery-title color_222 font_14 font_large">
			<?= GetMessage('REVIEWS_GALLERY_TITLE'); ?>
		</div>
		<?
		$arImages = array_map(function($array){
			return [
				'src' => CFile::GetPath($array['FILE_ID']),
				'preview' => CFile::ResizeImageGet($array['FILE_ID'], ["width" => 80, "height" => 80], BX_RESIZE_IMAGE_EXACT)['src'],
				'alt' => '',
				'title' => '',
			];
		}, $arResult['IMAGES']);
		?>
		<?= TSolution\Functions::showGallery($arImages, [
			'BREAKPOINTS' => [
				'xs' => 3,
				'xsm' => 4,
				'sm' => 5,
				'xmd' => 6,
				'md' => 7,
				'lg' => 8,
				'xl' => 10,
			],
			'CONTAINER_CLASS' => 'gallery-review',
		]); ?>
	</div>
<? endif; ?>
<?
global $pathForAjax;
$pathForAjax = $templateFolder;
?>
<script>
BX.ready( function(){
	if (BX.viewImageBind) {
		BX.viewImageBind('blg-comment-<?=$arParams["ID"]?>', false, {
			tag:'IMG', 
			attr: 'data-bx-image'
		});
	}
});
</script>
<div id="reviews_sort_continer" class="hidden"></div>
<div class="blog-comments" id="blg-comment-<?=$arParams["ID"]?>">
<a name="comments" class="hidden"></a>

<? if(!$bAjaxPost): ?>
	<? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/script.php"); ?>
	<? if ($arResult['IMAGES']): ?>
		<script>
			InitFancyBox();
		</script>
	<? endif; ?>
<? else: ?>
	<? $APPLICATION->RestartBuffer(); ?>
	<script>
		window.BX = top.BX;
		<? if ($arResult["use_captcha"]===true): ?>
			var cc ='<?=$arResult["CaptchaCode"]?>';
			if(BX('captcha')){
				BX('captcha').src='/bitrix/tools/captcha.php?captcha_code='+cc;
			}

			if(BX('captcha_code')){
				BX('captcha_code').value = cc;
			}

			if(BX('captcha_word')){
				BX('captcha_word').value = "";
			}
		<? endif; ?>
		if(!top.arImages)
			top.arImages = [];
		if(!top.arImagesId)
			top.arImagesId = [];
		<? if($arResult["Images"]): ?>
			<? foreach($arResult["Images"] as $aImg): ?>
				top.arImages.push('<?=CUtil::JSEscape($aImg["SRC"])?>');
				top.arImagesId.push('<?=$aImg["ID"]?>');
			<? endforeach; ?>
		<? endif; ?>
	</script>
	<? if (strlen($arResult["COMMENT_ERROR"])>0): ?>
		<script>top.commentEr = 'Y';</script>
		<div class="alert alert-danger blog-note-box blog-note-error">
			<div class="blog-error-text">
				<?=$arResult["COMMENT_ERROR"]?>
			</div>
		</div>
	<? endif; ?>
<? endif; ?>

<? if (strlen($arResult["MESSAGE"]) > 0): ?>
	<div class="blog-textinfo blog-note-box">
		<div class="blog-textinfo-text">
			<?=$arResult["MESSAGE"]?>
		</div>
	</div>
<? endif; ?>

<? if (strlen($arResult["ERROR_MESSAGE"]) > 0): ?>
	<div class="alert alert-danger blog-note-box blog-note-error">
		<div class="blog-error-text" id="blg-com-err">
			<?=$arResult["ERROR_MESSAGE"]?>
		</div>
	</div>
<? endif; ?>

<? if (strlen($arResult["FATAL_MESSAGE"]) > 0): ?>
	<div class="alert alert-danger blog-note-box blog-note-error">
		<div class="blog-error-text">
			<?=$arResult["FATAL_MESSAGE"]?>
		</div>
	</div>
<? else: ?>
	<? if ($arResult["imageUploadFrame"] == "Y"): ?>
		<script>
			<? if (!empty($arResult["Image"])): ?>
				top.bxBlogImageId = top.arImagesId.push('<?=$arResult["Image"]["ID"]?>');
				top.arImages.push('<?=CUtil::JSEscape($arResult["Image"]["SRC"])?>');
				top.bxBlogImageIdWidth = '<?=CUtil::JSEscape($arResult["Image"]["WIDTH"])?>';
			<? elseif (strlen($arResult["ERROR_MESSAGE"]) > 0): ?>
				top.bxBlogImageError = '<?=CUtil::JSEscape($arResult["ERROR_MESSAGE"])?>';
			<? endif; ?>
		</script>
		<? die(); ?>
	<? else:?>
		<? if (!$bAjaxPost && $arResult["CanUserComment"]): ?>
			<? $ajaxPath = $templateFolder.'/ajax.php'; ?>
			<div class="js-form-comment" id="form_comment_" style="display:none;">
				<div id="form_c_del" style="display:none;">
					<div class="blog-comment__form">
						<form enctype="multipart/form-data" method="POST" name="form_comment" id="form_comment" action="<?=$ajaxPath; ?>">
							<input type="hidden" name="parentId" id="parentId" value="">
							<input type="hidden" name="edit_id" id="edit_id" value="">
							<input type="hidden" name="act" id="act" value="add">
							<input type="hidden" name="post" value="Y">
							
							<? if(isset($request["IBLOCK_ID"])): ?>
								<input type="hidden" name="IBLOCK_ID" value="<?=(int)$request["IBLOCK_ID"]; ?>">
							<? endif; ?>
							
							<? if (isset($request["ELEMENT_ID"])): ?>
								<input type="hidden" name="ELEMENT_ID" value="<?=(int)$request["ELEMENT_ID"]; ?>">
							<? endif; ?>
							
							<? if(isset($request["XML_ID"])): ?>
								<input type="hidden" name="XML_ID" value="<?=$request["XML_ID"]; ?>">
							<? endif; ?>
							
							<? if(isset($request["SITE_ID"])): ?>
								<input type="hidden" name="SITE_ID" value="<?=htmlspecialcharsbx($request["SITE_ID"]); ?>">
							<? endif;?>

							<?= makeInputsFromParams($arParams["PARENT_PARAMS"]); ?>
							<?= bitrix_sessid_post(); ?>

							<div class="form popup blog-comment-fields outer-rounded-x bordered">
								<div class="form-header">
									<? if(empty($arResult["User"])): ?>
										<div class="blog-comment-field blog-comment-field-user">
											<div class="row form">
												<div class="col-md-6 col-sm-6">
													<div class="form-group <?=($_SESSION["blog_user_name"] ? 'input-filed' : '');?>">
														<label for="user_name"><?=GetMessage("B_B_MS_NAME")?> <span class="required-star">*</span></label>
														<div class="input">
														<input maxlength="255" size="30" class="form-control" required tabindex="3" type="text" name="user_name" id="user_name" value="<?=htmlspecialcharsEx($_SESSION["blog_user_name"])?>">
														</div>
													</div>
												</div>
												<div class="col-md-6 col-sm-6">
													<div class="form-group <?=($_SESSION["blog_user_email"] ? 'input-filed' : '');?>">
														<label for="user_email">E-mail</label>
														<div class="input">
														<input maxlength="255" size="30" class="form-control" tabindex="4" type="text" name="user_email" id="user_email" value="<?=htmlspecialcharsEx($_SESSION["blog_user_email"])?>">
														</div>
													</div>
												</div>
											</div>
										</div>
									<? endif; ?>

									<? if($arParams["NOT_USE_COMMENT_TITLE"] != "Y"): ?>
										<div class="row form">
											<div class="col-md-12">
												<div class="form-group">
													<label for="user_sbj"><?=GetMessage("BPC_SUBJECT")?></label>
													<div class="input">
														<input maxlength="255" size="70" class="form-control" tabindex="3" type="text" name="subject" id="user_sbj" value="">
													</div>
												</div>
											</div>
										</div>
									<? endif; ?>

									<div class="row form">
										<div class="col-md-12">
											<div class="form-group">
												<label class="rating_label" data-hide><?=GetMessage("BPC_RATING")?> <span class="required-star">*</span></label>
												<div class="votes_block nstar big with-text" data-hide>
													<div class="ratings">
														<div class="inner_rating rating__star-svg">
															<? for ($i=1; $i<=5; $i++): ?>
																<div class="item-rating rating__star-svg" data-message="<?= GetMessage('RATING_MESSAGE_'.$i); ?>">
																	<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . "/images/svg/catalog/item_icons.svg#star-13-13", '', [
																		'WIDTH' => 16,
																		'HEIGHT' => 16,
																	]); ?>
																</div>
															<? endfor; ?>
														</div>
													</div>
													<div class="rating_message muted" data-message="<?=GetMessage('RATING_MESSAGE_0')?>"><?=GetMessage('RATING_MESSAGE_0')?></div>
													<input class="hidden" name="rating" required>
												</div>
											</div>
										</div>
									</div>

									<div class="row form virtues" data-hide>
										<div class="col-md-12">
											<div class="form-group">
												<label for="virtues"><?=GetMessage("BPC_VIRTUES")?></label>
												<div class="input">
												<textarea rows="3" class="form-control" tabindex="3" name="virtues" id="virtues" value=""></textarea>
												</div>
											</div>
										</div>
									</div>

									<div class="row form limitations" data-hide>
										<div class="col-md-12">
											<div class="form-group">
												<label for="limitations"><?=GetMessage("BPC_LIMITATIONS")?></label>
												<div class="input">
												<textarea rows="3" class="form-control" tabindex="3" name="limitations" id="limitations" value=""></textarea>
												</div>
											</div>
										</div>
									</div>

									<div class="row form comment">
										<div class="col-md-12">
											<div class="form-group">
												<label for="comment"><?=GetMessage("BPC_MESSAGE")?></label>
												<div class="input">
													<textarea rows="3" class="form-control" tabindex="3" name="comment" id="comment" value=""></textarea>
												</div>
											</div>
										</div>
									</div>

									<div class="row form files" data-hide>
										<div class="col-md-12">
											<div class="form-group">
												<div class="input">
													<input type="file" class="form-control" tabindex="3" name="comment_images[]" id="comment_images" value="">
												</div>
												<div class="add_file color-theme">
													<span class="dotted pointer font_12"><?=GetMessage('JS_FILE_ADD');?></span>
												</div>	
											</div>
										</div>
									</div>

									<? if ($arResult["COMMENT_PROPERTIES"]["SHOW"] == "Y"): ?>
										<br />
										<?
										$eventHandlerID = false;
										$eventHandlerID = AddEventHandler('main', 'system.field.edit.file', array('CBlogTools', 'blogUFfileEdit'));
										?>
										<? foreach ($arResult["COMMENT_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField): ?>
											<? if($FIELD_NAME=='UF_BLOG_COMMENT_DOC'): ?>
												<a id="blog-upload-file" href="javascript:blogShowFile()"><?=GetMessage("BLOG_ADD_FILES")?></a>
											<? endif; ?>

											<div id="blog-comment-user-fields-<?=$FIELD_NAME?>"><?=($FIELD_NAME=='UF_BLOG_COMMENT_DOC' ? "" : $arPostField["EDIT_FORM_LABEL"].":")?>
												<?$APPLICATION->IncludeComponent(
													"bitrix:system.field.edit",
													$arPostField["USER_TYPE"]["USER_TYPE_ID"],
													array("arUserField" => $arPostField), 
													null, 
													array("HIDE_ICONS"=>"Y")
												);?>
											</div>
										<? endforeach; ?>
										<?
										if ($eventHandlerID !== false && ( intval($eventHandlerID) > 0 ))
											RemoveEventHandler('main', 'system.field.edit.file', $eventHandlerID);
										?>
									<? endif; ?>
							
									<? if (strlen($arResult["NoCommentReason"]) > 0): ?>
										<div id="nocommentreason" style="display:none;"><?=$arResult["NoCommentReason"]?></div>
									<? endif; ?>

									<? if($arResult["use_captcha"] === true): ?>
										<div class="row captcha-row form">
											<div class="col-md-6 col-sm-6 col-xs-6">
												<div class="form-group">
													<label for="captcha_word"><?=GetMessage("B_B_MS_CAPTCHA_SYM")?> <span class="required-star">*</span></label>
													<div class="input">
														<input type="text" size="30" name="captcha_word" class="form-control" id="captcha_word" value=""  tabindex="7">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-6">
												<div class="form-group">
													<div class="captcha-img">
														<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CaptchaCode"]?>" class="captcha_img" width="180" height="40" id="captcha" border="0" />
														<input type="hidden" name="captcha_code" id="captcha_code" value="<?=$arResult["CaptchaCode"]?>" />
														<span class="refresh captcha_reload"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
													</div>
												</div>
											</div>
										</div>
									<? endif; ?>

									<div class="blog-comment-buttons-wrapper font_15">
										<input tabindex="10" class="btn btn-default" value="<?=GetMessage("B_B_MS_SEND")?>" type="button" name="sub-post" id="post-button" onclick="submitComment()">
									</div>
								</div>
							</div>
							<input type="hidden" name="blog_upload_cid" id="upload-cid" value="">
						</form>
					</div>
				</div>
			</div>
		<? endif; ?>
		<?
		$prevTab = 0;
		function ShowComment($comment, $tabCount=0, $tabSize=2.5, $canModerate=false, $User=Array(), $use_captcha=false, $bCanUserComment=false, $errorComment=false, $arParams = array()) {
			if (!isset($application) && !isset($request)) {
				$application = \Bitrix\Main\Application::getInstance();
				$request = $application->getContext()->getRequest();
			}

			$comment["urlToAuthor"] = "";
			$comment["urlToBlog"] = "";
			$comment["urlToApprove"] = "";

			if ($canModerate && !$comment['PARENT_ID']) {
				$approveParam = isset($comment['UF_ASPRO_COM_APPROVE']) && $comment['UF_ASPRO_COM_APPROVE'] ? "unapprove_comment_id" : "approve_comment_id";
				$comment["urlToApprove"] = htmlspecialcharsbx($GLOBALS['APPLICATION']->GetCurPageParam($approveParam."=" . $comment["ID"], ["sessid", "delete_comment_id", "hide_comment_id", "success", "show_comment_id", "commentId", "approve_comment_id", "unapprove_comment_id"]));
			}

			if ($comment["SHOW_AS_HIDDEN"] == "Y" || $comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview") {
				global $prevTab;
				$tabCount = IntVal($tabCount);
				$startVal = $comment['PARENT_ID'] ? 25 : 0;
				if ($tabCount <= 5)
					$paddingSize = 26 * $tabCount;
				elseif ($tabCount > 5 && $tabCount <= 10)
					$paddingSize = 26 * 5 + ($tabCount - 5) * 1.5;
				elseif ($tabCount > 10)
					$paddingSize = 26 * 5 + 1.5 * 5 + ($tabCount-10) * 1;

				if (($tabCount+1) <= 5)
					$paddingSizeNew = 26 * ($tabCount+1);
				elseif (($tabCount+1) > 5 && ($tabCount+1) <= 10)
					$paddingSizeNew = 26 * 5 + (($tabCount+1) - 5) * 1.5;
				elseif (($tabCount+1) > 10)
					$paddingSizeNew = 26 * 5 + 1.5 * 5 + (($tabCount+1)-10) * 1;
				$paddingSizeNew -= $paddingSize;

				if ($prevTab > $tabCount)
					$prevTab = $tabCount;
				if ($prevTab <= 5)
					$prevPaddingSize = 26 * $prevTab;
				elseif ($prevTab > 5 && $prevTab <= 10)
					$prevPaddingSize = 26 * 5 + ($prevTab - 5) * 1.5;
				elseif ($prevTab > 10)
					$prevPaddingSize = 26 * 5 + 1.5 * 5 + ($prevTab-10) * 1;

					$prevTab = $tabCount;

				$bCommentChild = $tabCount > 0 || $comment['PARENT_ID'];
				?>
				<div class="blog-comment <?= $bCommentChild ? 'blog-comment--child' : 'parent bordered outer-rounded-x'?>"
					<? if ($bCommentChild): ?>
						style="--blog_comment_padding: <?= $tabCount ? $tabCount-1 : 1; ?>"
					<? endif; ?>
				>
				<a name="<?=$comment["ID"]?>"></a>
				<div id="blg-comment-<?=$comment["ID"]?>" class="blog-comment__content">
				<? if ($bCommentChild): ?>
					<div class="blog-comment__icon-answer">
						<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/catalog/item_icons.svg#comment_answer-12-12', 'stroke-dark-light', [
							'WIDTH' => 12,
							'HEIGHT' => 12,
						]);?>
					</div>
				<? endif; ?>
				<? if (isset($_SESSION['NOT_ADDED_FILES']) && $_SESSION['NOT_ADDED_FILES']['FILES'] && $_SESSION['NOT_ADDED_FILES']['ID'] == $comment["ID"]): ?>
					<div class="alert alert-danger">
						<?
							print_r(GetMessage('NOT_ADDED_FILES').'<br />');
							foreach ($_SESSION['NOT_ADDED_FILES']['FILES'] as $fileName) {
								echo $fileName.'<br />';
							}
							unset($_SESSION['NOT_ADDED_FILES']);
						?>
					</div>
				<? endif; ?>

				<? if ($comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview"): ?>
					<?
					$extraStyle = "";
					if ($arParams["is_ajax_post"] == "Y" || $comment["NEW"] == "Y")
						$extraStyle .= " blog-comment-new";
					if ($comment["AuthorIsAdmin"] == "Y")
						$extraStyle = " blog-comment-admin";
					if (IntVal($comment["AUTHOR_ID"]) > 0)
						$extraStyle .= " blog-comment-user-".IntVal($comment["AUTHOR_ID"]);
					if ($comment["AuthorIsPostAuthor"] == "Y")
						$extraStyle .= " blog-comment-author";
					if ($comment["PUBLISH_STATUS"] != BLOG_PUBLISH_STATUS_PUBLISH && $comment["ID"] != "preview")
						$extraStyle .= " blog-comment-hidden";
					if ($comment["ID"] == "preview")
						$extraStyle .= " blog-comment-preview";
					?>
					<div class="blog-comment-cont table-full-width colored_theme_bg_before<?= $extraStyle; ?>">
						<div class="blog-comment-cont-white">
							<div class="blog-comment-info">
								<?
								if($tabCount > 0 || $comment['PARENT_ID'])
									print_r(TSolution::showIconSvg("arrow_answer", SITE_TEMPLATE_PATH."/images/svg/arrow_answer.svg"));
								?>

								<div class="left_info">
									<?
									if (COption::GetOptionString("blog", "allow_alias", "Y") == "Y" && (strlen($comment["urlToBlog"]) > 0 || strlen($comment["urlToAuthor"]) > 0) && array_key_exists("ALIAS", $comment["BlogUser"]) && strlen($comment["BlogUser"]["ALIAS"]) > 0)
										$arTmpUser = array(
											"NAME" => "",
											"LAST_NAME" => "",
											"SECOND_NAME" => "",
											"LOGIN" => "",
											"NAME_LIST_FORMATTED" => $comment["BlogUser"]["~ALIAS"],
										);
									elseif (strlen($comment["urlToBlog"]) > 0 || strlen($comment["urlToAuthor"]) > 0)
										$arTmpUser = array(
											"NAME" => $comment["arUser"]["~NAME"],
											"LAST_NAME" => $comment["arUser"]["~LAST_NAME"],
											"SECOND_NAME" => $comment["arUser"]["~SECOND_NAME"],
											"LOGIN" => $comment["arUser"]["~LOGIN"],
											"NAME_LIST_FORMATTED" => "",
										);
									?>
									<? if (strlen($comment["urlToBlog"])>0): ?>
										<div class="blog-comment__author color_222 font_16">
											<?$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:main.user.link",
												'',
												array(
													"ID" => $comment["arUser"]["ID"],
													"HTML_ID" => "blog_post_comment_".$comment["arUser"]["ID"],
													"NAME" => $arTmpUser["NAME"],
													"LAST_NAME" => $arTmpUser["LAST_NAME"],
													"SECOND_NAME" => $arTmpUser["SECOND_NAME"],
													"LOGIN" => $arTmpUser["LOGIN"],
													"NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
													"USE_THUMBNAIL_LIST" => "N",
													"PROFILE_URL" => $comment["urlToAuthor"],
													"PROFILE_URL_LIST" => $comment["urlToBlog"],
													"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
													"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
													"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
													"SHOW_YEAR" => $arParams["SHOW_YEAR"],
													"CACHE_TYPE" => $arParams["CACHE_TYPE"],
													"CACHE_TIME" => $arParams["CACHE_TIME"],
													"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
													"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
													"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
													"PATH_TO_SONET_USER_PROFILE" => ($arParams["USE_SOCNET"] == "Y" ? $comment["urlToAuthor"] : $arParams["~PATH_TO_SONET_USER_PROFILE"]),
													"INLINE" => "Y",
													"SEO_USER" => $arParams["SEO_USER"],
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
									<? elseif (strlen($comment["urlToAuthor"])>0): ?>
										<div class="blog-comment__author color_222 font_16">
											<? if ($arParams["SEO_USER"] == "Y"): ?>
											<noindex>
											<? endif; ?>

												<?$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:main.user.link",
													'',
													array(
														"ID" => $comment["arUser"]["ID"],
														"HTML_ID" => "blog_post_comment_".$comment["arUser"]["ID"],
														"NAME" => $arTmpUser["NAME"],
														"LAST_NAME" => $arTmpUser["LAST_NAME"],
														"SECOND_NAME" => $arTmpUser["SECOND_NAME"],
														"LOGIN" => $arTmpUser["LOGIN"],
														"NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
														"USE_THUMBNAIL_LIST" => "N",
														"PROFILE_URL" => $comment["urlToAuthor"],
														"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
														"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
														"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
														"SHOW_YEAR" => $arParams["SHOW_YEAR"],
														"CACHE_TYPE" => $arParams["CACHE_TYPE"],
														"CACHE_TIME" => $arParams["CACHE_TIME"],
														"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
														"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
														"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
														"PATH_TO_SONET_USER_PROFILE" => ($arParams["USE_SOCNET"] == "Y" ? $comment["urlToAuthor"] : $arParams["~PATH_TO_SONET_USER_PROFILE"]),
														"INLINE" => "Y",
														"SEO_USER" => $arParams["SEO_USER"],
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>

											<? if($arParams["SEO_USER"] == "Y"): ?>
											</noindex>
											<? endif; ?>
										</div>
									<? else: ?>
										<div class="blog-comment__author color_222 font_16"><?=$comment["AuthorName"]?></div>
									<? endif; ?>

									<? if(strlen($comment["urlToDelete"])>0 && strlen($comment["AuthorEmail"])>0): ?>
										(<a href="mailto:<?=$comment["AuthorEmail"]?>"><?=$comment["AuthorEmail"]?></a>)
									<? endif; ?>

									<div class="blog-comment__date color_999 font_14"><?=FormatDate('d F, H:i', MakeTimeStamp($comment["DateFormated"]))?></div>
								</div>

								<div class="blog-info__rating">
									<div class="votes_block nstar big with-text">
										<div class="ratings">
											<div class="inner_rating">
												<? for ($i=1; $i<=5; $i++): ?>
													<div class="item-rating rating__star-svg<?= $i <= $comment['UF_ASPRO_COM_RATING'] ? ' rating__star-svg--filled' : ''; ?>">
														<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . "/images/svg/catalog/item_icons.svg#star-13-13", '', [
															'WIDTH' => 16,
															'HEIGHT' => 16,
														]); ?>
													</div>
												<? endfor; ?>
											</div>
										</div>
									</div>

									<? if (isset($comment['UF_ASPRO_COM_APPROVE']) && $comment['UF_ASPRO_COM_APPROVE']): ?>
										<div class="blog-comment-approve-text font_13">
											<?= isset($arParams["REAL_CUSTOMER_TEXT"]) && strlen($arParams["REAL_CUSTOMER_TEXT"]) ? $arParams["REAL_CUSTOMER_TEXT"] : GetMessage('T_REAL_CUSTOMER_TEXT_DEFAULT'); ?>
										</div>
									<? endif; ?>
								</div>
							</div>
							<?/*<div class="blog-clear-float"></div>*/?>
							
							<div class="blog-comment-post">
								<? if(strlen($comment["TitleFormated"])>0): ?>
									<b><?=$comment["TitleFormated"]?></b><br />
								<? endif; ?>

								<? if (isset($comment["TEXT"]['TYPE']) && $comment["TEXT"]['TYPE'] == 'PARENT'): ?>
									<? if($comment["TEXT"]['VIRTUES']): ?>
										<div class="blog-comment-post__item comment-text__text VIRTUES font_16" data-label="<?= GetMessage('BPC_VIRTUES'); ?>">
											<?= $comment["TEXT"]['VIRTUES']; ?>
										</div>
									<?endif;?>

									<?if($comment["TEXT"]['LIMITATIONS']):?>
										<div class="blog-comment-post__item comment-text__text LIMITATIONS font_16" data-label="<?= GetMessage('BPC_LIMITATIONS'); ?>">
											<?= $comment["TEXT"]['LIMITATIONS']; ?>
										</div>
									<?endif;?>

									<?if($comment["TEXT"]['COMMENT']):?>
										<div class="<?= !$tabCount ? 'blog-comment-post__item ' : ''; ?>comment-text__text COMMENT font_16"
											<?= !$tabCount ? ' data-label="' . GetMessage('BPC_MESSAGE') . '"' : ''; ?>	
										>
											<?#= !$tabCount ? "<span class='comment-text__title color_222'>".GetMessage('BPC_MESSAGE').':</span> ' : ''; ?><?= $comment["TEXT"]['COMMENT']; ?>
										</div>
									<?endif;?>
								<? else: ?>
									<? if($comment["~POST_TEXT"]): ?>
										<?
										$pattern = '/<comment>(.*?)<\/comment>/s';
										preg_match($pattern, $comment["~POST_TEXT"], $matches);
										$commentText = $matches[1];
										?>
										<div class="blog-comment-post__item comment-text__text COMMENT">
											<?= $commentText; ?>
										</div>
									<? endif; ?>
								<? endif; ?>

								<? if($comment['IMAGES']): ?>
									<?
									$commentSliderConfig = [
										'allowSlideNext' => false,
										'allowSlidePrev' => false,
										'allowTouchMove' => false,
										'init' => false,
										'slidesPerView' => 4,
										'spaceBetween' => 10,
										'type' => 'comment_block_slider_main',
										'breakpoints' => [
											601 => [
												'slidesPerView' => 6,
											],
											768 => [
												'slidesPerView' => 8,
											],
											1200 =>  [
												'slidesPerView' => 12,
											],
										]
									];
									?>
									<div class="blog-comment-content__item comment-image__wrapper">
										<div class="reviews-gallery-block" >
											<div class="comment-text__title color_222 font_14 font_large font_weight--500"><?= GetMessage('BLOG_PHOTO'); ?></div>
											<?
											$arImages = array_map(function($array){
												return [
													'src' => CFile::GetPath($array['FILE_ID']),
													'preview' => CFile::ResizeImageGet($array['FILE_ID'], ["width" => 80, "height" => 80], BX_RESIZE_IMAGE_EXACT)['src'],
													'alt' => '',
													'title' => '',
												];
											}, $comment['IMAGES']);
											?>
											<?= TSolution\Functions::showGallery($arImages, [
												'BREAKPOINTS' => [
													'xs' => 3,
													'xsm' => 4,
													'sm' => 5,
													'xmd' => 6,
													'md' => 7,
													'lg' => 8,
													'xl' => 10,
												],
												'CONTAINER_CLASS' => 'gallery-review',
											]); ?>
										</div>
									</div>
								<? endif; ?>

								<? if(!empty($arParams["arImages"][$comment["ID"]])): ?>
									<div class="feed-com-files">
										<div class="feed-com-files-title"><?=GetMessage("BLOG_PHOTO")?></div>
										<div class="feed-com-files-cont">
											<? foreach ($arParams["arImages"][$comment["ID"]] as $val): ?>
												<span class="feed-com-files-photo"><img src="<?=$val["small"]?>" alt="" data-bx-image="<?= $val["full"]; ?>"></span>
											<? endforeach; ?>
										</div>
									</div>
								<? endif; ?>
								
								<? if($comment["COMMENT_PROPERTIES"]["SHOW"] == "Y"): ?>
									<div>
									<?
										$eventHandlerID = AddEventHandler('main', 'system.field.view.file', Array('CBlogTools', 'blogUFfileShow'));
										
										foreach ($comment["COMMENT_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField) {
											if(!empty($arPostField["VALUE"])) {
												$GLOBALS["APPLICATION"]->IncludeComponent(
													"bitrix:system.field.view",
													$arPostField["USER_TYPE"]["USER_TYPE_ID"],
													array("arUserField" => $arPostField), 
													null, 
													array("HIDE_ICONS"=>"Y")
												);
											}
										}
									?>
									</div>
									<?
										if ($eventHandlerID !== false && ( intval($eventHandlerID) > 0 ))
											RemoveEventHandler('main', 'system.field.view.file', $eventHandlerID);
									?>
								<? endif; ?>

								<div class="blog-comment-post__item blog-comment-meta">
									<? // like buttons ?>
									<?
									if ($arParams["SHOW_RATING"] == "Y")
										include('like.php');
									?>
									
									<? // answer button ?>
									<? if ($bCanUserComment === true): ?>
										<span class="blog-comment-answer blog-comment-action color_222">
											<a href="javascript:void(0)" 
												class="blog-comment-action__link dotted dark_link font_14" 
												onclick="commentAction('<?= $comment['ID']; ?>', this, 'showComment');"
												data-type='showComment'
											><?= GetMessage("B_B_MS_REPLY"); ?></a>
										</span>
									<? endif; ?>
									
									<?/*<span class="blog-comment-link"><a href="#<?=$comment["ID"]?>"><?=GetMessage("B_B_MS_LINK")?></a></span>*/?>
									
									<? // edit comment button ?>
									<? if ($comment["CAN_EDIT"] == "Y"): ?>
										<script>
											top.text<?=$comment["ID"]?> = text<?=$comment["ID"]?> = '<?=CUtil::JSEscape($comment["~POST_TEXT"])?>';
											top.title<?=$comment["ID"]?> = title<?=$comment["ID"]?> = '<?=CUtil::JSEscape($comment["TITLE"])?>';
										</script>
										<span class="blog-comment-edit blog-comment-action color_222">
											<a href="javascript:void(0)" 
												class="blog-comment-action__link dotted dark_link font_14" 
												onclick="commentAction('<?= $comment['ID']; ?>', this, 'editComment');"
												data-type='editComment'
											><?= GetMessage("BPC_MES_EDIT"); ?></a>
										</span>
									<? endif; ?>
									
									<? // hide comment button ?>
									<? if (strlen($comment["urlToShow"])>0): ?>
										<span class="blog-comment-show blog-comment-action color_222">
											<a class="blog-comment-action__link dotted dark_link font_14"
											title="<?= GetMessage('BPC_MES_SHOW'); ?>"
												<? if ($arParams["AJAX_POST"] == "Y"): ?>
													href="javascript:void(0)" 
													onclick="return hideShowComment('<?= $comment['urlToShow'] . '&' . bitrix_sessid_get(); ?>', '<?= $comment['ID']; ?>');" 
												<? else: ?>
													href="<?= $comment["urlToShow"] . "&" . bitrix_sessid_get(); ?>" 
												<? endif; ?>
											><?= GetMessage("BPC_MES_SHOW"); ?></a>
										</span>
									<? endif; ?>
										
									<? // show comment button ?>
									<? if (strlen($comment["urlToHide"])>0): ?>
										<? $targetURL = $comment['urlToHide'].'&'.bitrix_sessid_get().'&IBLOCK_ID='.$request['IBLOCK_ID'].'&ELEMENT_ID='.$request['ELEMENT_ID']; ?>
										<span class="blog-comment-show blog-comment-action color_222">
											<a class="blog-comment-action__link dotted dark_link font_14" 
											title="<?= GetMessage('BPC_MES_HIDE'); ?>"
												<? if($arParams["AJAX_POST"] == "Y"): ?>
													href="javascript:void(0)" 
													onclick="return hideShowComment('<?= $targetURL; ?>', '<?= $comment['ID']; ?>');"
												<? else: ?>
													href="<?= $targetURL; ?>"
												<? endif; ?>
											><?= GetMessage("BPC_MES_HIDE"); ?></a>
										</span>
									<? endif; ?>

									<? // approve comment button ?>
									<? if (strlen($comment["urlToApprove"])>0): ?>
										<?
											$bpcMessage = $comment['UF_ASPRO_COM_APPROVE'] ? "BPC_MES_UNAPPROVE" : "BPC_MES_APPROVE";
											$targetURL = $comment['urlToApprove'].'&'.bitrix_sessid_get().'&IBLOCK_ID='.$request['IBLOCK_ID'].'&ELEMENT_ID='.$request['ELEMENT_ID'];
										?>
										<span class="blog-comment-approve blog-comment-action color_222">
											<a 
												class="blog-comment-action__link dotted dark_link font_14" title="<?= GetMessage($bpcMessage); ?>" 
												<? if($arParams["AJAX_POST"] == "Y"): ?>
													href="javascript:void(0)"
													onclick="return hideShowComment('<?= $targetURL; ?>', '<?= $comment['ID']; ?>');" 
												<? else: ?>
													href="<?= $targetURL; ?>" 
												<? endif; ?>
											><?= GetMessage($bpcMessage); ?></a>
										</span>
									<? endif; ?>

									<? // delete comment button ?>
									<? if (strlen($comment["urlToDelete"])>0): ?>
										<span class="blog-comment-delete blog-comment-action color_222">
											<?if($arParams["AJAX_POST"] == "Y"):?>
												<a href="javascript:void(0)" class="blog-comment-action__link dotted dark_link font_14" onclick="if(confirm('<?=GetMessage('BPC_MES_DELETE_POST_CONFIRM')?>')) deleteComment('<?=$comment['urlToDelete'].'&'.bitrix_sessid_get()?>&IBLOCK_ID=<?=$request['IBLOCK_ID']?>&ELEMENT_ID=<?=$request['ELEMENT_ID']?>', '<?=$comment['ID']?>');" title="<?=GetMessage("BPC_MES_DELETE")?>">
											<?else:?>
												<a href="javascript:if(confirm('<?=GetMessage("BPC_MES_DELETE_POST_CONFIRM")?>')) window.location='<?=$comment["urlToDelete"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$request["IBLOCK_ID"]?>&ELEMENT_ID=<?=$request["ELEMENT_ID"]?>'" class="blog-comment-action__link dotted dark_link font_14" title="<?=GetMessage("BPC_MES_DELETE")?>">
											<?endif;?>
											<?=GetMessage("BPC_MES_DELETE")?></a></span>
									<? endif; ?>
									
									<? // mark comment as spam button ?>
									<? if (strlen($comment["urlToSpam"])>0): ?>
										<span class="blog-comment-delete blog-comment-action blog-comment-spam color_222">
											<a href="<?=$comment["urlToSpam"]?>" 
												class="blog-comment-action__link dotted dark_link font_14" 
												title="<?=GetMessage("BPC_MES_SPAM_TITLE")?>"><?=GetMessage("BPC_MES_SPAM")?></a>
										</span>
									<? endif; ?>
								</div>
							</div>
						</div>
					</div>
					<?
					if (
						strlen($errorComment) <= 0 
						&& (strlen($post["preview"]) > 0 && $post["show_preview"] != "N") 
						&& (IntVal($post["parentId"]) > 0 || IntVal($post["edit_id"]) > 0)
						&& (
							(IntVal($post["parentId"]) == $comment["ID"] && IntVal($post["edit_id"]) <= 0) 
							|| (IntVal($post["edit_id"]) > 0 && IntVal($post["edit_id"]) == $comment["ID"] && $comment["CAN_EDIT"] == "Y")
						)
					) {
						$level = 0;
						$commentPreview = array(
							"ID" => "preview",
							"TitleFormated" => htmlspecialcharsEx($post["subject"]),
							"TextFormated" => $post["commentFormated"],
							"AuthorName" => $User["NAME"],
							"DATE_CREATE" => GetMessage("B_B_MS_PREVIEW_TITLE"),
						);
						ShowComment($commentPreview, (IntVal($post["edit_id"]) == $comment["ID"] && $comment["CAN_EDIT"] == "Y") ? $level : ($level+1), 2.5, false, Array(), false, false, false, $arParams);
					}

					if (
						strlen($errorComment)>0 && $bCanUserComment === true
						&& (IntVal($post["parentId"])==$comment["ID"] || IntVal($post["edit_id"]) == $comment["ID"])
					) {
						?>
						<div class="alert alert-danger blog-note-box blog-note-error">
							<div class="blog-error-text">
								<?=$errorComment?>
							</div>
						</div>
						<?
					}
					?>
					</div>

					<div id="err_comment_<?=$comment['ID']?>"></div>
					<div id="form_comment_<?=$comment['ID']?>" class="js-form-comment blog-comment__form-container" style="display: none"></div>
					<div id="new_comment_cont_<?=$comment['ID']?>"></div>
					<div id="new_comment_<?=$comment['ID']?>" style="display:none;"></div>
					<? if (
						(strlen($errorComment) > 0 || strlen($post["preview"]) > 0)
						&& (IntVal($post["parentId"]) == $comment["ID"] || IntVal($post["edit_id"]) == $comment["ID"])
						&& $bCanUserComment === true
					):?>
						<script>
							top.text<?=$comment["ID"]?> = text<?=$comment["ID"]?> = '<?=CUtil::JSEscape($post["comment"])?>';
							top.title<?=$comment["ID"]?> = title<?=$comment["ID"]?> = '<?=CUtil::JSEscape($post["subject"])?>';
							<? if(IntVal($post["edit_id"]) == $comment["ID"]): ?>
								editComment('<?=$comment["ID"]?>');
							<? else: ?>
								showComment('<?=$comment["ID"]?>', 'Y', '<?=CUtil::JSEscape($post["user_name"])?>', '<?=CUtil::JSEscape($post["user_email"])?>', 'Y');
							<? endif; ?>
						</script>
					<? endif; ?>

				<? elseif ($comment["SHOW_AS_HIDDEN"] == "Y"): ?>
					<b><?= GetMessage("BPC_HIDDEN_COMMENT"); ?></b>
				<? endif; ?>

				<? if($tabCount > 0): ?>
					</div>
				<? endif; ?>
			<?}
		}

		function RecursiveComments($sArray, $key, $level=0, $first=false, $canModerate=false, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams) {
			if (!empty($sArray[$key])) {
				foreach ($sArray[$key] as $comment) {
					if (!empty($arSumComments[$comment["ID"]])) {
						$comment["CAN_EDIT"] = $arSumComments[$comment["ID"]]["CAN_EDIT"];
						$comment["SHOW_AS_HIDDEN"] = $arSumComments[$comment["ID"]]["SHOW_AS_HIDDEN"];
						$comment["SHOW_SCREENNED"] = $arSumComments[$comment["ID"]]["SHOW_SCREENNED"];
						$comment["NEW"] = $arSumComments[$comment["ID"]]["NEW"];
					}
					ShowComment($comment, $level, 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);
					
					if (!empty($sArray[$comment["ID"]])) {
						foreach ($sArray[$comment["ID"]] as $key1) {
							if (!empty($arSumComments[$key1["ID"]])) {
								$key1["CAN_EDIT"] = $arSumComments[$key1["ID"]]["CAN_EDIT"];
								$key1["SHOW_AS_HIDDEN"] = $arSumComments[$key1["ID"]]["SHOW_AS_HIDDEN"];
								$key1["SHOW_SCREENNED"] = $arSumComments[$key1["ID"]]["SHOW_SCREENNED"];
								$key1["NEW"] = $arSumComments[$key1["ID"]]["NEW"];
							}
							
							ShowComment($key1, ($level+1), 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);

							if (!empty($sArray[$key1["ID"]])) {
								RecursiveComments($sArray, $key1["ID"], ($level+2), false, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams);
							}
						}
					}
					if ($first)
						$level=0;

					if ($level == 0): ?>
						</div>
					<?endif;
				}?>
				<?
			}
		}
		?>
		
		<? if (!$bAjaxPost): ?>
			<? if($arResult["CanUserComment"]): ?>
				<?
				$postTitle = "";
				if($arParams["NOT_USE_COMMENT_TITLE"] != "Y")
					$postTitle = "RE: ".CUtil::JSEscape($arResult["Post"]["TITLE"]);
				?>
				<?/*<div class="blog-add-comment"><a class="btn btn-lg btn-transparent-border-color white" href="javascript:void(0)"><?=GetMessage("B_B_MS_ADD_COMMENT")?></a></div>*/?>
				
				<? if (count($arResult['Comments'])): ?>
					<? include_once('sort.php'); ?>
				<? endif; ?>

				<? if (
					strlen($arResult["COMMENT_ERROR"]) > 0 
					&& strlen($post["parentId"]) < 2
					&& IntVal($post["parentId"])==0 
					&& IntVal($post["edit_id"]) <= 0
				):?>
					<div class="alert alert-danger blog-note-box blog-note-error">
						<div class="blog-error-text"><?=$arResult["COMMENT_ERROR"]?></div>
					</div>
				<? endif; ?>
			<? endif; ?>

			<? if ($arResult["CanUserComment"]): ?>
				<div class="js-form-comment" id="form_comment_0" style="display: none;">
					<div id="err_comment_0"></div>
					<div class="js-form-comment" id="form_comment_0"></div>
					<div id="new_comment_0" style="display:none;"></div>
				</div>

				<div id="new_comment_cont_0" class="hidden"></div>

				<? if (
					(strlen($arResult["COMMENT_ERROR"])>0 || strlen($post["preview"]) > 0)
					&& $arResult['COMMENT_ERROR_TYPE'] !== 'FILTER'
					&& IntVal($post["parentId"]) == 0 
					&& strlen($post["parentId"]) < 2 
					&& IntVal($post["edit_id"]) <= 0
				): ?>
					<script>
						top.text0 = text0 = '<?=CUtil::JSEscape($post["comment"])?>';
						top.title0 = title0 = '<?=CUtil::JSEscape($post["subject"])?>';
						showComment('0', 'Y', '<?=CUtil::JSEscape($post["user_name"])?>', '<?=CUtil::JSEscape($post["user_email"])?>', 'Y');
					</script>
				<? endif; ?>
			<? endif; ?>
		<? endif; ?>

		<?
		$arParams["RATING"] = $arResult["RATING"];
		$arParams["component"] = $component;
		$arParams["arImages"] = $arResult["arImages"];

		if($bAjaxPost)
			$arParams["is_ajax_post"] = "Y";
		?>
		
		<? if (!$bAjaxPost && $arResult["NEED_NAV"] == "Y"): ?>
			<div class="blog-comment__container">
				<? for ($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++):?>
					<?
					$tmp = $arResult["CommentsResult"];
					$tmp[0] = $arResult["PagesComment"][$i];
					?>
					<div id="blog-comment-page-<?=$i?>" class="<?= $arResult["PAGE"] != $i ? "hidden" : ''; ?>">
						<? RecursiveComments($tmp, $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);?>
					</div>
				<? endfor; ?>
			</div>
		<? else: ?>
			<? if (!$bAjaxPost): ?>
				<div class="blog-comment__container">
			<? endif;  ?>
				<? if (!$arResult["CommentsResult"][0] && !$arResult["ajax_comment"] && !strlen($arResult["COMMENT_ERROR"])): ?>
					<div class="rounded-x bordered alert-empty">
						<?= GetMessage('EMPTY_REVIEWS'); ?>
					</div>
					<script>
						var comments = $('.EXTENDED .blog-comments');
						if (comments.length) {
							comments.addClass('empty-reviews');
						}
					</script>
				<? endif; ?>
				
				<? RecursiveComments($arResult["CommentsResult"], $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams); ?>
			<? if (!$bAjaxPost): ?>
				</div>
			<? endif; ?>
		<? endif; ?>
		
		<? if (!$bAjaxPost && $arResult["NEED_NAV"] == "Y"): ?>
			<div class="bottom_nav">
				<div class="blog-comment-nav hidden">
					<? for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++): ?>
						<?
						$style = "blog-comment-nav-item";
						if ($i == $arResult["PAGE"])
							$style .= " blog-comment-nav-item-sel colored_theme_bg";
						?>
						<a class="<?= $style; ?>" 
							href="<?= $arResult["NEW_PAGES"][$i]; ?>" 
							onclick="return bcNav('<?=$i?>', this)" 
							id="blog-comment-nav-b<?= $i; ?>"
						><?= $i; ?></a>
					<? endfor;?>
				</div>

				<div class="more_text_ajax btn btn-transparent blog-comment__load_more">
					<?= GetMessage('PAGER_SHOW_MORE'); ?>
				</div>
			</div>
		<? endif; ?>
	<? endif; ?>
<? endif; ?>
</div>
<?
if($bAjaxPost)
	die();

function makeInputsFromParams($arParams, $name="PARAMS") {
	$result = "";

	if (is_array($arParams)) {
		foreach ($arParams as $key => $value) {
			if(substr($key, 0, 1) != "~") {
				$inputName = $name.'['.$key.']';

				if(is_array($value))
					$result .= makeInputsFromParams($value, $inputName);
				else
					$result .= '<input type="hidden" name="'.$inputName.'" value="'.$value.'">'.PHP_EOL;
			}
		}
	}

	return $result;
}
?>
</div>