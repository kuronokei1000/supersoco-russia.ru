<?
if ($request['params']) {
	// jqm
	$arParams = json_decode($request['params'], true) ?? [];
	$arParams = $GLOBALS['APPLICATION']->ConvertCharsetArray($arParams, 'UTF-8', SITE_CHARSET);

	$arComponentParams = [
		'ACCOUNT_NUMBER' => $arParams['ACCOUNT_NUMBER'],
		'PAYMENT_NUMBER' => $arParams['PAYMENT_NUMBER'],
		'PATH_TO_PAYMENT' => htmlspecialcharsbx(urldecode(strlen($arParams['PATH_TO_PAYMENT']) ? $arParams['PATH_TO_PAYMENT'] : '')),
		'REFRESH_PRICES' => ($arParams['REFRESH_PRICES'] === 'Y') ? 'Y' : 'N',
		'RETURN_URL' => urldecode($arParams['RETURN_URL'] ?? ''),
	];
	
	if (CBXFeatures::IsFeatureEnabled('SaleAccounts')) {
		$arComponentParams['ALLOW_INNER'] = $arParams['ALLOW_INNER'];
		$arComponentParams['ONLY_INNER_FULL'] = $arParams['ONLY_INNER_FULL'];
	}
	else {
		$arComponentParams['ALLOW_INNER'] = 'N';
		$arComponentParams['ONLY_INNER_FULL'] = 'Y';
	}
}
else {
	// form submit
	$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);
	$arParams = $request->getPostList()->toArray();
	$templateName = $request->get('templateName');
	if (!strlen($templateName)) {
		$templateName = '';
	}

	$arComponentParams['AJAX_DISPLAY'] = "Y";
	$arComponentParams['ACCOUNT_NUMBER'] = $arParams['accountNumber'];
	$arComponentParams['PAYMENT_NUMBER'] = $arParams['paymentNumber'];
	$arComponentParams['NEW_PAY_SYSTEM_ID'] = $arParams['paySystemId'];
	$arComponentParams['PATH_TO_PAYMENT'] = $arParams['pathToPayment'] <> '' ? htmlspecialcharsbx($arParams['pathToPayment']) : "";
	$arComponentParams['REFRESH_PRICES'] = ($arParams['refreshPrices'] === 'Y') ? 'Y' : 'N';
	$arComponentParams['RETURN_URL'] = $arParams['returnUrl'] ?? '';
	if ((float)$arParams['paymentSum'] > 0) {
		$arComponentParams['INNER_PAYMENT_SUM'] = (float)$arParams['paymentSum'];
	}

	if (CBXFeatures::IsFeatureEnabled('SaleAccounts')) {
		$arComponentParams['ALLOW_INNER'] = $arParams['inner'];
		$arComponentParams['ONLY_INNER_FULL'] = $arParams['onlyInnerFull'];
	}
	else {
		$arComponentParams['ALLOW_INNER'] = 'N';
		$arComponentParams['ONLY_INNER_FULL'] = 'Y';
	}

	$GLOBALS['APPLICATION']->RestartBuffer();
}

$component = false;
if (
	strlen($arParams['PARENT_COMPONENT'] ?? '') &&
	strlen($arParams['PARENT_COMPONENT_TEMPLATE'] ?? '') &&
	strlen($arParams['PARENT_COMPONENT_PAGE'] ?? '')
) {
	$component = new \CBitrixComponent();
	if ($component->InitComponent($arParams['PARENT_COMPONENT'])) {
		$component->setTemplateName($arParams['PARENT_COMPONENT_TEMPLATE']);
		$component->initComponentTemplate($arParams['PARENT_COMPONENT_PAGE'], SITE_ID);
	}
}
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment.change",
	"main",
	$arComponentParams,
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>