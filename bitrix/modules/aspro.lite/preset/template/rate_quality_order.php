<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\IO\File;

Loc::loadLanguageFile(__FILE__);
?>
<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>
	<tr>
		<td>
			<table class="mail-grid-cell" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td data-bx-block-editor-place="body">
						<!-- content title -->
						<div data-bx-block-editor-block-type="text">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-body-title">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockInn bxBlockInnText">
										<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td valign="top" class="bxBlockPadding bxBlockContentText">
													<p><?=Loc::getMessage('MAIL_GREETINGS')?></p>
													<br>
													<h2><?=Loc::getMessage('MAIL_TITLE')?></h2>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content title -->

						<!-- content image -->
						<div data-bx-block-editor-block-type="image">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockImage mail-block mail-body-image">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockInn bxBlockInnImage">
										<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td valign="top" class="bxBlockPadding bxBlockContentImage" style="text-align: center;">
													<a align="center" href="/">
														<img align="center" data-bx-editor-def-image="0" src="/bitrix/images/aspro.lite/preset/rate.jpg" class="bxImage">
													</a>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content image -->

						<!-- content text top -->
						<div data-bx-block-editor-block-type="text">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-body-top">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockInn bxBlockInnText">
										<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td valign="top" class="bxBlockPadding bxBlockContentText">
													<?=Loc::getMessage('MAIL_TEXT_TOP')?>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content text top -->

						<div data-bx-block-editor-block-type="component">
							<?=str_replace(
								[
									'%ORDER_TITLE%',
									'%ORDER_NOTE%',
									'%SHOW_REVIEW_BUTTON%',
									'%REVIEW_BUTTON_TITLE%',
									'%SHOW_ORDER_SUM%',
								],
								[
									Loc::getMessage('MAIL_ORDER_TITLE'),
									'',
									'Y',
									Loc::getMessage('MAIL_REVIEW_BUTTON_TITLE'),
									'N',
								],
								File::getFileContents(__DIR__.'/blocks/order.php')
							);?>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</tbody>
</table>