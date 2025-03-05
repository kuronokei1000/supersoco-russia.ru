<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\IO\File;

Loc::loadLanguageFile(__FILE__);
?>
<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>
	<tr>
		<td class="mail-body">
			<table class="mail-grid-cell" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tbody>
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

						<!-- content top line -->
						<div data-bx-block-editor-block-type="line">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockLine mail-block mail-line-top">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockInn bxBlockInnLine">
										<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td valign="top" class="bxBlockPadding">
													<span class="bxBlockContentLine" style="height: 1px; background-color: rgb(237, 237, 237);"></span>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content top line -->

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
									'N',
									Loc::getMessage('MAIL_REVIEW_BUTTON_TITLE'),
									'Y',
								],
								File::getFileContents(__DIR__.'/blocks/order.php')
							);?>
						</div>

						<!-- content text bottom -->
						<div data-bx-block-editor-block-type="text">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-body-bottom">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockInn bxBlockInnText">
										<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td valign="top" class="bxBlockPadding bxBlockContentText">
													<?=Loc::getMessage('MAIL_TEXT_BOTTOM')?>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content text bottom -->

						<!-- content bottom line -->
						<div data-bx-block-editor-block-type="line">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockLine mail-block mail-line-bottom">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockInn bxBlockInnLine">
										<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td valign="top" class="bxBlockPadding">
													<span class="bxBlockContentLine" style="height: 1px; background-color: rgb(237, 237, 237);"></span>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content bottom line -->
					</td>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
</tbody>
</table>