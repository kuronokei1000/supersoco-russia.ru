<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Личный кабинет Super Soco");
$APPLICATION->SetTitle("Личный кабинет");
?>
<?$APPLICATION->IncludeComponent(
	"aspro:personal.section.lite", 
	".default", 
	array(
		"ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
			0 => "0",
		),
		"ACCOUNT_PAYMENT_PERSON_TYPE" => "1",
		"ACCOUNT_PAYMENT_SELL_CURRENCY" => "RUB",
		"ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
		"ACCOUNT_PAYMENT_SELL_TOTAL" => "[{\"value\":\"100\",\"active\":1},{\"value\":\"200\",\"active\":1},{\"value\":\"500\",\"active\":1},{\"value\":\"1000\",\"active\":1},{\"value\":\"5000\",\"active\":1}]",
		"ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ALLOW_INNER" => "N",
		"BANNERS_HIDDEN_SM" => "N",
		"BANNERS_HIDDEN_XS" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_RIGHTS_PRIVATE" => "N",
		"COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CUSTOM_MAIN_BLOCKS" => "[]",
		"CUSTOM_MAIN_LINKS" => "[]",
		"CUSTOM_PAGES" => "[]",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"DELIVERY_INFO_PROP_1" => "",
		"DELIVERY_INFO_PROP_2" => "",
		"DATE_FORMAT" => "j F Y",
		"MAIN_LINKS_ORDER" => "favorites,orders,subscribes,profiles,help",
		"MAIN_BLOCKS_ORDER" => "banners,private,account,links,orders,votes,recoms",
		"NAV_TEMPLATE" => "main",
		"ONLY_INNER_FULL" => "N",
		"ORDERS_PER_MAIN_PAGE" => "10",
		"ORDERS_PER_PAGE" => "20",
		"ORDER_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"ORDER_HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
		),
		"ORDER_DEFAULT_SORT" => "DATE_INSERT",
		"ORDER_REFRESH_PRICES" => "N",
		"ORDER_DISALLOW_CANCEL" => "N",
		"ORDER_HIDE_STATUSES" => array(
		),
		"ORDER_CHANGE_STATUS_COLOR" => "",
		"ORDER_CANCEL_REASONS_REQUIRED" => "N",
		"ORDER_CANCEL_REASONS" => "[{\"name\":\"Слишком долго ждать\",\"active\":1},{\"name\":\"Заказал по ошибке\",\"active\":1},{\"name\":\"Нет в наличии\",\"active\":1},{\"name\":\"Заказал в другом магазине\",\"active\":1},{\"name\":\"Заказ-дубликат\",\"active\":1}]",
		"ORDER_CANCEL_REASON_REQUIRED" => "N",
		"PATH_TO_HELP" => "/faq/",
		"PER_PAGE" => "20",
		"PROP_1" => array(
		),
		"PROP_2" => array(
		),
		"PROP_1_PROFILE_LIST" => array(
			0 => "1",
			1 => "2",
			2 => "3",
		),
		"PROP_2_PROFILE_LIST" => array(
			0 => "8",
			1 => "10",
			2 => "11",
			3 => "13",
			4 => "14",
		),
		"SEF_FOLDER" => "/personal/",
		"SEF_MODE" => "Y",
		"SEND_INFO_PRIVATE" => "N",
		"SET_TITLE" => "Y",
		"SHOW_ACCOUNT_COMPONENT" => "Y",
		"SHOW_ACCOUNT_PAGE" => "Y",
		"SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
		"SHOW_FAVORITE_PAGE" => "Y",
		"SHOW_ORDER_PAGE" => "Y",
		"SHOW_PAGE_TOP_BANNER" => "N",
		"SHOW_PRIVATE_PAGE" => "Y",
		"SHOW_PROFILE_PAGE" => "N",
		"SHOW_SUBSCRIBE_PAGE" => "Y",
		"RCM_ELEMENTS_COUNT" => "10",
		"RCM_TYPE" => "any_personal",
		"USE_AJAX_LOCATIONS_PROFILE" => "N",
		"USER_PROPERTY_PRIVATE" => "",
		"VOTE_BLOG_URL" => "catalog_comments",
		"VOTE_EMAIL_NOTIFY" => "N",
		"VOTE_ORDER_STATUSES" => array(
			0 => "F",
		),
		"VOTE_PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
		"VOTE_PRODUCTS_PER_MAIN_PAGE" => "10",
		"VOTE_RATING_TYPE" => "like_graphic_catalog_reviews",
		"SEF_URL_TEMPLATES" => array(
			"private" => "private/",
			"orders" => "orders/",
			"order_detail" => "orders/#ID#",
			"order_cancel" => "orders/cancel/#ID#",
			"payment" => "payment/",
			"subscribe" => "subscribe/",
			"unsubscribe" => "unsubscribe.php",
			"favorite" => "favorite/",
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>