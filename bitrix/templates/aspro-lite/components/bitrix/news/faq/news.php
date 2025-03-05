<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

$arItemFilter = \TSolution::GetIBlockAllElementsFilter($arParams);
$itemsCnt = \TSolution\Cache::CIblockElement_GetList(['CACHE' => ['TAG' => \TSolution\Cache::GetIBlockCacheTag($arParams['IBLOCK_ID'])]], $arItemFilter, []);
?>

<? if (!$itemsCnt): ?>
	<div class="alert alert-warning"><?= Loc::getMessage('FAQ__SECTION_EMPTY') ?></div>
<? else : ?>
	<?\TSolution::CheckComponentTemplatePageBlocksParams($arParams, __DIR__);?>
	<?global $arTheme;?>
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["FAQS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	<?if($arParams["SHOW_ASK_QUESTION_BLOCK"] == "Y"){
		include('include_bottom_block.php');
	}?>	
<? endif ?>

