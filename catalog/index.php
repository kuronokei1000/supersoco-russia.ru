<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetPageProperty("", "");

$APPLICATION->SetTitle("Каталог");

$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"main", 
	array(
		"IBLOCK_TYPE" => "aspro_lite_catalog",
		"IBLOCK_ID" => "13",
		"HIDE_NOT_AVAILABLE" => "L",
		"BASKET_URL" => "/basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/catalog/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "MAX_SMART_FILTER",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "IN_STOCK",
			1 => "CML2_ARTICLE",
			2 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "BASE",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES",
			2 => "VOLUME",
			3 => "SIZES5",
			4 => "SIZES3",
			5 => "SIZES4",
			6 => "COLOR",
			7 => "CML2_LINK",
			8 => "",
		),
		"USE_REVIEW" => "Y",
		"MESSAGES_PER_PAGE" => "",
		"USE_CAPTCHA" => "Y",
		"REVIEW_AJAX_POST" => "Y",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"FORUM_ID" => "",
		"URL_TEMPLATES_READ" => "",
		"SHOW_LINK_TO_FORUM" => "Y",
		"POST_FIRST_MESSAGE" => "N",
		"USE_COMPARE" => "Y",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "NAME",
			1 => "TAGS",
			2 => "SORT",
			3 => "PREVIEW_PICTURE",
			4 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "HIT",
			1 => "BRAND",
			2 => "CML2_ARTICLE",
			3 => "PROP_2104",
			4 => "PODBORKI",
			5 => "PROP_2033",
			6 => "CML2_ATTRIBUTES",
			7 => "COLOR_REF2",
			8 => "PROP_2065",
			9 => "PROP_305",
			10 => "PROP_352",
			11 => "PROP_317",
			12 => "PROP_357",
			13 => "PROP_2102",
			14 => "PROP_318",
			15 => "PROP_159",
			16 => "PROP_349",
			17 => "PROP_327",
			18 => "PROP_2052",
			19 => "PROP_370",
			20 => "PROP_336",
			21 => "PROP_2115",
			22 => "PROP_346",
			23 => "PROP_2120",
			24 => "PROP_2053",
			25 => "PROP_363",
			26 => "PROP_320",
			27 => "PROP_2089",
			28 => "PROP_374",
			29 => "PROP_325",
			30 => "PROP_2103",
			31 => "PROP_2085",
			32 => "PROP_300",
			33 => "PROP_322",
			34 => "PROP_362",
			35 => "PROP_365",
			36 => "PROP_359",
			37 => "PROP_284",
			38 => "PROP_364",
			39 => "PROP_356",
			40 => "PROP_343",
			41 => "PROP_373",
			42 => "PROP_2083",
			43 => "PROP_314",
			44 => "PROP_348",
			45 => "PROP_316",
			46 => "PROP_350",
			47 => "PROP_333",
			48 => "PROP_372",
			49 => "PROP_332",
			50 => "PROP_360",
			51 => "PROP_353",
			52 => "PROP_347",
			53 => "PROP_25",
			54 => "PROP_2114",
			55 => "PROP_301",
			56 => "PROP_2101",
			57 => "PROP_2067",
			58 => "PROP_323",
			59 => "PROP_324",
			60 => "PROP_355",
			61 => "PROP_304",
			62 => "PROP_358",
			63 => "PROP_319",
			64 => "PROP_344",
			65 => "PROP_328",
			66 => "PROP_338",
			67 => "PROP_2113",
			68 => "PROP_371",
			69 => "PROP_366",
			70 => "PROP_302",
			71 => "PROP_303",
			72 => "PROP_2054",
			73 => "PROP_341",
			74 => "PROP_223",
			75 => "PROP_283",
			76 => "PROP_354",
			77 => "PROP_313",
			78 => "PROP_2066",
			79 => "PROP_329",
			80 => "PROP_342",
			81 => "PROP_367",
			82 => "PROP_2084",
			83 => "PROP_340",
			84 => "PROP_351",
			85 => "PROP_368",
			86 => "PROP_369",
			87 => "PROP_331",
			88 => "PROP_337",
			89 => "PROP_345",
			90 => "PROP_339",
			91 => "PROP_310",
			92 => "PROP_309",
			93 => "PROP_330",
			94 => "PROP_2017",
			95 => "PROP_335",
			96 => "PROP_321",
			97 => "PROP_308",
			98 => "PROP_206",
			99 => "PROP_334",
			100 => "PROP_2100",
			101 => "PROP_311",
			102 => "PROP_2132",
			103 => "SHUM",
			104 => "PROP_361",
			105 => "PROP_326",
			106 => "PROP_315",
			107 => "PROP_2091",
			108 => "PROP_2026",
			109 => "PROP_307",
			110 => "PROP_2027",
			111 => "PROP_2049",
			112 => "PROP_2044",
			113 => "PROP_162",
			114 => "CML2_MANUFACTURER",
			115 => "PROP_2055",
			116 => "PROP_2069",
			117 => "PROP_2062",
			118 => "PROP_2061",
			119 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "ARTICLE",
			1 => "COLOR_REF",
			2 => "SIZES",
			3 => "VOLUME",
			4 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => "",
		"USE_PRODUCT_QUANTITY" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"OFFERS_CART_PROPERTIES" => "",
		"SHOW_TOP_ELEMENTS" => "Y",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "1",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "UF_SECTION_DESCR",
		"SHOW_SECTION_LIST_PICTURES" => "Y",
		"PAGE_ELEMENT_COUNT" => "20",
		"LINE_ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "ID",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER2" => "asc",
		"LIST_PROPERTY_CODE" => array(
			0 => "HIT",
			1 => "BRAND",
			2 => "FORM_ORDER",
			3 => "PRICE_CURRENCY",
			4 => "PRICE",
			5 => "PRICEOLD",
			6 => "ECONOMY",
			7 => "STATUS",
			8 => "PROP_2104",
			9 => "PODBORKI",
			10 => "PROP_2033",
			11 => "COLOR_REF2",
			12 => "PROP_2065",
			13 => "PROP_305",
			14 => "PROP_352",
			15 => "PROP_317",
			16 => "PROP_357",
			17 => "PROP_2102",
			18 => "PROP_318",
			19 => "PROP_159",
			20 => "PROP_349",
			21 => "PROP_327",
			22 => "PROP_2052",
			23 => "PROP_370",
			24 => "PROP_336",
			25 => "PROP_2115",
			26 => "PROP_346",
			27 => "PROP_2120",
			28 => "PROP_2053",
			29 => "PROP_363",
			30 => "PROP_320",
			31 => "PROP_2089",
			32 => "PROP_325",
			33 => "PROP_2103",
			34 => "PROP_2085",
			35 => "PROP_300",
			36 => "PROP_322",
			37 => "PROP_362",
			38 => "PROP_365",
			39 => "PROP_359",
			40 => "PROP_284",
			41 => "PROP_364",
			42 => "PROP_356",
			43 => "PROP_343",
			44 => "PROP_2083",
			45 => "PROP_314",
			46 => "PROP_348",
			47 => "PROP_316",
			48 => "PROP_350",
			49 => "PROP_333",
			50 => "PROP_332",
			51 => "PROP_360",
			52 => "PROP_353",
			53 => "PROP_347",
			54 => "PROP_25",
			55 => "PROP_2114",
			56 => "PROP_301",
			57 => "PROP_2101",
			58 => "PROP_2067",
			59 => "PROP_323",
			60 => "PROP_324",
			61 => "PROP_355",
			62 => "PROP_304",
			63 => "PROP_358",
			64 => "PROP_319",
			65 => "PROP_344",
			66 => "PROP_328",
			67 => "PROP_338",
			68 => "PROP_2113",
			69 => "PROP_366",
			70 => "PROP_302",
			71 => "PROP_303",
			72 => "PROP_2054",
			73 => "PROP_341",
			74 => "PROP_223",
			75 => "PROP_283",
			76 => "PROP_354",
			77 => "PROP_313",
			78 => "PROP_2066",
			79 => "PROP_329",
			80 => "PROP_342",
			81 => "PROP_367",
			82 => "PROP_340",
			83 => "PROP_351",
			84 => "PROP_368",
			85 => "PROP_369",
			86 => "PROP_331",
			87 => "PROP_337",
			88 => "PROP_345",
			89 => "PROP_339",
			90 => "PROP_310",
			91 => "PROP_309",
			92 => "PROP_330",
			93 => "PROP_2017",
			94 => "PROP_335",
			95 => "PROP_321",
			96 => "PROP_308",
			97 => "PROP_206",
			98 => "PROP_334",
			99 => "PROP_2100",
			100 => "PROP_311",
			101 => "PROP_2132",
			102 => "SHUM",
			103 => "PROP_361",
			104 => "PROP_326",
			105 => "PROP_315",
			106 => "PROP_2091",
			107 => "PROP_2026",
			108 => "PROP_307",
			109 => "PROP_2027",
			110 => "PROP_2098",
			111 => "PROP_2122",
			112 => "PROP_24",
			113 => "PROP_2049",
			114 => "PROP_22",
			115 => "PROP_2095",
			116 => "PROP_2044",
			117 => "PROP_162",
			118 => "PROP_2055",
			119 => "PROP_2069",
			120 => "PROP_2062",
			121 => "PROP_2061",
			122 => "CML2_LINK",
			123 => "",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "CML2_LINK",
			2 => "DETAIL_PAGE_URL",
			3 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "MORE_PHOTO",
			1 => "COLOR_REF",
			2 => "SIZES",
			3 => "SIZES2",
			4 => "VOLUME",
			5 => "SIZES5",
			6 => "SIZES4",
			7 => "SIZES3",
			8 => "SPORT",
			9 => "",
		),
		"LIST_OFFERS_LIMIT" => "10",
		"SORT_BUTTONS" => array(
			0 => "POPULARITY",
			1 => "NAME",
			2 => "PRICE",
		),
		"SORT_PRICES" => "REGION_PRICE",
		"DEFAULT_LIST_TEMPLATE" => "block",
		"SECTION_DISPLAY_PROPERTY" => "UF_SECTION_TEMPLATE",
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",
		"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SECTION_PICTURES" => "Y",
		"SHOW_SECTION_SIBLINGS" => "Y",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "BRAND",
			1 => "EXPANDABLES_FILTER",
			2 => "LINK_SALE",
			3 => "ASSOCIATED_FILTER",
			4 => "PRICE_CURRENCY",
			5 => "PRICE",
			6 => "PRICEOLD",
			7 => "ECONOMY",
			8 => "STATUS",
			9 => "EXPANDABLES",
			10 => "CML2_ARTICLE",
			11 => "CML2_BASE_UNIT",
			12 => "VIDEO_YOUTUBE",
			13 => "POPUP_VIDEO",
			14 => "LINK_FAQ",
			15 => "PROP_2104",
			16 => "ASSOCIATED",
			17 => "HELP_TEXT",
			18 => "PROP_2033",
			19 => "SERVICES",
			20 => "CML2_ATTRIBUTES",
			21 => "COLOR_REF2",
			22 => "PROP_2065",
			23 => "PROP_305",
			24 => "PROP_352",
			25 => "PROP_317",
			26 => "PROP_357",
			27 => "PROP_2102",
			28 => "PROP_318",
			29 => "PROP_159",
			30 => "PROP_349",
			31 => "PROP_3916",
			32 => "PROP_327",
			33 => "PROP_2052",
			34 => "PROP_370",
			35 => "PROP_2826",
			36 => "PROP_336",
			37 => "PROP_2115",
			38 => "PROP_346",
			39 => "PROP_2120",
			40 => "PROP_2053",
			41 => "PROP_363",
			42 => "PROP_320",
			43 => "PROP_2089",
			44 => "PROP_3915",
			45 => "PROP_374",
			46 => "PROP_325",
			47 => "PROP_2103",
			48 => "PROP_2085",
			49 => "PROP_300",
			50 => "PROP_322",
			51 => "PROP_362",
			52 => "PROP_365",
			53 => "PROP_359",
			54 => "PROP_284",
			55 => "PROP_364",
			56 => "PROP_356",
			57 => "PROP_343",
			58 => "PROP_373",
			59 => "PROP_2083",
			60 => "PROP_314",
			61 => "PROP_348",
			62 => "PROP_316",
			63 => "PROP_350",
			64 => "PROP_333",
			65 => "PROP_372",
			66 => "PROP_332",
			67 => "PROP_360",
			68 => "PROP_353",
			69 => "PROP_3015",
			70 => "PROP_347",
			71 => "PROP_25",
			72 => "PROP_2114",
			73 => "PROP_301",
			74 => "PROP_2101",
			75 => "PROP_2067",
			76 => "PROP_323",
			77 => "PROP_324",
			78 => "PROP_355",
			79 => "PROP_304",
			80 => "PROP_358",
			81 => "PROP_319",
			82 => "PROP_344",
			83 => "PROP_328",
			84 => "PROP_338",
			85 => "PROP_2113",
			86 => "PROP_371",
			87 => "PROP_366",
			88 => "PROP_302",
			89 => "PROP_303",
			90 => "PROP_2054",
			91 => "PROP_341",
			92 => "PROP_223",
			93 => "PROP_283",
			94 => "PROP_354",
			95 => "PROP_313",
			96 => "PROP_2066",
			97 => "PROP_329",
			98 => "PROP_342",
			99 => "PROP_367",
			100 => "PROP_2084",
			101 => "PROP_340",
			102 => "PROP_351",
			103 => "PROP_368",
			104 => "PROP_369",
			105 => "PROP_331",
			106 => "PROP_337",
			107 => "PROP_345",
			108 => "PROP_3016",
			109 => "PROP_339",
			110 => "PROP_310",
			111 => "PROP_309",
			112 => "PROP_330",
			113 => "PROP_2017",
			114 => "PROP_335",
			115 => "PROP_2691",
			116 => "PROP_321",
			117 => "PROP_308",
			118 => "PROP_206",
			119 => "PROP_334",
			120 => "PROP_2100",
			121 => "PROP_311",
			122 => "PROP_2132",
			123 => "SHUM",
			124 => "PROP_361",
			125 => "PROP_326",
			126 => "PROP_315",
			127 => "PROP_2091",
			128 => "PROP_2026",
			129 => "PROP_307",
			130 => "LINK_BLOG",
			131 => "LINK_VACANCY",
			132 => "PROP_2090",
			133 => "PROP_2027",
			134 => "PROP_2098",
			135 => "PROP_2112",
			136 => "PROP_2122",
			137 => "PROP_221",
			138 => "PROP_24",
			139 => "PROP_2134",
			140 => "PROP_23",
			141 => "PROP_2049",
			142 => "PROP_22",
			143 => "PROP_2095",
			144 => "PROP_2044",
			145 => "PROP_162",
			146 => "PROP_207",
			147 => "PROP_220",
			148 => "PROP_2094",
			149 => "PROP_2092",
			150 => "PROP_2111",
			151 => "PROP_2133",
			152 => "PROP_2096",
			153 => "PROP_2086",
			154 => "PROP_285",
			155 => "PROP_2130",
			156 => "PROP_286",
			157 => "PROP_222",
			158 => "PROP_2121",
			159 => "PROP_2123",
			160 => "PROP_2124",
			161 => "PROP_2093",
			162 => "LINK_REVIEWS",
			163 => "PROP_312",
			164 => "PROP_3083",
			165 => "PROP_2055",
			166 => "PROP_2069",
			167 => "PROP_2062",
			168 => "PROP_2061",
			169 => "RECOMMEND",
			170 => "NEW",
			171 => "STOCK",
			172 => "VIDEO",
			173 => "INSTRUCTIONS",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "DETAIL_PAGE_URL",
			4 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "ARTICLE",
			1 => "MORE_PHOTO",
			2 => "COLOR_REF",
			3 => "SIZES",
			4 => "WEIGHT",
			5 => "AGE",
			6 => "SIZES2",
			7 => "RUKAV",
			8 => "FRCOLLECTION",
			9 => "FRLINE",
			10 => "VOLUME",
			11 => "FRMADEIN",
			12 => "FRELITE",
			13 => "SIZES5",
			14 => "SIZES4",
			15 => "SIZES3",
			16 => "TALL",
			17 => "FRTYPE",
			18 => "FRAROMA",
			19 => "SPORT",
			20 => "VLAGOOTVOD",
			21 => "KAPUSHON",
			22 => "FRFITIL",
			23 => "FRFAMILY",
			24 => "FRSOSTAVCANDLE",
			25 => "FRFORM",
			26 => "",
		),
		"PROPERTIES_DISPLAY_LOCATION" => "TAB",
		"SHOW_BRAND_PICTURE" => "Y",
		"SHOW_ASK_BLOCK" => "Y",
		"ASK_FORM_ID" => "2",
		"SHOW_ADDITIONAL_TAB" => "Y",
		"PROPERTIES_DISPLAY_TYPE" => "TABLE",
		"SHOW_KIT_PARTS" => "Y",
		"SHOW_KIT_PARTS_PRICES" => "Y",
		"LINK_IBLOCK_TYPE" => "aspro_lite_content",
		"LINK_IBLOCK_ID" => "15",
		"LINK_PROPERTY_SID" => "LINK_SERVICES",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "Y",
		"ALSO_BUY_ELEMENT_COUNT" => "5",
		"ALSO_BUY_MIN_BUYES" => "2",
		"USE_STORE" => "Y",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "Y",
		"MIN_AMOUNT" => "10",
		"STORE_PATH" => "/contacts/stores/#store_id#/",
		"MAIN_TITLE" => "Наличие",
		"MAX_AMOUNT" => "20",
		"USE_ONLY_MAX_AMOUNT" => "Y",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "name",
		"OFFERS_SORT_ORDER2" => "asc",
		"PAGER_TEMPLATE" => "main",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"SHOW_QUANTITY" => "Y",
		"SHOW_MEASURE" => "Y",
		"SHOW_QUANTITY_COUNT" => "Y",
		"USE_RATING" => "Y",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DEFAULT_COUNT" => "1",
		"SHOW_HINTS" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"STORES" => array(
			0 => "1",
			1 => "2",
			2 => "4",
			3 => "5",
			4 => "6",
			5 => "",
		),
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"TOP_ELEMENT_COUNT" => "8",
		"TOP_LINE_ELEMENT_COUNT" => "4",
		"TOP_ELEMENT_SORT_FIELD" => "sort",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_FIELD2" => "sort",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPONENT_TEMPLATE" => "main",
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"SHOW_DEACTIVATED" => "N",
		"TOP_OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "",
		),
		"TOP_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "10",
		"SECTION_TOP_BLOCK_TITLE" => "Лучшие предложения",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES",
			2 => "VOLUME",
			3 => "FRTYPE",
			4 => "WEIGHT",
			5 => "SIZES2",
			6 => "SIZES3",
			7 => "SIZES4",
			8 => "SIZES5",
		),
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "similar",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"VIEWED_ELEMENT_COUNT" => "20",
		"VIEWED_BLOCK_TITLE" => "Ранее вы смотрели",
		"ELEMENT_SORT_FIELD_BOX" => "name",
		"ELEMENT_SORT_ORDER_BOX" => "asc",
		"ELEMENT_SORT_FIELD_BOX2" => "id",
		"ELEMENT_SORT_ORDER_BOX2" => "desc",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"MAX_GALLERY_ITEMS" => "5",
		"SHOW_GALLERY" => "Y",
		"SHOW_PROPS" => "Y",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"SKU_DETAIL_ID" => "oid",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"AJAX_FILTER_CATALOG" => "Y",
		"AJAX_CONTROLS" => "Y",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DISPLAY_ELEMENT_SLIDER" => "10",
		"SHOW_ONE_CLICK_BUY" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "8",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"OFFER_HIDE_NAME_PROPS" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "Y",
		"SALE_STIKER" => "SALE_TEXT",
		"SHOW_DISCOUNT_TIME" => "Y",
		"SHOW_RATING" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_OFFERS_LIMIT" => "0",
		"DETAIL_EXPANDABLES_TITLE" => "С этим товаром покупают",
		"DETAIL_ASSOCIATED_TITLE" => "Вам также может понравиться",
		"DETAIL_LINKED_GOODS_SLIDER" => "Y",
		"DETAIL_LINKED_GOODS_TABS" => "Y",
		"DETAIL_PICTURE_MODE" => "MAGNIFIER",
		"SHOW_UNABLE_SKU_PROPS" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"COMPATIBLE_MODE" => "Y",
		"TEMPLATE_THEME" => "blue",
		"LABEL_PROP" => "",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"SIDEBAR_SECTION_SHOW" => "Y",
		"SIDEBAR_DETAIL_SHOW" => "N",
		"SIDEBAR_PATH" => "",
		"USE_SALE_BESTSELLERS" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FILTER_HIDE_ON_MOBILE" => "N",
		"INSTANT_RELOAD" => "N",
		"COMPARE_POSITION_FIXED" => "Y",
		"COMPARE_POSITION" => "top left",
		"USE_RATIO_IN_RANGES" => "Y",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"DETAIL_ADD_TO_BASKET_ACTION_PRIMARY" => array(
			0 => "BUY",
		),
		"TOP_PROPERTY_CODE_MOBILE" => "",
		"TOP_VIEW_MODE" => "SECTION",
		"TOP_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"TOP_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"TOP_ENLARGE_PRODUCT" => "STRICT",
		"TOP_SHOW_SLIDER" => "Y",
		"TOP_SLIDER_INTERVAL" => "3000",
		"TOP_SLIDER_PROGRESS" => "N",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"LIST_PROPERTY_CODE_MOBILE" => "",
		"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_SHOW_SLIDER" => "Y",
		"LIST_SLIDER_INTERVAL" => "3000",
		"LIST_SLIDER_PROGRESS" => "N",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => "",
		"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => "",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_IMAGE_RESOLUTION" => "16by9",
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
		"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
		"DETAIL_BLOCKS_ORDER" => "complect,kit,sku,tabs,associated,expandables,big_gallery,services,articles,comments,gift",
		"DETAIL_SHOW_SLIDER" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "POPUP",
			1 => "MAGNIFIER",
		),
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"MESS_PRICE_RANGES_TITLE" => "Цены",
		"MESS_DESCRIPTION_TAB" => "Описание",
		"MESS_PROPERTIES_TAB" => "Характеристики",
		"MESS_COMMENTS_TAB" => "Комментарии",
		"LAZY_LOAD" => "N",
		"LOAD_ON_SCROLL" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"DETAIL_DOCS_PROP" => "INSTRUCTIONS",
		"STIKERS_PROP" => "HIT",
		"USE_SHARE" => "Y",
		"TAB_OFFERS_NAME" => "",
		"TAB_DESCR_NAME" => "",
		"TAB_KOMPLECT_NAME" => "",
		"TAB_NABOR_NAME" => "",
		"TAB_CHAR_NAME" => "",
		"TAB_VIDEO_NAME" => "",
		"TAB_REVIEW_NAME" => "",
		"TAB_STOCK_NAME" => "",
		"TAB_DOPS_NAME" => "",
		"BLOCK_SERVICES_NAME" => "",
		"BLOCK_DOCS_NAME" => "",
		"ELEMENT_DETAIL_TYPE_VIEW" => "FROM_MODULE",
		"SHOW_CHEAPER_FORM" => "Y",
		"LANDING_TITLE" => "Популярные категории",
		"LANDING_SECTION_COUNT" => "10",
		"LANDING_SEARCH_TITLE" => "Похожие запросы",
		"LANDING_SEARCH_COUNT" => "7",
		"SECTIONS_TYPE_VIEW" => "FROM_MODULE",
		"SECTION_TYPE_VIEW" => "FROM_MODULE",
		"SECTION_ELEMENTS_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENTS_PRICE_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENTS_LIST_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENTS_TABLE_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENT_TYPE_VIEW" => "FROM_MODULE",
		"LANDING_TYPE_VIEW" => "FROM_MODULE",
		"FILE_404" => "",
		"SHOW_MEASURE_WITH_RATIO" => "N",
		"SHOW_COUNTER_LIST" => "Y",
		"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"SHOW_ARTICLE_SKU" => "Y",
		"USE_FILTER_PRICE" => "N",
		"DISPLAY_ELEMENT_COUNT" => "Y",
		"RESTART" => "N",
		"USE_LANGUAGE_GUESS" => "Y",
		"NO_WORD_LOGIC" => "Y",
		"SORT_REGION_PRICE" => "BASE",
		"SHOW_SECTION_DESC" => "Y",
		"USE_ADDITIONAL_GALLERY" => "Y",
		"ADDITIONAL_GALLERY_TYPE" => "BIG",
		"ADDITIONAL_GALLERY_PROPERTY_CODE" => "PHOTO_GALLERY",
		"ADDITIONAL_GALLERY_OFFERS_PROPERTY_CODE" => "-",
		"BLOCK_ADDITIONAL_GALLERY_NAME" => "",
		"STORES_FILTER" => "SORT",
		"STORES_FILTER_ORDER" => "SORT_ASC",
		"VIEW_BLOCK_TYPE" => "N",
		"SHOW_HOW_BUY" => "Y",
		"TITLE_HOW_BUY" => "Как купить",
		"SHOW_DELIVERY" => "N",
		"TITLE_DELIVERY" => "Доставка",
		"SHOW_PAYMENT" => "N",
		"TITLE_PAYMENT" => "Оплата",
		"SHOW_GARANTY" => "Y",
		"TITLE_GARANTY" => "Условия гарантии",
		"TITLE_SLIDER" => "Рекомендуем",
		"SHOW_SEND_GIFT" => "Y",
		"SEND_GIFT_FORM_NAME" => "",
		"BLOCK_LANDINGS_NAME" => "",
		"BLOG_IBLOCK_ID" => "15",
		"BLOCK_BLOG_NAME" => "",
		"RECOMEND_COUNT" => "5",
		"VISIBLE_PROP_COUNT" => "6",
		"BIGDATA_EXT" => "bigdata_1",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
		"ALT_TITLE_GET" => "NORMAL",
		"BUNDLE_ITEMS_COUNT" => "3",
		"SHOW_LANDINGS_SEARCH" => "Y",
		"SHOW_LANDINGS" => "Y",
		"LANDING_POSITION" => "BEFORE_PRODUCTS",
		"USE_DETAIL_PREDICTION" => "Y",
		"SECTION_BG" => "UF_SECTION_BG_IMG",
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => "",
		"LANDING_IBLOCK_ID" => "19",
		"DETAIL_BLOCKS_TAB_ORDER" => "desc,reviews,char,faq,video,buy,docs,payment,delivery,dops",
		"DETAIL_BLOCKS_ALL_ORDER" => "desc,associated,big_gallery,video,char,faq,expandables,articles,docs,services,complect,kit,sku,reviews,buy,payment,delivery,dops,comments,gift",
		"DELIVERY_CALC" => "Y",
		"DELIVERY_CALC_NAME" => "",
		"ASK_TAB" => "",
		"TAB_VACANCY_NAME" => "",
		"VIEW_TYPE" => "table",
		"SHOW_BUY_DELIVERY" => "Y",
		"TITLE_BUY_DELIVERY" => "Оплата и доставка",
		"BLOG_URL" => "catalog_comments",
		"SHOW_MORE_SUBSECTIONS" => "Y",
		"SHOW_SIDE_BLOCK_LAST_LEVEL" => "N",
		"SHOW_SORT_IN_FILTER" => "N",
		"SUBSECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SUBSECTION_DESC" => "Y",
		"USE_CUSTOM_RESIZE" => "N",
		"LINKED_ELEMENT_TAB_SORT_FIELD" => "sort",
		"LINKED_ELEMENT_TAB_SORT_ORDER" => "asc",
		"LINKED_ELEMENT_TAB_SORT_FIELD2" => "id",
		"LINKED_ELEMENT_TAB_SORT_ORDER2" => "desc",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "Y",
		"MAX_IMAGE_SIZE" => "1",
		"BIGDATA_SHOW_FROM_SECTION" => "N",
		"LANDING_SEARCH_COUNT_MOBILE" => "3",
		"USE_BIG_DATA_IN_SEARCH" => "Y",
		"BIG_DATA_IN_SEARCH_RCM_TYPE" => "bestsell",
		"TITLE_SLIDER_IN_SEARCH" => "Возможно, вам подойдет",
		"RECOMEND_IN_SEARCH_COUNT" => "10",
		"LANDING_SECTION_COUNT_MOBILE" => "3",
		"SHOW_SMARTSEO_TAGS" => "Y",
		"SMARTSEO_TAGS_COUNT" => "10",
		"SMARTSEO_TAGS_COUNT_MOBILE" => "3",
		"TAB_BUY_SERVICES_NAME" => "",
		"COUNT_SERVICES_IN_ANNOUNCE" => "2",
		"SHOW_ALL_SERVICES_IN_SLIDE" => "N",
		"SHOW_SKU_DESCRIPTION" => "N",
		"MODULES_ELEMENT_COUNT" => "10",
		"DETAIL_SET_PRODUCT_TITLE" => "Собрать комплект",
		"DISPLAY_LINKED_PAGER" => "Y",
		"SHOW_SORT_RANK_BUTTON" => "Y",
		"USE_LANDINGS_GROUP" => "Y",
		"LANDINGS_GROUP_FROM_SEO" => "N",
		"SMARTSEO_TAGS_BY_GROUPS" => "Y",
		"VISIBLE_PROP_WITH_OFFER" => "N",
		"SMARTSEO_TAGS_SHOW_DEACTIVATED" => "N",
		"SMARTSEO_TAGS_SORT" => "SORT",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"SEARCH_PAGE_RESULT_COUNT" => "50",
		"SEARCH_RESTART" => "N",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEARCH_CHECK_DATES" => "Y",
		"SEARCH_USE_SEARCH_RESULT_ORDER" => "N",
		"SKU_IBLOCK_ID" => "14",
		"SKU_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES",
			2 => "VOLUME",
			3 => "SIZES3",
			4 => "SIZES5",
			5 => "SIZES4",
		),
		"T_SKU" => "",
		"SKU_PROPERTY_CODE" => array(
			0 => "FORM_ORDER",
			1 => "STATUS",
			2 => "PRICE_CURRENCY",
			3 => "PRICE",
			4 => "PRICEOLD",
			5 => "ECONOMY",
			6 => "ARTICLE",
			7 => "POPUP_VIDEO",
			8 => "MORE_PHOTO",
			9 => "COLOR_REF",
			10 => "CML2_LINK",
			11 => "SIZES",
			12 => "WEIGHT",
			13 => "AGE",
			14 => "SIZES2",
			15 => "RUKAV",
			16 => "FRCOLLECTION",
			17 => "FRLINE",
			18 => "VOLUME",
			19 => "FRMADEIN",
			20 => "FRELITE",
			21 => "SIZES3",
			22 => "SIZES5",
			23 => "SIZES4",
			24 => "TALL",
			25 => "FRTYPE",
		),
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_VIEWED" => "Y",
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"USE_DETAIL_TABS" => "FROM_MODULE",
		"USE_COMPARE_GROUP" => "Y",
		"SHOW_CHILD_SECTIONS" => "Y",
		"LANDING_TIZER_IBLOCK_ID" => "5",
		"LANDING_SECTION_COUNT_VISIBLE" => "12",
		"SORT_PROP" => array(
			0 => "SHOWS",
			1 => "NAME",
			2 => "PRICES",
			3 => "QUANTITY",
		),
		"SORT_PROP_DEFAULT" => "SHOWS",
		"SORT_DIRECTION" => "desc",
		"SHOW_LIST_TYPE_SECTION" => "Y",
		"SECTION_LIST_PREVIEW_DESCRIPTION" => "Y",
		"SECTION_LIST_DISPLAY_TYPE" => "3",
		"OPT_BUY" => "Y",
		"T_DESC" => "",
		"T_CHAR" => "",
		"SHOW_BIG_GALLERY" => "Y",
		"TYPE_BIG_GALLERY" => "SMALL",
		"T_BIG_GALLERY" => "",
		"BIG_GALLERY_PROP_CODE" => "PHOTO_GALLERY",
		"T_VIDEO" => "",
		"T_DOCS" => "",
		"DOCS_PROP_CODE" => "INSTRUCTIONS",
		"T_REVIEWS" => "",
		"T_VACANCY" => "",
		"T_SALE" => "",
		"T_ARTICLES" => "",
		"T_SERVICES" => "",
		"T_PARTNERS" => "",
		"T_GOODS" => "",
		"SHOW_BUY" => "N",
		"T_PAYMENT" => "",
		"T_DELIVERY" => "",
		"SHOW_DOPS" => "N",
		"HEADING_COUNT_ELEMENTS" => "Y",
		"T_FAQ" => "",
		"DETAIL_BLOG_USE" => "N",
		"DETAIL_VK_USE" => "N",
		"DETAIL_FB_USE" => "N",
		"SKU_ADD_PICT_PROP" => "MORE_PHOTO",
		"CHEAPER_FORM_NAME" => "",
		"REVIEW_COMMENT_REQUIRED" => "N",
		"REVIEW_FILTER_BUTTONS" => array(
			0 => "PHOTO",
			1 => "RATING",
			2 => "TEXT",
		),
		"REAL_CUSTOMER_TEXT" => "",
		"T_ASSOCIATED" => "Дополнительные услуги",
		"T_EXPANDABLES" => "Электромотоциклы Super Soco",
		"IBLOCK_TIZERS_ID" => "5",
		"SHOW_REVIEW" => "N",
		"T_DOPS" => "",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
); ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>