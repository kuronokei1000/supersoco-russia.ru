<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;

?>
<?
$arExtensions = ['catalog', 'owl_carousel', 'bootstrap.lite', 'tabs'];
$arExtensionsMobile = ['counter'];

\TSolution\Extensions::init($arExtensions);
\TSolution\ExtensionsMobile::init($arExtensionsMobile);

$APPLICATION->AddHeadString('<link href="'.SITE_TEMPLATE_PATH.'/../aspro-lite/css/owl-styles.css"'.' type="text/css" rel="stylesheet" />');
?>