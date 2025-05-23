<?
use CLite as Solution;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $arRegion, $arSite, $arTheme;

$arSite = CSite::GetByID(SITE_ID)->Fetch();
$bIncludedModule = \Bitrix\Main\Loader::includeModule('aspro.lite');

// 04.07.2024 [Roman Brovin] Рефакторинг вывода класса в тег html
$classes = array_filter([
    $_SESSION['SESS_INCLUDE_AREAS'] ? 'bx_editmode' : '',
    str_contains($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ? 'ie ie7' : '',
    str_contains($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') ? 'ie ie8' : '',
    str_contains($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0') ? 'ie ie9' : '',
]);

$classAttribute = !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '';


?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=$classAttribute?>>
<head>
    <?php
    // 19.03.2024 [Roman Brovin] Добавление канонической ссылки
    $canonicalUrl = getCanonicalUrl(str_replace('index.php', '', $APPLICATION->GetCurPage(true)));
    $APPLICATION->AddHeadString('<link rel="canonical" href="https://supersoco-russia.ru' . $canonicalUrl . '" />');
    ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowMeta("viewport"); ?>
    <? $APPLICATION->ShowMeta("HandheldFriendly"); ?>
    <? $APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes"); ?>
    <? $APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style"); ?>
    <? $APPLICATION->ShowMeta("SKYPE_TOOLBAR"); ?>
    <? $APPLICATION->ShowHead(); ?>
    <? $APPLICATION->AddHeadString('<script>BX.message(' . CUtil::PhpToJSObject($MESS, false) . ')</script>', true); ?>
    <? if ($bIncludedModule) {
        Solution::Start();
    } ?>

</head>
<body class="<?=($bIndexBot ? "wbot" : "")?> site_<?=SITE_ID?> <?=($bIncludedModule ? TSolution::getConditionClass(
) : '')?>" id="main" data-site="<?=SITE_DIR?>">
<div class="bx_areas"><? if ($bIncludedModule) {
        TSolution::ShowPageType('header_counter');
    } ?></div>

<? if (!$bIncludedModule): ?>
<? $APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_LITE_TITLE")); ?>
<? $APPLICATION->IncludeFile(SITE_DIR . "include/error_include_module.php"); ?></body>
</html>
<? die(); ?>
<? endif; ?>

<? @include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_DIR . 'include/header/body_top.php')); ?>

<? $arTheme = $APPLICATION->IncludeComponent("aspro:theme.lite", "", [], false, ['HIDE_ICONS' => 'Y']); ?>
<? @include_once('defines.php'); ?>
<? TSolution::SetJSOptions(); ?>

<div class="body <?=($isIndex ? 'index' : '')?> hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>">
    <div class="body_media"></div>

    <? TSolution::get_banners_position('TOP_HEADER'); ?>
    <div class="headers-container">
        <div class="title-v<?=$arTheme["PAGE_TITLE"]["VALUE"];?><?=($isIndex ? ' index' : '')?>"
             data-ajax-block="HEADER" data-ajax-callback="headerInit">
            <? TSolution::ShowPageType('mega_menu'); ?>
            <? TSolution::ShowPageType('header'); ?>
        </div>

        <? if ($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y'): ?>
            <div id="headerfixed">
                <? TSolution::ShowPageType('header_fixed'); ?>
            </div>
        <? endif; ?>

        <div id="mobileheader" class="visible-991">
            <? TSolution::ShowPageType('header_mobile'); ?>
            <div id="mobilemenu" class="mobile-scroll scrollbar">
                <? TSolution::ShowPageType('header_mobile_menu'); ?>
            </div>
        </div>
    </div>
    <div id="mobilefilter" class="scrollbar-filter"></div>
    <? TSolution::get_banners_position('TOP_UNDERHEADER'); ?>

    <div role="main" class="main banner-auto">
        <? if (!$isIndex && !$is404 && !$isForm): ?>
            <? $APPLICATION->ShowViewContent('section_bnr_content'); ?>
            <? if ($APPLICATION->GetProperty("HIDETITLE") !== 'Y'): ?>
                <!--title_content-->
                <? TSolution::ShowPageType('page_title'); ?>
                <!--end-title_content-->
            <? endif; ?>
            <? $APPLICATION->ShowViewContent('top_section_filter_content'); ?>
            <? $APPLICATION->ShowViewContent('top_detail_content'); ?>
        <? endif; // if !$isIndex && !$is404 && !$isForm?>

        <div class="container <?=($isCabinet ? 'cabinte-page' : '');?><?=($isBlog ? ' blog-page' : '');?> <?=TSolution::ShowPageProps(
            "ERROR_404"
        );?>">
            <? if (!$isIndex): ?>
            <div class="row">
                <div class="maxwidth-theme wide-<? TSolution::ShowPageProps("FULLWIDTH"); ?>">
                    <? if ($is404): ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 content-md">
                        <? else: ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 content-md">
                            <div class="right_block narrow_<?=TSolution::ShowPageProps(
                                "MENU"
                            );?> <?=$APPLICATION->ShowViewContent('right_block_class')?>">
                                <? TSolution::get_banners_position('CONTENT_TOP'); ?>

                                <? ob_start(); ?>
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    ".default",
                                    [
                                        "COMPONENT_TEMPLATE"  => ".default",
                                        "PATH"                => SITE_DIR . "include/left_block/menu.left_menu.php",
                                        "AREA_FILE_SHOW"      => "file",
                                        "AREA_FILE_SUFFIX"    => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE"       => "include_area.php",
                                    ],
                                    false
                                ); ?>
                                <? $sMenuContent = ob_get_contents();
                                ob_end_clean(); ?>
                                <? endif; ?>
                                <? endif; ?>
<? TSolution::checkRestartBuffer(); ?>