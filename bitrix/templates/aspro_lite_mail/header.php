<?require ($_SERVER['DOCUMENT_ROOT'].Bitrix\Main\Mail\EventMessageThemeCompiler::getInstance()->themePath.'/vars.php');?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$arSite['CHARSET']?>" />
		<style type="text/css">
			a{
				color: <?=$baseColor?>;
			}
			.mail-wrap{
				border-radius: <?=$outerBorderRadius?>;
			}
			a.link_img_logo{
				background: <?=$logoBgColor?>;
			}
			.bxBlockContentButton{
				background-color: <?=$baseColor?>;
			}
			.block-button{
				background-color: <?=$baseColor?>;
			}
			.coupon-block{
				background-color: <?=Aspro\Lite\Sender\Preset\Template::hex2rgb($baseColor, 0.1)?>;
			}
		</style>
	</head>
	<body style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100% !important;margin: 0;padding: 0;font-family: Arial, Helvetica;padding-right: 2px;min-width: 268px !important; background-color: #eeeeee !important; padding-top: 0px; padding-bottom: 0px;">
		<div class="mail-wrap" style="width: 100%;max-width: 600px;margin: 40px auto;overflow: hidden;background: #ffffff;border-radius: 0px;border: 0px solid #ededed;padding: 20px;">
			<center>
				<!-- top content -->
				<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
                <tbody>
					<tr>
						<td id="bxStylistHeader" style="font-family: Arial, Helvetica;background: none;font-size: 16px;line-height: 22px;padding-top: 20px;padding-bottom: 0px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
							<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100% !important;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
							<tbody>
                                <tr>
                                    <td class="mail-header" style="background-color: transparent;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
										<div>
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="header" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
														<?if ($logoSrc):?>
															<div data-bx-block-editor-block-type="image">
																<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockImage mail-block mail-logo" style="text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
																<tbody class="bxBlockOut">
																	<tr>
																		<td valign="top" class="bxBlockInn bxBlockInnImage" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
																			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
																			<tbody>
																				<tr>
																					<td valign="top" class="bxBlockPadding bxBlockContentImage" style="padding: 16px 32px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
																						<a align="center" href="<?=$siteAddressFull?>" class="link_img_logo" style="display: inline-block;zoom: 1;vertical-align: middle;margin: 0;background: none; max-width: 300px; text-decoration: none;color: #5b7fff;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;word-wrap: break-word;">
																							<img align="center" src="<?=$logoSrc?>" class="bxImage" style="display: block;max-height: 100%;max-width: 100%;height: auto;border: 0;margin: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;">
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
														<?endif;?>
													</td>
												</tr>
											</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
							</table>
						</td>
					</tr>
                </tbody>
				</table>
				<!-- /top content -->

				<!-- middle content -->
				<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
				<tbody>
					<tr>
						<td id="bxStylistBody" style="font-family: Arial, Helvetica;color: #222222;font-size: 16px;line-height: 22px;background: none;padding-top: 0px;padding-bottom: 0px;padding-right: 32px;padding-left: 32px;overflow: hidden;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
							<!-- content -->
