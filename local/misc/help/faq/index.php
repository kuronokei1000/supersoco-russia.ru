<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Вопросы и ответы");

$APPLICATION->SetPageProperty(
    "title",
    "О компании Super Soco - официальный представитель в РФ +7 499 704-42-08"
);

$APPLICATION->SetPageProperty(
    "description",
    "Super Soco – это инновационная технологическая компания, которая следует передовым технологиям и новейшим трендам в промышленном дизайне"
);

?>


<?
//$APPLICATION->IncludeComponent(
//    "bitrix:news",
//    "faq",
//    [
//        "ADD_ELEMENT_CHAIN"                => "N",
//        "ADD_SECTIONS_CHAIN"               => "N",
//        "AJAX_MODE"                        => "N",
//        "AJAX_OPTION_ADDITIONAL"           => "",
//        "AJAX_OPTION_HISTORY"              => "N",
//        "AJAX_OPTION_JUMP"                 => "N",
//        "AJAX_OPTION_STYLE"                => "Y",
//        "BROWSER_TITLE"                    => "-",
//        "CACHE_FILTER"                     => "N",
//        "CACHE_GROUPS"                     => "N",
//        "CACHE_TIME"                       => "100000",
//        "CACHE_TYPE"                       => "A",
//        "CHECK_DATES"                      => "Y",
//        "COMPONENT_TEMPLATE"               => "faq",
//        "COUNT_IN_LINE"                    => "3",
//        "DETAIL_ACTIVE_DATE_FORMAT"        => "d.m.Y",
//        "DETAIL_DISPLAY_BOTTOM_PAGER"      => "Y",
//        "DETAIL_DISPLAY_TOP_PAGER"         => "N",
//        "DETAIL_FIELD_CODE"                => [
//            0 => "",
//            1 => "",
//        ],
//        "DETAIL_PAGER_SHOW_ALL"            => "Y",
//        "DETAIL_PAGER_TEMPLATE"            => "",
//        "DETAIL_PAGER_TITLE"               => "Страница",
//        "DETAIL_PROPERTY_CODE"             => [
//            0 => "TITLE_BUTTON",
//            1 => "LINK_BUTTON",
//            2 => "",
//        ],
//        "DETAIL_SET_CANONICAL_URL"         => "N",
//        "DISPLAY_BOTTOM_PAGER"             => "Y",
//        "DISPLAY_NAME"                     => "Y",
//        "DISPLAY_TOP_PAGER"                => "N",
//        "HIDE_LINK_WHEN_NO_DETAIL"         => "Y",
//        "IBLOCK_ID"                        => "11",
//        "IBLOCK_TYPE"                      => "aspro_lite_content",
//        "IMAGE_POSITION"                   => "left",
//        "INCLUDE_IBLOCK_INTO_CHAIN"        => "N",
//        "LIST_ACTIVE_DATE_FORMAT"          => "d.m.Y",
//        "LIST_FIELD_CODE"                  => [
//            0 => "PREVIEW_TEXT",
//            1 => "PREVIEW_PICTURE",
//            2 => "",
//        ],
//        "LIST_PROPERTY_CODE"               => [
//            0 => "TITLE_BUTTON",
//            1 => "LINK_BUTTON",
//            2 => "",
//        ],
//        "MESSAGE_404"                      => "",
//        "META_DESCRIPTION"                 => "-",
//        "META_KEYWORDS"                    => "-",
//        "NEWS_COUNT"                       => "20",
//        "PAGER_BASE_LINK_ENABLE"           => "N",
//        "PAGER_DESC_NUMBERING"             => "N",
//        "PAGER_DESC_NUMBERING_CACHE_TIME"  => "36000",
//        "PAGER_SHOW_ALL"                   => "N",
//        "PAGER_SHOW_ALWAYS"                => "N",
//        "PAGER_TEMPLATE"                   => ".default",
//        "PAGER_TITLE"                      => "Новости",
//        "PREVIEW_TRUNCATE_LEN"             => "",
//        "SECTION_ELEMENTS_TYPE_VIEW"       => "FROM_MODULE",
//        "SEF_FOLDER"                       => "/company/faq/",
//        "SEF_MODE"                         => "Y",
//        "SET_LAST_MODIFIED"                => "N",
//        "SET_STATUS_404"                   => "N",
//        "SET_TITLE"                        => "Y",
//        "SHOW_404"                         => "N",
//        "SHOW_ASK_QUESTION_BLOCK"          => "Y",
//        "SHOW_DETAIL_LINK"                 => "Y",
//        "SHOW_SECTION_NAME"                => "N",
//        "SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
//        "SHOW_TABS"                        => "Y",
//        "SORT_BY1"                         => "SORT",
//        "SORT_BY2"                         => "ID",
//        "SORT_ORDER1"                      => "ASC",
//        "SORT_ORDER2"                      => "DESC",
//        "STRICT_SECTION_CHECK"             => "N",
//        "S_ASK_QUESTION"                   => "Написать сообщение",
//        "TITLE_BLOCK_QUESTION"             => "Задать вопрос",
//        "USE_CATEGORIES"                   => "N",
//        "USE_FILTER"                       => "N",
//        "USE_PERMISSIONS"                  => "N",
//        "USE_RATING"                       => "N",
//        "USE_REVIEW"                       => "N",
//        "USE_RSS"                          => "N",
//        "USE_SEARCH"                       => "N",
//        "USE_SHARE"                        => "N",
//        "VIEW_TYPE"                        => "accordion",
//        "SEF_URL_TEMPLATES"                => [
//            "news"    => "",
//            "section" => "",
//            "detail"  => "",
//        ],
//    ],
//    false
//);

?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>