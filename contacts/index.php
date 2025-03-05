<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Контакты Super Soco");

$APPLICATION->SetPageProperty(
    "title",
    "Контакты официального дистрибьютора Super Soco +7 499 704-42-08"
);

$APPLICATION->SetPageProperty(
    "description",
    "Офис официального представителя компании Super Soco находится в Москве. Обязательно приходите к нам на чашку ароматного кофе"
);

global $arTheme;

if ($arTheme["STORES_SOURCE"]["VALUE"] != 'IBLOCK' && $arTheme["PAGE_CONTACTS"]["VALUE"] != 1):
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.store",
        "main",
        [
            "SEF_MODE"             => "Y",
            "SEF_FOLDER"           => "/contacts/",
            "CACHE_TYPE"           => "A",
            "CACHE_TIME"           => "3600000",
            "CACHE_GROUPS"         => "N",
            "FILTER_NAME"          => "arRegionality",
            "PHONE"                => "Y",
            "SCHEDULE"             => "Y",
            "SET_TITLE"            => "Y",
            "TITLE"                => "",
            "COMPONENT_TEMPLATE"   => "main",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "MAP_TYPE"             => "0",
            "SEF_URL_TEMPLATES"    => [
                "liststores" => "stores/",
                "element"    => "stores/#store_id#/",
            ],
        ],
        false
    );
else:
    $APPLICATION->IncludeComponent(
        "bitrix:news",
        "contacts",
        [
            "IBLOCK_TYPE"                     => "aspro_lite_content",
            "IBLOCK_ID"                       => "12",
            "NEWS_COUNT"                      => "999",
            "USE_SEARCH"                      => "N",
            "USE_RSS"                         => "Y",
            "USE_RATING"                      => "N",
            "USE_CATEGORIES"                  => "N",
            "USE_FILTER"                      => "Y",
            "SORT_BY1"                        => "SORT",
            "SORT_ORDER1"                     => "ASC",
            "SORT_BY2"                        => "ID",
            "SORT_ORDER2"                     => "ASC",
            "CHECK_DATES"                     => "Y",
            "SEF_MODE"                        => "Y",
            "SEF_FOLDER"                      => "/contacts/",
            "AJAX_MODE"                       => "N",
            "AJAX_OPTION_JUMP"                => "N",
            "AJAX_OPTION_STYLE"               => "Y",
            "AJAX_OPTION_HISTORY"             => "N",
            "CACHE_TYPE"                      => "A",
            "CACHE_TIME"                      => "36000000",
            "CACHE_FILTER"                    => "Y",
            "CACHE_GROUPS"                    => "N",
            "SET_TITLE"                       => "Y",
            "SET_STATUS_404"                  => "Y",
            "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
            "ADD_SECTIONS_CHAIN"              => "N",
            "USE_PERMISSIONS"                 => "N",
            "PREVIEW_TRUNCATE_LEN"            => "",
            "LIST_ACTIVE_DATE_FORMAT"         => "j F Y",
            "LIST_FIELD_CODE"                 => [
                0 => "NAME",
                1 => "PREVIEW_TEXT",
                2 => "PREVIEW_PICTURE",
                3 => "DETAIL_PICTURE",
                4 => "",
            ],
            "LIST_PROPERTY_CODE"              => [
                0 => "ADDRESS",
                1 => "METRO",
                2 => "PHONE",
                3 => "EMAIL",
                4 => "SCHEDULE",
                5 => "PAY_TYPE",
                6 => "MAP",
                7 => "",
            ],
            "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
            "DISPLAY_NAME"                    => "Y",
            "META_KEYWORDS"                   => "-",
            "META_DESCRIPTION"                => "-",
            "BROWSER_TITLE"                   => "-",
            "DETAIL_ACTIVE_DATE_FORMAT"       => "j F Y",
            "DETAIL_FIELD_CODE"               => [
                0 => "NAME",
                1 => "PREVIEW_TEXT",
                2 => "DETAIL_TEXT",
                3 => "DETAIL_PICTURE",
                4 => "DATE_ACTIVE_FROM",
                5 => "",
            ],
            "DETAIL_PROPERTY_CODE"            => [
                0 => "ADDRESS",
                1 => "METRO",
                2 => "PHONE",
                3 => "EMAIL",
                4 => "SCHEDULE",
                5 => "PAY_TYPE",
                6 => "MAP",
                7 => "MORE_PHOTOS",
                8 => "",
            ],
            "DETAIL_DISPLAY_TOP_PAGER"        => "N",
            "DETAIL_DISPLAY_BOTTOM_PAGER"     => "N",
            "DETAIL_PAGER_TITLE"              => "",
            "DETAIL_PAGER_TEMPLATE"           => "",
            "DETAIL_PAGER_SHOW_ALL"           => "N",
            "PAGER_TEMPLATE"                  => "main",
            "DISPLAY_TOP_PAGER"               => "N",
            "DISPLAY_BOTTOM_PAGER"            => "N",
            "PAGER_TITLE"                     => "",
            "PAGER_SHOW_ALWAYS"               => "N",
            "PAGER_DESC_NUMBERING"            => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL"                  => "N",
            "USE_SHARE"                       => "N",
            "USE_REVIEW"                      => "N",
            "ADD_ELEMENT_CHAIN"               => "Y",
            "SHOW_DETAIL_LINK"                => "Y",
            "COMPONENT_TEMPLATE"              => "contacts",
            "SET_LAST_MODIFIED"               => "Y",
            "DETAIL_SET_CANONICAL_URL"        => "N",
            "PAGER_BASE_LINK_ENABLE"          => "N",
            "SHOW_404"                        => "Y",
            "MESSAGE_404"                     => "",
            "COMPOSITE_FRAME_MODE"            => "A",
            "COMPOSITE_FRAME_TYPE"            => "AUTO",
            "INCLUDE_SUBSECTIONS"             => "Y",
            "FILTER_NAME"                     => "arFilterContacts",
            "FILTER_FIELD_CODE"               => [
                0 => "",
                1 => "",
            ],
            "FILTER_PROPERTY_CODE"            => [
                0 => "",
                1 => "",
            ],
            "DETAIL_STRICT_SECTION_CHECK"     => "N",
            "STRICT_SECTION_CHECK"            => "Y",
            "AJAX_OPTION_ADDITIONAL"          => "",
            "СHOOSE_REGION_TEXT"              => "",
            "FILE_404"                        => "",
            "SECTIONS_TYPE_VIEW"              => "FROM_MODULE",
            "SECTION_ELEMENTS_TYPE_VIEW"      => "list_elements_1",
            "ELEMENT_TYPE_VIEW"               => "element_1",
            "CHOOSE_REGION_TEXT"              => "",
            "NUM_NEWS"                        => "20",
            "NUM_DAYS"                        => "30",
            "YANDEX"                          => "N",
            "SEF_URL_TEMPLATES"               => [
                "news"        => "",
                "section"     => "stores/#SECTION_CODE#/",
                "detail"      => "stores/#SECTION_CODE#/#ELEMENT_ID#/",
                "rss"         => "rss/",
                "rss_section" => "#SECTION_ID#/rss/",
            ],
        ],
        false
    );

endif; ?>

    <div class="contacts__props">
        <h3>Реквизиты</h3>
        <ul>
            <li>
                Полное наименование:
                <strong>Индивидуальный предприниматель Соболев Игорь Петрович</strong>
            </li>
            <li>
                Сокращенное наименование:
                <strong>ИП Соболев Игорь Петрович</strong>
            </li>
            <li>
                Юридический адрес:
                <strong>664050, Иркутская область, г. Иркутск, ул. Байкальская, д. 346/12, кв. 3</strong>
            </li>
            <li>
                Почтовый адрес:
                <strong>664050, Иркутская область, г. Иркутск, а/я 23</strong>
            </li>
            <li>
                ИНН:
                <strong>032601942802</strong>
            </li>
            <li>
                ОГРНИП:
                <strong>319237500005809</strong>
            </li>
            <li>
                Расчетный счет:
                <strong>40802810703550000562</strong>
            </li>
            <li>
                ОКПО:
                <strong>0143298763</strong>
            </li>
            <li>
                Банк:
                <strong>Филиал "Центральный" Банка ВТБ (ПАО)</strong>
            </li>
            <li>
                БИК:
                <strong>044525411</strong>
            </li>
            <li>
                Корр. счет:
                <strong>30101810145250000411</strong>
            </li>
        </ul>
    </div>

<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");