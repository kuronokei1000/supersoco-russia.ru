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
														<img align="center" data-bx-editor-def-image="0" src="/bitrix/images/aspro.lite/preset/cart.jpg" class="bxImage">
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
													<p><?=Loc::getMessage('MAIL_TEXT_TOP')?></p>
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
									'%BASKET_TITLE%',
									'%BASKET_NOTE%',
									'%SHOW_BUTTON%',
									'%BUTTON_TITLE%',
								],
								[
									Loc::getMessage('MAIL_BASKET_TITLE'),
									'',
									'N',
									Loc::getMessage('MAIL_BASKET_BUTTON_TITLE'),
								],
								File::getFileContents(__DIR__.'/blocks/cart.php')
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
													<p><?=Loc::getMessage('MAIL_TEXT_BOTTOM')?></p>
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

						<!-- content button -->
						<div data-bx-block-editor-block-type="button">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockButton mail-block mail-body-button">
							<tbody class="bxBlockOut">
								<tr>
									<td valign="top" class="bxBlockPadding bxBlockInn bxBlockInnButton">
										<table align="center" border="0" cellpadding="0" cellspacing="0" class="bxBlockContentButtonEdge" bgcolor="<?=$baseColor?>" style="background-color: <?=$baseColor?>;border-radius: 7px;text-align: center;" width="100%">
										<tbody>
											<tr>
												<td valign="top">
													<a class="bxBlockContentButton" title="<?=htmlspecialcharsbx(Loc::getMessage('MAIL_BUTTON_TITLE'))?>" href="/basket/" target="_blank" style="background:inherit"><?=Loc::getMessage('MAIL_BUTTON_TITLE')?></a>
												</td>
											</tr>
										</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							</table>
						</div>
						<!-- /content button -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
</tbody>
</table>