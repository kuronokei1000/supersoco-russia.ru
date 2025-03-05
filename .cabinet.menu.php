<?
$aMenuLinks = Array(
	Array(
		"Мой кабинет", 
		"/personal/", 
		Array(), 
		Array(), 
		"TSolution::isPersonalSectionAvailable()" 
	),
	Array(
		"Личные данные", 
		"/personal/private/", 
		Array(), 
		Array(), 
		"TSolution::isPersonalSectionAvailable()" 
	),
	Array(
		"Личный счет", 
		"/personal/account/", 
		Array(), 
		Array(), 
		"TSolution::isPersonalSaleSectionAvailable() && CBXFeatures::IsFeatureEnabled('SaleAccounts')" 
	),
	Array(
		"Заказы", 
		"/personal/orders/", 
		Array(), 
		Array(), 
		"TSolution::isPersonalSaleSectionAvailable()" 
	),
	Array(
		"Подписки", 
		"/personal/subscribe/", 
		Array(), 
		Array(), 
		"Bitrix\\Main\\Loader::includeModule('subscribe') || Bitrix\\Main\\Loader::includeModule('catalog')" 
	),
	Array(
		"Избранные товары", 
		"/personal/favorite/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Выйти", 
		"?logout=yes&login=yes", 
		Array(), 
		Array("class"=>"exit", "SVG_ICON"=>"header_icons.svg#logout-11-9"), 
		"\$GLOBALS['USER']->IsAuthorized()" 
	)
);
?>