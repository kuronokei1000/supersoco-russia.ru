<div class="catalog-detail js-popup-block">
    <? $ElementID = $APPLICATION->IncludeComponent(
        "bitrix:catalog.element",
        "fast_view_1",
        [
            "DISPLAY_NAME"            => $arParams["DISPLAY_NAME"],
            "SEF_FOLDER"              => $arParams["SEF_FOLDER"],
            "SEF_URL_TEMPLATES"       => $arParams["SEF_URL_TEMPLATES"],
            "IBLOCK_REVIEWS_TYPE"     => $arParams["IBLOCK_REVIEWS_TYPE"],
            "IBLOCK_REVIEWS_ID"       => $arParams["IBLOCK_REVIEWS_ID"],
            "SHOW_ONE_CLICK_BUY"      => $arParams["SHOW_ONE_CLICK_BUY"],
            "SEF_MODE_BRAND_SECTIONS" => $arParams["SEF_MODE_BRAND_SECTIONS"],
            "SEF_MODE_BRAND_ELEMENT"  => $arParams["SEF_MODE_BRAND_ELEMENT"],

            "IBLOCK_TIZERS_ID" => $arParams["IBLOCK_TIZERS_ID"],

            "DISPLAY_COMPARE" => TSolution::GetFrontParametrValue('CATALOG_COMPARE'),
            "SHOW_FAVORITE"   => TSolution::GetFrontParametrValue('SHOW_FAVORITE'),

            "IBLOCK_TYPE"                    => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID"                      => $arParams["IBLOCK_ID"],
            "PROPERTY_CODE"                  => $arParams["DETAIL_PROPERTY_CODE"],
            "META_KEYWORDS"                  => $arParams["DETAIL_META_KEYWORDS"],
            "META_DESCRIPTION"               => $arParams["DETAIL_META_DESCRIPTION"],
            "BROWSER_TITLE"                  => $arParams["DETAIL_BROWSER_TITLE"],
            "BASKET_URL"                     => $arParams["BASKET_URL"],
            "ACTION_VARIABLE"                => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE"            => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE"            => $arParams["SECTION_ID_VARIABLE"],
            "DISPLAY_PANEL"                  => $arParams["DISPLAY_PANEL"],
            "CACHE_TYPE"                     => $arParams["CACHE_TYPE"],
            "CACHE_TIME"                     => $arParams["CACHE_TIME"],
            "CACHE_GROUPS"                   => $arParams["CACHE_GROUPS"],
            "SET_TITLE"                      => $arParams["SET_TITLE"],
            "SET_CANONICAL_URL"              => $arParams["DETAIL_SET_CANONICAL_URL"],
            "SET_LAST_MODIFIED"              => "Y",
            "SET_STATUS_404"                 => $arParams["SET_STATUS_404"],
            "MESSAGE_404"                    => $arParams["MESSAGE_404"],
            "SHOW_404"                       => $arParams["SHOW_404"],
            "FILE_404"                       => $arParams["FILE_404"],
            "USE_PRICE_COUNT"                => $arParams["USE_PRICE_COUNT"],
            "SHOW_PRICE_COUNT"               => $arParams["SHOW_PRICE_COUNT"],
            "PRICE_VAT_INCLUDE"              => $arParams["PRICE_VAT_INCLUDE"],
            "PRICE_VAT_SHOW_VALUE"           => $arParams["PRICE_VAT_SHOW_VALUE"],
            "LINK_IBLOCK_TYPE"               => $arParams["LINK_IBLOCK_TYPE"],
            "LINK_IBLOCK_ID"                 => $arParams["LINK_IBLOCK_ID"],
            "LINK_PROPERTY_SID"              => $arParams["LINK_PROPERTY_SID"],
            "LINK_ELEMENTS_URL"              => $arParams["LINK_ELEMENTS_URL"],
            'ADD_PICT_PROP'                  => TSolution::GetFrontParametrValue('GALLERY_PROPERTY_CODE'),
            'OFFER_ADD_PICT_PROP'            => TSolution::GetFrontParametrValue('GALLERY_PROPERTY_CODE'),
            "OFFERS_CART_PROPERTIES"         => $arParams["OFFERS_CART_PROPERTIES"],
            "LINKED_ELEMENT_TAB_SORT_FIELD"  => $arParams["LINKED_ELEMENT_TAB_SORT_FIELD"],
            "LINKED_ELEMENT_TAB_SORT_ORDER"  => $arParams["LINKED_ELEMENT_TAB_SORT_ORDER"],
            "LINKED_ELEMENT_TAB_SORT_FIELD2" => $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"],
            "LINKED_ELEMENT_TAB_SORT_ORDER2" => $arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"],
            "SKU_DETAIL_ID"                  => $arParams["SKU_DETAIL_ID"],
            "ELEMENT_ID"                     => $arResult["VARIABLES"]["ELEMENT_ID"],
            "ELEMENT_CODE"                   => $arResult["VARIABLES"]["ELEMENT_CODE"],
            "SECTION_ID"                     => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE"                   => $arResult["VARIABLES"]["SECTION_CODE"],
            "SECTION_URL"                    => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
            "DETAIL_URL"                     => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
            "ADD_SECTIONS_CHAIN"             => $arParams["ADD_SECTIONS_CHAIN"],
            "ADD_ELEMENT_CHAIN"              => $arParams["ADD_ELEMENT_CHAIN"],
            "USE_STORE"                      => $arParams["USE_STORE"],
            "USE_STORE_PHONE"                => $arParams["USE_STORE_PHONE"],
            "USE_STORE_SCHEDULE"             => $arParams["USE_STORE_SCHEDULE"],
            "USE_PRODUCT_QUANTITY"           => $arParams["USE_PRODUCT_QUANTITY"],
            "PRODUCT_QUANTITY_VARIABLE"      => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "BLOG_URL"                       => $arParams["DETAIL_BLOG_URL"],

            "USE_DETAIL_TABS" => $arParams['USE_DETAIL_TABS'],

            "SKU_IBLOCK_ID"      => $arParams["SKU_IBLOCK_ID"],
            "SKU_TREE_PROPS"     => $arParams["SKU_TREE_PROPS"],
            "SKU_PROPERTY_CODE"  => $arParams["SKU_PROPERTY_CODE"],
            "SKU_SORT_FIELD"     => $arParams["SKU_SORT_FIELD"],
            "SKU_SORT_ORDER"     => $arParams["SKU_SORT_ORDER"],
            "SKU_SORT_FIELD2"    => $arParams["SKU_SORT_FIELD2"],
            "SKU_SORT_ORDER2"    => $arParams["SKU_SORT_ORDER2"],
            "OFFERS_SORT_FIELD"  => $arParams["SKU_SORT_FIELD"],
            "OFFERS_SORT_ORDER"  => $arParams["SKU_SORT_ORDER"],
            "OFFERS_SORT_FIELD2" => $arParams["SKU_SORT_FIELD2"],
            "OFFERS_SORT_ORDER2" => $arParams["SKU_SORT_ORDER2"],
            "TYPE_SKU"           => $typeSKU,
            "OID"                => $arParams['OID'],

            'OFFER_TREE_PROPS'     => $arParams['SKU_TREE_PROPS'],
            'OFFERS_PROPERTY_CODE' => $arParams['SKU_PROPERTY_CODE'],
            "OFFERS_FIELD_CODE"    => array_merge(['ID', 'NAME'], (array)$arParams["DETAIL_OFFERS_FIELD_CODE"]),

            "T_DESC"     => ($arParams["T_DESC"] ? $arParams["T_DESC"] : GetMessage("T_DESC")),
            "T_CHAR"     => ($arParams["T_CHAR"] ? $arParams["T_CHAR"] : GetMessage("T_CHARACTERISTICS")),
            "T_DOCS"     => ($arParams["T_DOCS"] ? $arParams["T_DOCS"] : GetMessage("T_DOCS")),
            "T_FAQ"      => ($arParams["T_FAQ"] ? $arParams["T_FAQ"] : GetMessage("T_FAQ")),
            "T_REVIEWS"  => ($arParams["T_REVIEWS"] ? $arParams["T_REVIEWS"] : GetMessage("T_REVIEWS")),
            "T_SALE"     => ($arParams["T_SALE"] ? $arParams["T_SALE"] : GetMessage("T_SALE")),
            "T_SERVICES" => ($arParams["T_SERVICES"] ? $arParams["T_SERVICES"] : GetMessage("T_SERVICES")),
            "T_ARTICLES" => ($arParams["T_ARTICLES"] ? $arParams["T_ARTICLES"] : GetMessage("T_ARTICLES")),
            "T_VIDEO"    => ($arParams["T_VIDEO"] ? $arParams["T_VIDEO"] : GetMessage("T_VIDEO")),
            "T_GOODS"    => ($arParams["T_GOODS"] ? $arParams["T_GOODS"] : GetMessage("T_GOODS")),
            "T_SKU"      => ($arParams["T_SKU"] ? $arParams["T_SKU"] : GetMessage("T_SKU")),

            "SHOW_BUY"      => $arParams["SHOW_BUY"],
            "T_BUY"         => ($arParams["T_BUY"] ? $arParams["T_BUY"] : GetMessage("T_BUY")),
            "SHOW_DELIVERY" => $arParams["SHOW_DELIVERY"],
            "T_DELIVERY"    => ($arParams["T_DELIVERY"] ? $arParams["T_DELIVERY"] : GetMessage("T_DELIVERY")),
            "SHOW_PAYMENT"  => $arParams["SHOW_PAYMENT"],
            "T_PAYMENT"     => ($arParams["T_PAYMENT"] ? $arParams["T_PAYMENT"] : GetMessage("T_PAYMENT")),
            "SHOW_DOPS"     => $arParams["SHOW_DOPS"],
            "T_DOPS"        => ($arParams["T_DOPS"] ? $arParams["T_DOPS"] : GetMessage("T_DOPS")),

            "CONVERT_CURRENCY"          => $arParams["CONVERT_CURRENCY"],
            "CURRENCY_ID"               => $arParams["CURRENCY_ID"],
            "HIDE_NOT_AVAILABLE"        => TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE'),
            "HIDE_NOT_AVAILABLE_OFFERS" => TSolution::GetFrontParametrValue('HIDE_NOT_AVAILABLE_OFFERS'),
            'SHOW_DEACTIVATED'          => $arParams['SHOW_DEACTIVATED'],
            "USE_ELEMENT_COUNTER"       => $arParams["USE_ELEMENT_COUNTER"],
            'STRICT_SECTION_CHECK'      => (isset($arParams['DETAIL_STRICT_SECTION_CHECK']) ? $arParams['DETAIL_STRICT_SECTION_CHECK'] : ''),
            'RELATIVE_QUANTITY_FACTOR'  => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
            "DETAIL_USE_COMMENTS"       => (isset($arParams['DETAIL_USE_COMMENTS']) ? $arParams['DETAIL_USE_COMMENTS'] : 'N'),
            "COMMENTS_COUNT"            => (isset($arParams['COMMENTS_COUNT']) ? $arParams['COMMENTS_COUNT'] : '5'),
            "DETAIL_BLOG_EMAIL_NOTIFY"  => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : 'Y'),
            "USE_REVIEW"                => $arParams["USE_REVIEW"],
            "REVIEWS_VIEW"              => $arTheme["REVIEWS_VIEW"]["VALUE"],
            "FORUM_ID"                  => $arParams["FORUM_ID"],
            "MESSAGES_PER_PAGE"         => $arParams["MESSAGES_PER_PAGE"],
            "MAX_AMOUNT"                => $arParams["MAX_AMOUNT"],
            "USE_ONLY_MAX_AMOUNT"       => $arParams["USE_ONLY_MAX_AMOUNT"],
            "DISPLAY_WISH_BUTTONS"      => $arParams["DISPLAY_WISH_BUTTONS"],
            "DEFAULT_COUNT"             => $arParams["DEFAULT_COUNT"],
            "PROPERTIES_DISPLAY_TYPE"   => $arParams["PROPERTIES_DISPLAY_TYPE"],
            "VISIBLE_PROP_COUNT"        => $arParams["VISIBLE_PROP_COUNT"],
            "SHOW_ADDITIONAL_TAB"       => $arParams["SHOW_ADDITIONAL_TAB"],
            "SHOW_ASK_BLOCK"            => $arParams["SHOW_ASK_BLOCK"],
            "ASK_FORM_ID"               => $arParams["ASK_FORM_ID"],
            "SHOW_MEASURE"              => $arParams["SHOW_MEASURE"],
            "SHOW_HINTS"                => $arParams["SHOW_HINTS"],
            "OFFER_HIDE_NAME_PROPS"     => $arParams["OFFER_HIDE_NAME_PROPS"],
            "SHOW_KIT_PARTS"            => $arParams["SHOW_KIT_PARTS"],
            "SHOW_KIT_PARTS_PRICES"     => $arParams["SHOW_KIT_PARTS_PRICES"],

            'OFFER_TREE_PROPS'     => $arParams['SKU_TREE_PROPS'],
            'OFFERS_PROPERTY_CODE' => $arParams['SKU_PROPERTY_CODE'],
            "OFFERS_FIELD_CODE"    => array_merge(['ID', 'NAME'], (array)$arParams["DETAIL_OFFERS_FIELD_CODE"]),

            "SHOW_DISCOUNT_TIME"    => TSolution::GetFrontParametrValue('SHOW_DISCOUNT_TIME'),
            "SHOW_OLD_PRICE"        => TSolution::GetFrontParametrValue('SHOW_OLD_PRICE'),
            "SHOW_DISCOUNT_PERCENT" => TSolution::GetFrontParametrValue('SHOW_DISCOUNT_PERCENT'),
            "PRICE_CODE"            => $arParams["PRICE_CODE"],
            "STORES"                => $arParams["STORES"],

            "SHOW_EMPTY_STORE"                             => $arParams['SHOW_EMPTY_STORE'],
            "SHOW_GENERAL_STORE_INFORMATION"               => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
            "USER_FIELDS"                                  => $arParams['USER_FIELDS'],
            "FIELDS"                                       => $arParams['FIELDS'],
            "STORES"                                       => $arParams['STORES'],
            "BIG_DATA_RCM_TYPE"                            => $arParams['BIG_DATA_RCM_TYPE'],
            "USE_BIG_DATA"                                 => $arParams['USE_BIG_DATA'],
            "USE_MAIN_ELEMENT_SECTION"                     => $arParams["USE_MAIN_ELEMENT_SECTION"],
            "PARTIAL_PRODUCT_PROPERTIES"                   => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
            "ADD_PROPERTIES_TO_BASKET"                     => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
            "PRODUCT_PROPERTIES"                           => $arParams["PRODUCT_PROPERTIES"],
            "MAX_GALLERY_ITEMS"                            => $arParams["MAX_GALLERY_ITEMS"],
            "SHOW_GALLERY"                                 => $arParams["SHOW_GALLERY"],
            'SHOW_BASIS_PRICE'                             => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
            'DISABLE_INIT_JS_IN_COMPONENT'                 => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
            'COMPATIBLE_MODE'                              => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
            'SET_VIEWED_IN_COMPONENT'                      => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),
            'SHOW_SLIDER'                                  => (isset($arParams['DETAIL_SHOW_SLIDER']) ? $arParams['DETAIL_SHOW_SLIDER'] : ''),
            'SLIDER_INTERVAL'                              => (isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : ''),
            'SLIDER_PROGRESS'                              => (isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : ''),
            'USE_ENHANCED_ECOMMERCE'                       => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
            'DATA_LAYER_NAME'                              => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
            'GALLERY_SIZE'                                 => $gallerySize,
            'GALLERY_THUMB_POSITION'                       => 'vertical',
            "USE_GIFTS_DETAIL"                             => 'N',
            "USE_GIFTS_MAIN_PR_SECTION_LIST"               => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] ?: 'Y',
            "GIFTS_SHOW_DISCOUNT_PERCENT"                  => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
            "GIFTS_SHOW_OLD_PRICE"                         => $arParams['GIFTS_SHOW_OLD_PRICE'],
            "GIFTS_DETAIL_PAGE_ELEMENT_COUNT"              => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
            "GIFTS_DETAIL_HIDE_BLOCK_TITLE"                => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
            "GIFTS_DETAIL_TEXT_LABEL_GIFT"                 => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
            "GIFTS_DETAIL_BLOCK_TITLE"                     => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
            "GIFTS_SHOW_NAME"                              => $arParams['GIFTS_SHOW_NAME'],
            "GIFTS_SHOW_IMAGE"                             => $arParams['GIFTS_SHOW_IMAGE'],
            "GIFTS_MESS_BTN_BUY"                           => $arParams['GIFTS_MESS_BTN_BUY'],
            "GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
            "GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE"        => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

            "SHOW_BIG_GALLERY"      => $arParams["SHOW_BIG_GALLERY"],
            "TYPE_BIG_GALLERY"      => $arParams["TYPE_BIG_GALLERY"],
            "BIG_GALLERY_PROP_CODE" => $arParams["BIG_GALLERY_PROP_CODE"],
            "T_BIG_GALLERY"         => ($arParams["T_BIG_GALLERY"] ? $arParams["T_BIG_GALLERY"] : GetMessage(
                "T_BIG_GALLERY"
            )),

            "LINKED_FILTER_BY_PROP"   => $arAllValues,
            "LINKED_FILTER_BY_FILTER" => $arTab,

            "DETAIL_BLOCKS_ORDER"     => ($arParams["DETAIL_BLOCKS_ORDER"] ? $arParams["DETAIL_BLOCKS_ORDER"] : 'sale,tabs,big_gallery,services,articles,licenses,goods,comments'),
            "DETAIL_BLOCKS_TAB_ORDER" => ($arParams["DETAIL_BLOCKS_TAB_ORDER"] ? $arParams["DETAIL_BLOCKS_TAB_ORDER"] : 'desc,char,docs,faq,video,reviews,custom_tab'),
            "DETAIL_BLOCKS_ALL_ORDER" => ($arParams["DETAIL_BLOCKS_ALL_ORDER"] ? $arParams["DETAIL_BLOCKS_ALL_ORDER"] : 'sale,desc,char,reviews,big_gallery,video,services,articles,docs,licenses,faq,goods,custom_tab,comments'),

            "GRUPPER_PROPS"           => $arParams["GRUPPER_PROPS"],
            "ORDER_VIEW"              => $bOrderViewBasket,
            "SHOW_UNABLE_SKU_PROPS"   => $arParams["SHOW_UNABLE_SKU_PROPS"],
            "SHOW_ARTICLE_SKU"        => $arParams["SHOW_ARTICLE_SKU"],
            "SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
            "STORES_FILTER"           => ($arParams["STORES_FILTER"] ? $arParams["STORES_FILTER"] : "TITLE"),
            "STORES_FILTER_ORDER"     => ($arParams["STORES_FILTER_ORDER"] ? $arParams["STORES_FILTER_ORDER"] : "SORT_ASC"),
            "BUNDLE_ITEMS_COUNT"      => $arParams["BUNDLE_ITEMS_COUNT"],
            "DETAIL_DOCS_PROP"        => $arParams["DETAIL_DOCS_PROP"],
            "LIST_FIELD_CODE"         => $arParams["LIST_FIELD_CODE"],
            "LIST_PROPERTY_CODE"      => $arParams["LIST_PROPERTY_CODE"],

            "SHOW_RATING" => $arParams['SHOW_RATING'],

            "BRAND_PROP_CODE"        => $arParams["DETAIL_BRAND_PROP_CODE"],
            "BRAND_USE"              => $arParams["DETAIL_BRAND_USE"],
            "GOODS_TEMPLATE"         => ($arParams["ELEMENTS_TABLE_TYPE_VIEW"] ? ($arParams["ELEMENTS_TABLE_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["ELEMENTS_TABLE_TYPE_VIEW"]["VALUE"] : $arParams["ELEMENTS_TABLE_TYPE_VIEW"]) : "catalog_linked"),
            "DETAIL_LINKED_TEMPLATE" => (isset($arParams['DETAIL_LINKED_TEMPLATE']) ? $arParams['DETAIL_LINKED_TEMPLATE'] : 'linked'),
            "COMMENTS_COUNT"         => $arParams['COMMENTS_COUNT'],
            "DETAIL_USE_COMMENTS"    => $arParams['DETAIL_USE_COMMENTS'],
            "FB_USE"                 => $arParams["DETAIL_FB_USE"],
            "VK_USE"                 => $arParams["DETAIL_VK_USE"],
            "BLOG_USE"               => $arParams["DETAIL_BLOG_USE"],
            "BLOG_TITLE"             => $arParams["DETAIL_BLOG_TITLE"],
            "BLOG_URL"               => $arParams["DETAIL_BLOG_URL"],
            "BLOG_EMAIL_NOTIFY"      => $arParams["DETAIL_BLOG_EMAIL_NOTIFY"],
            "FB_TITLE"               => $arParams["DETAIL_FB_TITLE"],
            "FB_APP_ID"              => $arParams["DETAIL_FB_APP_ID"],
            "VK_TITLE"               => $arParams["DETAIL_VK_TITLE"],
            "VK_API_ID"              => $arParams["DETAIL_VK_API_ID"],
            "SHOW_ONE_CLINK_BUY"     => $arParams["SHOW_ONE_CLINK_BUY"],
            "USE_PRICE_COUNT"        => "N",
            "USE_REGION"             => ($arRegion ? "Y" : "N"),

            "CALCULATE_DELIVERY"                => $arParams["CALCULATE_DELIVERY"],
            "EXPRESSION_FOR_CALCULATE_DELIVERY" => $arTheme['EXPRESSION_FOR_CALCULATE_DELIVERY']['VALUE'],
            "OFFERS_LIMIT"                      => $arParams["DETAIL_OFFERS_LIMIT"] ?? TSolution::GetFrontParametrValue(
                    'CATALOG_SKU_LIMIT'
                ),
            "FILL_ITEM_ALL_PRICES"              => "Y",
        ],
        $component
    ); ?>
</div>