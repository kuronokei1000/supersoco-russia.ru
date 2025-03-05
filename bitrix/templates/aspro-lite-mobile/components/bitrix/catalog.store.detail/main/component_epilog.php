<?
$arExtensions = ['contacts', 'swiper'];
\TSolution\Extensions::init($arExtensions);
\TSolution\ExtensionsMobile::init(['fancybox', 'map']);

$_SESSION['SHOP_TITLE'] = $arResult['ADDRESS'];
