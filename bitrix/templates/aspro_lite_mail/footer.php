
							<?require ($_SERVER['DOCUMENT_ROOT'].Bitrix\Main\Mail\EventMessageThemeCompiler::getInstance()->themePath.'/vars.php');?>

							<!-- content bottom line -->
							<div data-bx-block-editor-block-type="line">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockLine mail-block mail-line-top" style="text-align: left;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
								<tbody class="bxBlockOut">
									<tr>
										<td valign="top" class="bxBlockInn bxBlockInnLine" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
											<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: left;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
											<tbody>
												<tr>
													<td valign="top" class="bxBlockPadding" style="padding: 16px 32px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
														<span class="bxBlockContentLine" style="height: 1px;background-color: rgb(237, 237, 237);text-align: left;display: block;width: 100%;"></span>
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

						<!-- /content -->
						</td>
					</tr>
				</tbody>
				</table>
				<!-- /middle content -->

				<!-- bottom content -->
				<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
				<tbody>
					<tr>
						<td id="bxStylistFooter" style="font-family: Arial, Helvetica;background: none;font-size: 14px;line-height: 22px;padding-top: 0px;padding-bottom: 20px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
							<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100% !important;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
							<tbody>
                                <tr>
                                    <td class="mail-footer" style="background-color: transparent;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
										<div>
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="footer" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
														<div data-bx-block-editor-block-type="text">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-contacts" style="text-align: center;padding: 16px 32px 5px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
															<tbody class="bxBlockOut">
																<tr>
																	<td valign="top" class="bxBlockInn bxBlockInnText" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
																		<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
																		<tbody>
																			<tr>
																				<td valign="top" class="bxBlockPadding bxBlockContentText" style="font-family: Arial, Helvetica;color: #999999;font-weight: 400;font-size: 14px;line-height: 22px;margin: 0;padding: 16px 32px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
																					<p style="text-align: left;font-family: Arial, Helvetica;color: #999999;font-weight: 400;font-size: 14px;line-height: 22px;margin: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;"><?=GetMessage('T_CONTACTS_TEXT', array('#SITE_ADDRESS#' => $siteAddressFull))?></p>
																					<?if (
																						strlen($emailHtml) ||
																						strlen($phoneHtml)
																					):?>
<p style="text-align: left;font-family: Arial, Helvetica;color: #999999;font-weight: 400;font-size: 14px;line-height: 22px;margin: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
Электронная почта: <a href="mailto:<?=$emailHtml?>"><?=$emailHtml?></a><br>
Телефон: <a href="tel:+74997044208">+7 499 704-42-08</a>
</p>
																					<?endif;?>
																				</td>
																			</tr>
																		</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
															</table>
														</div>

														<?if (strlen($copyrightHtml)):?>
															<div data-bx-block-editor-block-type="text">
																<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-copyright" style="text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
																<tbody class="bxBlockOut">
																	<tr>
																		<td valign="top" class="bxBlockInn bxBlockInnText" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
																			<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;table-layout: fixed;">
																			<tbody>
																				<tr>
																					<td valign="top" class="bxBlockPadding bxBlockContentText" style="font-family: Arial, Helvetica;color: #999999;font-weight: 400;font-size: 14px;line-height: 22px;margin: 0;padding: 16px 32px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-line-height-rule: exactly;">
																						<div style="text-align: left;"><?=$copyrightHtml?></div>
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
				<!-- /bottom content -->
			</center>
		</div>
	</body>
</html>