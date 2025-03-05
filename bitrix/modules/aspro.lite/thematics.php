<?php
/**
 * Aspro:Lite module thematics
 * @copyright 2022 Aspro
 */

IncludeModuleLangFile(__FILE__);
$moduleClass = 'CLite';

// initialize module parametrs list and default values
$moduleClass::$arThematicsList = array(
	'SHOP' => array(
		'SALE' => true,
		'CODE' => 'SHOP',
		'TITLE' => GetMessage('THEMATIC_SHOP_TITLE'),
		'DESCRIPTION' => GetMessage('THEMATIC_SHOP_DESCRIPTION'),
		'PREVIEW_PICTURE' => '/bitrix/images/aspro.lite/themes/thematic_preview_shop.png',
		'URL' => 'https://lite-demo.ru/',
		'OPTIONS' => array(
		),
		'PRESETS' => array(
			'DEFAULT' => 894,
			'LIST' => array(
				0 => 894,
				1 => 319,
				2 => 382,
				3 => 663,
				4 => 748,
			),
		),
	),
	'START' => array(
		'SALE' => false,
		'CODE' => 'START',
		'TITLE' => GetMessage('THEMATIC_START_TITLE'),
		'DESCRIPTION' => GetMessage('THEMATIC_START_DESCRIPTION'),
		'PREVIEW_PICTURE' => '/bitrix/images/aspro.lite/themes/thematic_preview_start.png',
		'URL' => 'https://start.lite-demo.ru/',
		'OPTIONS' => array(
		),
		'PRESETS' => array(
			'DEFAULT' => 923,
			'LIST' => array(
				0 => 923,
				1 => 836,
				2 => 757,
				3 => 607,
			),
		),
	),
);