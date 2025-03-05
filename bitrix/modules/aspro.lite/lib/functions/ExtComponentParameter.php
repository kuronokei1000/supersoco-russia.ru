<?php

namespace Aspro\Lite\Functions;

use Bitrix\Main\Localization\Loc,
	CLite as Solution;

Loc::loadMessages(__FILE__);

class ExtComponentParameter
{
	const IBLOCK_TYPE_CONTENT = 'aspro_lite_content';
	const IBLOCK_TYPE_CATALOG = 'aspro_lite_catalog';

	const PARENT_GROUP_BASE = 'BASE';
	const PARENT_GROUP_DETAIL = 'DETAIL_SETTINGS';
	const PARENT_GROUP_ADDITIONAL = 'ADDITIONAL_SETTINGS';
	const PARENT_GROUP_LIST = 'LIST_SETTINGS';
	const PARENT_GROUP_DATA_SOURCE = 'DATA_SOURCE';

	const RELATION_BLOCK_TABS = 'tabs';
	const RELATION_BLOCK_DESC = 'desc';
	const RELATION_BLOCK_CHAR = 'char';
	const RELATION_BLOCK_TIZERS = 'tizers';
	const RELATION_BLOCK_GALLERY = 'big_gallery';
	const RELATION_BLOCK_TOP_GALLERY = 'top_gallery';
	const RELATION_BLOCK_VIDEO = 'video';
	const RELATION_BLOCK_DOCS = 'docs';
	const RELATION_BLOCK_REVIEWS = 'reviews';
	const RELATION_BLOCK_FAQ = 'faq';
	const RELATION_BLOCK_VACANCY = 'vacancy';
	const RELATION_BLOCK_STAFF = 'staff';
	const RELATION_BLOCK_SALE = 'sale';
	const RELATION_BLOCK_NEWS = 'news';
	const RELATION_BLOCK_ARTICLES = 'articles';
	const RELATION_BLOCK_SERVICES = 'services';
	const RELATION_BLOCK_BUY_SERVICES = 'buy_services';
	const RELATION_BLOCK_PROJECTS = 'projects';
	const RELATION_BLOCK_BRANDS = 'brands';
	const RELATION_BLOCK_PARTNERS = 'partners';
	const RELATION_BLOCK_GOODS = 'goods';
	const RELATION_BLOCK_LINK_GOODS = 'link_goods';
	const RELATION_BLOCK_LINK_SECTIONS = 'link_sections';
	const RELATION_BLOCK_BUY = 'buy';
	const RELATION_BLOCK_PAYMENT = 'payment';
	const RELATION_BLOCK_DELIVERY = 'delivery';
	const RELATION_BLOCK_DOPS = 'dops';
	const RELATION_BLOCK_COMMENTS = 'comments';
	const RELATION_BLOCK_COMPLECT = 'complect';
	const RELATION_BLOCK_KIT = 'kit';
	const RELATION_BLOCK_SKU = 'sku';
	const RELATION_BLOCK_TARIFFS = 'tariffs';
	const RELATION_BLOCK_ASSOCIATED = 'associated';
	const RELATION_BLOCK_EXPANDABLES = 'expandables';
	const RELATION_BLOCK_GIFT = 'gift';
	const RELATION_BLOCK_RECOMS = 'recoms';

	const ORDER_BLOCK_TABS = 'tabs';
	const ORDER_BLOCK_DESC = 'desc';
	const ORDER_BLOCK_CHAR = 'char';
	const ORDER_BLOCK_TIZERS = 'tizers';
	const ORDER_BLOCK_GALLERY = 'big_gallery';
	const ORDER_BLOCK_VIDEO = 'video';
	const ORDER_BLOCK_DOCS = 'docs';
	const ORDER_BLOCK_REVIEWS = 'reviews';
	const ORDER_BLOCK_FAQ = 'faq';
	const ORDER_BLOCK_VACANCY = 'vacancy';
	const ORDER_BLOCK_STAFF = 'staff';
	const ORDER_BLOCK_SALE = 'sale';
	const ORDER_BLOCK_NEWS = 'news';
	const ORDER_BLOCK_ARTICLES = 'articles';
	const ORDER_BLOCK_SERVICES = 'services';
	const ORDER_BLOCK_BUY_SERVICES = 'buy_services';
	const ORDER_BLOCK_PROJECTS = 'projects';
	const ORDER_BLOCK_BRANDS = 'brands';
	const ORDER_BLOCK_PARTNERS = 'partners';
	const ORDER_BLOCK_GOODS = 'goods';
	const ORDER_BLOCK_LINK_GOODS = 'link_goods';
	const ORDER_BLOCK_LINK_SECTIONS = 'link_sections';
	const ORDER_BLOCK_BUY = 'buy';
	const ORDER_BLOCK_PAYMENT = 'payment';
	const ORDER_BLOCK_DELIVERY = 'delivery';
	const ORDER_BLOCK_DOPS = 'dops';
	const ORDER_BLOCK_COMMENTS = 'comments';
	const ORDER_BLOCK_COMPLECT = 'complect';
	const ORDER_BLOCK_KIT = 'kit';
	const ORDER_BLOCK_ORDER_SALE = 'order_sale';
	const ORDER_BLOCK_ORDER_FORM = 'order_form';
	const ORDER_BLOCK_MAP = 'map';
	const ORDER_BLOCK_LANDINGS = 'landings';
	const ORDER_BLOCK_LIST_GOODS = 'list_goods';
	const ORDER_BLOCK_SKU = 'sku';
	const ORDER_BLOCK_TARIFFS = 'tariffs';
	const ORDER_BLOCK_ASSOCIATED = 'associated';
	const ORDER_BLOCK_EXPANDABLES = 'expandables';
	const ORDER_BLOCK_GIFT = 'gift';
	const ORDER_BLOCK_RECOMS = 'recoms';

	const GALLERY_TYPE_SMALL = 'SMALL';
	const GALLERY_TYPE_BIG = 'BIG';

	protected static $absolutePath = '';
	protected static $currentValues = [];

	protected static $baseParams = [];
	protected static $relationBlockParams = [];

	protected static $orderBlockParams = [];
	protected static $orderTabParams = [];
	protected static $orderAllParams = [];
	protected static $useTabParam = false;

	protected static $listCatalogIblocks = [];
	protected static $listContentIblocks = [];

	protected static $listProperties = [];
	protected static $listFileProperties = [];

	private static $registeredBlocks = [];

	private static function getCodeRelationBlocks()
	{
		$blocks = [
			self::RELATION_BLOCK_SKU,
			self::RELATION_BLOCK_TARIFFS,
			self::RELATION_BLOCK_DESC,
			self::RELATION_BLOCK_CHAR,
			self::RELATION_BLOCK_TIZERS,
			self::RELATION_BLOCK_GALLERY,
			self::RELATION_BLOCK_TOP_GALLERY,
			self::RELATION_BLOCK_VIDEO,
			self::RELATION_BLOCK_DOCS,
			self::RELATION_BLOCK_REVIEWS,
			self::RELATION_BLOCK_FAQ,
			self::RELATION_BLOCK_VACANCY,
			self::RELATION_BLOCK_STAFF,
			self::RELATION_BLOCK_SALE,
			self::RELATION_BLOCK_NEWS,
			self::RELATION_BLOCK_ARTICLES,
			self::RELATION_BLOCK_SERVICES,
			self::RELATION_BLOCK_PROJECTS,
			self::RELATION_BLOCK_BRANDS,
			self::RELATION_BLOCK_PARTNERS,
			self::RELATION_BLOCK_GOODS,
			self::RELATION_BLOCK_ASSOCIATED,
			self::RELATION_BLOCK_EXPANDABLES,
			self::RELATION_BLOCK_LINK_GOODS,
			self::RELATION_BLOCK_LINK_SECTIONS,
			self::RELATION_BLOCK_BUY,
			self::RELATION_BLOCK_PAYMENT,
			self::RELATION_BLOCK_DELIVERY,
			self::RELATION_BLOCK_DOPS,
			self::RELATION_BLOCK_COMMENTS,
			self::RELATION_BLOCK_COMPLECT,
			self::RELATION_BLOCK_KIT,
			self::RELATION_BLOCK_GIFT,
		];

		if (Solution::isSaleMode()) {
			$blocks = array_merge(
				$blocks,
				[
					self::RELATION_BLOCK_BUY_SERVICES,
				]
			);

			if (
				isset(self::$currentValues['USE_BIG_DATA']) &&
				self::$currentValues['USE_BIG_DATA'] === 'Y'
			) {
				$blocks = array_merge(
					$blocks,
					[
						self::RELATION_BLOCK_RECOMS,
					]
				);
			}
		}

		return $blocks;
	}

	private static function getCodeOrderBlocks()
	{
		$blocks =  [
			self::ORDER_BLOCK_SKU,
			self::ORDER_BLOCK_TARIFFS,
			self::ORDER_BLOCK_ORDER_SALE,
			self::ORDER_BLOCK_ORDER_FORM,
			self::ORDER_BLOCK_TABS,
			self::ORDER_BLOCK_DESC,
			self::ORDER_BLOCK_CHAR,
			self::ORDER_BLOCK_TIZERS,
			self::ORDER_BLOCK_GALLERY,
			self::ORDER_BLOCK_VIDEO,
			self::ORDER_BLOCK_DOCS,
			self::ORDER_BLOCK_REVIEWS,
			self::ORDER_BLOCK_FAQ,
			self::ORDER_BLOCK_VACANCY,
			self::ORDER_BLOCK_STAFF,
			self::ORDER_BLOCK_SALE,
			self::ORDER_BLOCK_NEWS,
			self::ORDER_BLOCK_ARTICLES,
			self::ORDER_BLOCK_SERVICES,
			self::ORDER_BLOCK_BUY_SERVICES,
			self::ORDER_BLOCK_PROJECTS,
			self::ORDER_BLOCK_BRANDS,
			self::ORDER_BLOCK_PARTNERS,
			self::ORDER_BLOCK_GOODS,
			self::ORDER_BLOCK_ASSOCIATED,
			self::ORDER_BLOCK_EXPANDABLES,
			self::ORDER_BLOCK_LINK_GOODS,
			self::ORDER_BLOCK_LINK_SECTIONS,
			self::ORDER_BLOCK_BUY,
			self::ORDER_BLOCK_PAYMENT,
			self::ORDER_BLOCK_DELIVERY,
			self::ORDER_BLOCK_MAP,
			self::ORDER_BLOCK_LANDINGS,
			self::ORDER_BLOCK_LIST_GOODS,
			self::ORDER_BLOCK_DOPS,
			self::ORDER_BLOCK_COMMENTS,
			self::ORDER_BLOCK_COMPLECT,
			self::ORDER_BLOCK_KIT,
			self::ORDER_BLOCK_GIFT,
		];

		if (Solution::isSaleMode()) {
			$blocks = array_merge(
				$blocks,
				[
					self::ORDER_BLOCK_BUY_SERVICES,
				]
			);

			if (
				isset(self::$currentValues['USE_BIG_DATA']) &&
				self::$currentValues['USE_BIG_DATA'] === 'Y'
			) {
				$blocks = array_merge(
					$blocks,
					[
						self::ORDER_BLOCK_RECOMS,
					]
				);
			}
		}

		return $blocks;
	}

	public static function init($absolutePath = '', $currentValues = [])
	{
		if ($absolutePath) {
			self::setAbsolutePath($absolutePath);
		}
		if ($currentValues) {
			self::setCurrentValues($currentValues);
		}

		self::loadProperties();
		self::loadIblocks();
	}

	public static function setAbsolutePath($path)
	{
		self::$absolutePath = $path;
	}

	public static function getAbsolutePath()
	{
		return self::$absolutePath;
	}

	public static function setCurrentValues($values)
	{
		self::$currentValues = $values;
	}

	public static function hasCurrentValue($code)
	{
		if (isset(self::$currentValues[$code]) && self::$currentValues[$code]) {
			return true;
		}

		return false;
	}

	public static function getCurrentValues($code = '')
	{
		if ($code) {
			if (isset(self::$currentValues[$code]) && self::$currentValues[$code]) {
				return self::$currentValues[$code];
			} else {
				return null;
			}
		}

		return self::$currentValues;
	}

	public static function appendTo(&$templateParameters)
	{
		if (self::$baseParams) {
			$templateParameters = array_merge($templateParameters, self::$baseParams);
		}

		if (self::$relationBlockParams) {
			$templateParameters = array_merge($templateParameters, self::$relationBlockParams);
		}

		$bAppendOrderBlockParams = $bAppendTabOrderBlockParams = $bAppendAllOrderBlockParams = true;

		if (array_key_exists('USE_DETAIL_TABS', self::$baseParams)) {
			$moduleParamValue = \CLite::GetFrontParametrValue(self::$useTabParam, self::getSiteId(), true);

			if (array_key_exists('USE_DETAIL_TABS', self::$currentValues)) {
				if (self::$currentValues['USE_DETAIL_TABS'] === 'Y') {
					$bUseDetailTabs = true;
				}
				elseif (self::$currentValues['USE_DETAIL_TABS'] === 'N') {
					$bUseDetailTabs = false;
				}
				else {
					$bUseDetailTabs = $moduleParamValue !== 'N';
				}
			} else {
				$bUseDetailTabs = $moduleParamValue !== 'N';
			}

			if ($bUseDetailTabs) {
				$bAppendAllOrderBlockParams = false;
			} else {
				$bAppendOrderBlockParams = $bAppendTabOrderBlockParams = false;
			}
		}

		if ($bAppendOrderBlockParams) {
			if (self::$orderBlockParams) {
				$templateParameters['DETAIL_BLOCKS_ORDER'] = self::$orderBlockParams;
			} elseif (self::$registeredBlocks && !self::$orderAllParams) {
				self::addOrderBlockParameters(array_unique(self::$registeredBlocks));
				$templateParameters['DETAIL_BLOCKS_ORDER'] = self::$orderBlockParams;
			}
		}

		if ($bAppendTabOrderBlockParams) {
			if (self::$orderTabParams) {
				$templateParameters['DETAIL_BLOCKS_TAB_ORDER'] = self::$orderTabParams;
			}
		}

		if ($bAppendAllOrderBlockParams) {
			if (self::$orderAllParams) {
				$templateParameters['DETAIL_BLOCKS_ALL_ORDER'] = self::$orderAllParams;
			}
		}
	}

	/**
	 * @param array $additionals
	 *
	 * example
	 * $additionals = [
	 *   [
	 *     'EXT_PARAMS' => ['SECTION' => 'CATALOG_PAGE', 'OPTION' => 'SECTIONS_TYPE_VIEW_CATALOG'],
	 *     'LIST_PARAMS' => 'SECTIONS_TYPE_VIEW',
	 *   ],
	 *   [
	 *     ['SECTION' => 'CATALOG_PAGE', 'OPTION' => 'SECTION_TYPE_VIEW_CATALOG'],
	 *     'SECTION_TYPE_VIEW',
	 *   ],
	 * ]
	 */
	public static function addBaseParameters(array $additionals = [])
	{
		$pageBlocks = \CLite::GetComponentTemplatePageBlocks(self::getAbsolutePath());
		$pageBlocksParams = \CLite::GetComponentTemplatePageBlocksParams($pageBlocks);
		\CLite::AddComponentTemplateModulePageBlocksParams(self::getAbsolutePath(), $pageBlocksParams);

		if ($additionals) {
			foreach ($additionals as $param) {
				$extParams = $param['EXT_PARAMS'] ?: array_shift($param);
				$listParams = $param['LIST_PARAMS'] ?: array_shift($param);

				\CLite::AddComponentTemplateModulePageBlocksParams(self::getAbsolutePath(), $pageBlocksParams, $extParams, $listParams);
			}
		}

		self::$baseParams = $pageBlocksParams;
	}

	/**
	 * @param array $relationBlockCodes
	 * @param null $parentGroup
	 * @param int $sort
	 *
	 * example
	 * $relationBlockCodes = [
	 *   ExtComponentParameter::RELATION_BLOCK_FAQ,
	 *   [
	 *     ExtComponentParameter::RELATION_BLOCK_GALLERY,
	 *     'overrideParams' => [
	 *         'GALLERY_PROP_CODE' => [
	 *             'DEFAULT' => 'GALLERY_BIG'
	 *         ]
	 *     ]
	 *   ],
	 *   ExtComponentParameter::RELATION_BLOCK_SALE,
	 *   ExtComponentParameter::RELATION_BLOCK_NEWS,
	 *   ExtComponentParameter::RELATION_BLOCK_SERVICES,
	 *   ExtComponentParameter::RELATION_BLOCK_REVIEWS,
	 *   ExtComponentParameter::RELATION_BLOCK_PROJECTS,
	 *   ExtComponentParameter::RELATION_BLOCK_STAFF,
	 *   ExtComponentParameter::RELATION_BLOCK_GOODS,
	 *   ExtComponentParameter::RELATION_BLOCK_DOCS,
	 *   ExtComponentParameter::RELATION_BLOCK_COMMENTS,
	 * ]
	 *
	 * $parentGroup = ExtComponentParameter::PARENT_GROUP_ADDITIONAL (default ExtComponentParameter::PARENT_GROUP_DETAIL)
	 *
	 * $sort = 500 (default 700)
	 */
	public static function addRelationBlockParameters(array $relationBlockCodes, $parentGroup = null, $sort = 700)
	{
		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		foreach ($relationBlockCodes as $relationblock) {
			$overrideParams = [];

			if (is_array($relationblock)) {
				$block = array_shift($relationblock);
				$additionalParams = array_key_exists('additionalParams', $relationblock) ? $relationblock['additionalParams'] : array();
				$overrideParams = array_key_exists('overrideParams', $relationblock) ? $relationblock['overrideParams'] : array();
			} else {
				$block = $relationblock;
				$additionalParams = $overrideParams = array();
			}

			if (!in_array($block, self::getCodeRelationBlocks())) {
				continue;
			}

			if ($additionalParams) {
				if (array_key_exists('toggle', $additionalParams)) {
					self::attachToggleParameterBlock($block, $parentGroup, $sort, $additionalParams);

					if (!self::isBlockToggledOn($block, boolval($additionalParams['toggle']))) {
						continue;
					}
				}

				if (array_key_exists('type', $additionalParams)) {
					self::attachTypeParameterBlock($block, $parentGroup, $sort, $additionalParams);
				}
			}

			if (in_array($block, [self::RELATION_BLOCK_DOCS])) {
				self::attachAlterTextParameterBlock($block, $parentGroup, $sort, $overrideParams);
				self::attachDocParameterBlock($block, $parentGroup, $sort, $overrideParams);

				continue;
			}

			if (in_array($block, [self::RELATION_BLOCK_GALLERY])) {
				self::attachAlterTextParameterBlock($block, $parentGroup, $sort, $overrideParams);
				self::attachGalleryParameterBlock($block, $parentGroup, $sort, $overrideParams);

				continue;
			}

			if (in_array($block, [self::RELATION_BLOCK_TOP_GALLERY])) {
				// self::attachAlterTextParameterBlock($block, $parentGroup, $sort, $overrideParams);
				self::attachGalleryParameterBlock($block, $parentGroup, $sort, $overrideParams);

				continue;
			}

			if (in_array($block, [self::RELATION_BLOCK_LINK_GOODS])) {
				self::attachLinkParameterBlock($block, $parentGroup, $sort, $overrideParams);

				continue;
			}

			if (in_array($block, [self::RELATION_BLOCK_COMMENTS])) {
				self::attachCommentParameterBlock($block, $parentGroup, $sort, $overrideParams);

				continue;
			}

			if (in_array($block, [self::RELATION_BLOCK_BUY_SERVICES])) {
				self::attachAlterTextParameterBlock($block, $parentGroup, $sort, $overrideParams);
				self::attachBuyServicesParameterBlock($block, $parentGroup, $sort, $overrideParams);

				continue;
			}

			self::attachAlterTextParameterBlock($block, $parentGroup, $sort, $overrideParams);
		}
	}

	/**
	 * @param array $orderBlockCodes
	 * @param null $parentGroup
	 * @param int $sort
	 *
	 * example
	 * $orderBlockCodes = [
	 *   ExtComponentParameter::ORDER_BLOCK_FAQ,
	 *   ExtComponentParameter::ORDER_BLOCK_GALLERY,
	 *   ExtComponentParameter::ORDER_BLOCK_SALE,
	 *   ExtComponentParameter::ORDER_BLOCK_NEWS,
	 *   ExtComponentParameter::ORDER_BLOCK_SERVICES,
	 *   ExtComponentParameter::ORDER_BLOCK_REVIEWS,
	 *   ExtComponentParameter::ORDER_BLOCK_PROJECTS,
	 *   ExtComponentParameter::ORDER_BLOCK_STAFF,
	 *   ExtComponentParameter::ORDER_BLOCK_GOODS,
	 *   ExtComponentParameter::ORDER_BLOCK_DOCS,
	 *   ExtComponentParameter::ORDER_BLOCK_COMMENTS,
	 * ]
	 *
	 * $parentGroup = ExtComponentParameter::PARENT_GROUP_ADDITIONAL (default ExtComponentParameter::PARENT_GROUP_DETAIL)
	 *
	 * $sort = 500 (default 700)
	 *
	 */
	public static function addOrderBlockParameters($orderBlockCodes, $parentGroup = null, $sort = 700)
	{
		if (!is_array($orderBlockCodes)) {
			$orderBlockCodes = explode(',', $orderBlockCodes);
		}

		\CBitrixComponent::includeComponentClass('bitrix:catalog.section');

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		foreach ($orderBlockCodes as $code) {
			if (!in_array($code, self::getCodeOrderBlocks())) {
				continue;
			}

			$upperCode = mb_strtoupper($code);
			$data[$code] = Loc::getMessage('ASPRO__ECP__ORDER_BLOCK__' . $upperCode) ?: $upperCode;
		}

		self::$orderBlockParams = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__BLOCK_DISPLAY_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => \Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/aspro.lite/settings/dragdrop_order/script.min.js'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => \Bitrix\Main\Web\Json::encode($data),
			'DEFAULT' => implode(',', array_keys($data)),
		];
	}

	/**
	 * @param array $orderTabCodes
	 * @param null $parentGroup
	 * @param int $sort
	 *
	 * example
	 * $orderTabCodes = [
	 *   ExtComponentParameter::ORDER_BLOCK_FAQ,
	 *   ExtComponentParameter::ORDER_BLOCK_GALLERY,
	 *   ExtComponentParameter::ORDER_BLOCK_SALE,
	 *   ExtComponentParameter::ORDER_BLOCK_NEWS,
	 *   ExtComponentParameter::ORDER_BLOCK_SERVICES,
	 *   ExtComponentParameter::ORDER_BLOCK_REVIEWS,
	 *   ExtComponentParameter::ORDER_BLOCK_PROJECTS,
	 *   ExtComponentParameter::ORDER_BLOCK_STAFF,
	 *   ExtComponentParameter::ORDER_BLOCK_GOODS,
	 *   ExtComponentParameter::ORDER_BLOCK_DOCS,
	 *   ExtComponentParameter::ORDER_BLOCK_COMMENTS,
	 * ]
	 *
	 * $parentGroup = ExtComponentParameter::PARENT_GROUP_ADDITIONAL (default ExtComponentParameter::PARENT_GROUP_DETAIL)
	 *
	 * $sort = 500 (default 700)
	 *
	 */
	public static function addOrderTabParameters($orderTabCodes, $parentGroup = null, $sort = 700, $useTabParam = false)
	{
		if (!is_array($orderTabCodes)) {
			$orderTabCodes = explode(',', $orderTabCodes);
		}

		\CBitrixComponent::includeComponentClass('bitrix:catalog.section');

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		foreach ($orderTabCodes as $code) {
			if (!in_array($code, self::getCodeOrderBlocks())) {
				continue;
			}

			$upperCode = mb_strtoupper($code);
			$data[$code] = Loc::getMessage('ASPRO__ECP__ORDER_BLOCK__' . $upperCode) ?: $upperCode;
		}

		self::$orderTabParams = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__BLOCKS_TAB_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => \Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/aspro.lite/settings/dragdrop_order/script.min.js'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => \Bitrix\Main\Web\Json::encode($data),
			'DEFAULT' => implode(',', array_keys($data)),
		];

		self::addUseTabParameter($useTabParam);
	}

	public static function addUseTabParameter($useTabParam)
	{
		if ($useTabParam) {
			self::$useTabParam = $useTabParam;

			self::$baseParams['USE_DETAIL_TABS'] = [
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('ASPRO__ECP__USE_DETAIL_TABS'),
				'TYPE' => 'LIST',
				'REFRESH' => 'Y',
				'VALUES' => array(
					'FROM_MODULE' => Loc::getMessage('ASPRO__ECP__USE_DETAIL_TABS__FROM_MODULE'),
					'Y' => Loc::getMessage('ASPRO__ECP__USE_DETAIL_TABS__YES'),
					'N' => Loc::getMessage('ASPRO__ECP__USE_DETAIL_TABS__NO'),
				),
				'DEFAULT' => 'FROM_MODULE',
				'SORT' => 2,
			];
		}
	}

	public static function addTextParameter($code, $arOptions = [])
	{
		$arDefaultOptions = [
			'PARENT' => self::PARENT_GROUP_DETAIL,
			'DEFAULT' => '',
			'NAME' => Loc::getMessage('ASPRO__TEXT_PARAM__'.$code),
			'SORT' => 2,
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		self::$baseParams[$code] = [
			'PARENT' => $arConfig['PARENT'],
			'NAME' => $arConfig['NAME'],
			'TYPE' => 'STRING',
			'DEFAULT' => $arConfig['DEFAULT'],
			'SORT' => $arConfig['SORT'],
		];
	}

	public static function addSelectParameter($code, $arOptions = [])
	{
		$arDefaultOptions = [
			'PARENT' => self::PARENT_GROUP_DETAIL,
			'MULTIPLE' => 'N',
			'REFRESH' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => [],
			'DEFAULT' => '',
			'NAME' => Loc::getMessage('ASPRO__SELECT_PARAM__'.$code),
			'SORT' => 2,
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		self::$baseParams[$code] = [
			'PARENT' => $arConfig['PARENT'],
			'NAME' => $arConfig['NAME'],
			'TYPE' => 'LIST',
			"MULTIPLE" => $arConfig['MULTIPLE'],
			"VALUES" => $arConfig['VALUES'],
			'DEFAULT' => $arConfig['DEFAULT'],
			'REFRESH' => $arConfig['REFRESH'],
			'ADDITIONAL_VALUES' => $arConfig['ADDITIONAL_VALUES'],
			'SORT' => $arConfig['SORT'],
		];
	}

	public static function addCheckboxParameter($code, $arOptions = [])
	{
		$arDefaultOptions = [
			'PARENT' => self::PARENT_GROUP_DETAIL,
			'REFRESH' => 'N',
			'DEFAULT' => '',
			'NAME' => Loc::getMessage('ASPRO__CHECKBOX_PARAM__'.$code),
			'SORT' => 2,
		];
		$arConfig = array_merge($arDefaultOptions, $arOptions);

		self::$baseParams[$code] = [
			'PARENT' => $arConfig['PARENT'],
			'NAME' => $arConfig['NAME'],
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => $arConfig['DEFAULT'],
			'REFRESH' => $arConfig['REFRESH'],
			'SORT' => $arConfig['SORT'],
		];
	}

	/**
	 * @param array $orderTabCodes
	 * @param null $parentGroup
	 * @param int $sort
	 *
	 * example
	 * $orderAllCodes = [
	 *   ExtComponentParameter::ORDER_BLOCK_FAQ,
	 *   ExtComponentParameter::ORDER_BLOCK_GALLERY,
	 *   ExtComponentParameter::ORDER_BLOCK_SALE,
	 *   ExtComponentParameter::ORDER_BLOCK_NEWS,
	 *   ExtComponentParameter::ORDER_BLOCK_SERVICES,
	 *   ExtComponentParameter::ORDER_BLOCK_REVIEWS,
	 *   ExtComponentParameter::ORDER_BLOCK_PROJECTS,
	 *   ExtComponentParameter::ORDER_BLOCK_STAFF,
	 *   ExtComponentParameter::ORDER_BLOCK_GOODS,
	 *   ExtComponentParameter::ORDER_BLOCK_DOCS,
	 *   ExtComponentParameter::ORDER_BLOCK_COMMENTS,
	 * ]
	 *
	 * $parentGroup = ExtComponentParameter::PARENT_GROUP_ADDITIONAL (default ExtComponentParameter::PARENT_GROUP_DETAIL)
	 *
	 * $sort = 500 (default 700)
	 *
	 */
	public static function addOrderAllParameters($orderAllCodes, $parentGroup = null, $sort = 700)
	{
		if (!is_array($orderAllCodes)) {
			$orderAllCodes = explode(',', $orderAllCodes);
		}

		\CBitrixComponent::includeComponentClass('bitrix:catalog.section');

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		foreach ($orderAllCodes as $code) {
			if (!in_array($code, self::getCodeOrderBlocks())) {
				continue;
			}

			$upperCode = mb_strtoupper($code);
			$data[$code] = Loc::getMessage('ASPRO__ECP__ORDER_BLOCK__' . $upperCode) ?: $upperCode;
		}

		self::$orderAllParams = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__ALL_DISPLAY_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => \Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/aspro.lite/settings/dragdrop_order/script.min.js'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => \Bitrix\Main\Web\Json::encode($data),
			'DEFAULT' => implode(',', array_keys($data)),
		];
	}

	public static function printPairParameters($lineBreakStr = '<br/>')
	{
		foreach (self::$relationBlockParams as $paramsCode => $paramValue) {
			echo '"' . $paramsCode . '" => $arParams["' . $paramsCode . '"],' . $lineBreakStr;
		}

		if (self::$orderBlockParams) {
			echo '"DETAIL_BLOCKS_ORDER" => $arParams["DETAIL_BLOCKS_ORDER"],' . $lineBreakStr;
		}
		if (self::$orderTabParams) {
			echo '"DETAIL_BLOCKS_TAB_ORDER" => $arParams["DETAIL_BLOCKS_TAB_ORDER"],' . $lineBreakStr;
		}
		if (self::$orderAllParams) {
			echo '"DETAIL_BLOCKS_ALL_ORDER" => $arParams["DETAIL_BLOCKS_ALL_ORDER"],' . $lineBreakStr;
		}
	}

	public static function attachAlterTextParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$relationBlockParams['T_' . $upperCode] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__ALT__' . $upperCode) ?: $upperCode,
			'TYPE' => 'TEXT',
			'DEFAULT' => '',
			'SORT' => $sort,
		];

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachIblockParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$relationBlockParams[$upperCode . '_IBLOCK_ID'] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__IBLOCK_' . $upperCode) ?: $upperCode,
			'TYPE' => 'LIST',
			'REFRESH' => 'N',
			'ADDITIONAL_VALUES' => 'Y',
			'VALUES' => $code == self::RELATION_BLOCK_GOODS ? self::getListCatalogIblocks() : self::getListContentIblocks(),
			'DEFAULT' => '-',
			'SORT' => $sort,
		];

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachDocParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$relationBlockParams[$upperCode . '_PROP_CODE'] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__PROPERTY_REL__' . $upperCode) ?: $upperCode,
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => 'DOCUMENTS',
			'VALUES' => self::getListFileIblockProperties(),
			'SORT' => $sort,
		];

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachGalleryParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$relationBlockParams[$upperCode . '_PROP_CODE'] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__PROPERTY_REL__' . $upperCode) ?: $upperCode,
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => 'PHOTOS',
			'VALUES' => self::getListFileIblockProperties(),
			'SORT' => $sort,
		];

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachCommentParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		if (!\Bitrix\Main\ModuleManager::isModuleInstalled('blog')) {
			return;
		}

		$relationBlockParams['DETAIL_USE_COMMENTS'] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__USE_COMMENTS'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
			'SORT' => $sort,
		];

		if (self::getCurrentValues('DETAIL_USE_COMMENTS') === 'Y') {
			/** blog */
			$relationBlockParams['DETAIL_BLOG_USE'] = [
				'PARENT' => $parentGroup,
				'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__BLOG_USE'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N',
				'REFRESH' => 'Y',
				'SORT' => $sort,
			];

			if (self::getCurrentValues('DETAIL_BLOG_USE') == 'Y') {
				$relationBlockParams['DETAIL_BLOG_URL'] = [
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__BLOG_URL'),
					'TYPE' => 'STRING',
					'DEFAULT' => 'catalog_comments'
				];

				$relationBlockParams['COMMENTS_COUNT'] = [
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__COMMENTS_COUNT'),
					'TYPE' => 'STRING',
					'DEFAULT' => '5'
				];

				$relationBlockParams['DETAIL_BLOG_TITLE'] = [
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__TITLE_TAB'),
					'TYPE' => 'STRING',
					'DEFAULT' => Loc::getMessage('ASPRO__ECP__BLOG__COMMENTS_VALUE'),
				];

				$relationBlockParams['DETAIL_BLOG_EMAIL_NOTIFY'] = [
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__EMAIL_NOTIFY'),
					'TYPE' => 'CHECKBOX',
					'DEFAULT' => 'N',
				];
			}

			/** vk */
			$relationBlockParams['DETAIL_VK_USE'] = array(
				'PARENT' => $parentGroup,
				'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__VK_USE'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N',
				'REFRESH' => 'Y'
			);

			if (self::getCurrentValues('DETAIL_VK_USE') === 'Y') {
				$relationBlockParams['DETAIL_VK_TITLE'] = array(
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__VK_TITLE_TAB'),
					'TYPE' => 'STRING',
					'DEFAULT' => Loc::getMessage('ASPRO__ECP__BLOG__VK_VALUE'),
				);

				$relationBlockParams['DETAIL_VK_API_ID'] = array(
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__VK_API_ID'),
					'TYPE' => 'STRING',
					'DEFAULT' => 'API_ID',
				);
			}

			/** fb */
			$relationBlockParams['DETAIL_FB_USE'] = array(
				'PARENT' => $parentGroup,
				'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__FB_USE'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N',
				'REFRESH' => 'Y'
			);

			if (self::getCurrentValues('DETAIL_FB_USE') === 'Y') {
				$relationBlockParams['DETAIL_FB_TITLE'] = array(
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__FB_TITLE_TAB'),
					'TYPE' => 'STRING',
					'DEFAULT' => Loc::getMessage('ASPRO__ECP__BLOG__FB_VALUE'),
				);

				$relationBlockParams['DETAIL_FB_APP_ID'] = array(
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__BLOG__FB_APP_ID'),
					'TYPE' => 'STRING',
					'DEFAULT' => 'APP_ID',
				);
			}
		}

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachBuyServicesParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$relationBlockParams['COUNT_SERVICES_IN_ANNOUNCE'] = array(
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__' . $upperCode . '_COUNT_IN_ANNOUNCE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '2',
			'REFRESH' => 'N'
		);

		$relationBlockParams['SHOW_ALL_SERVICES_IN_SLIDE'] = array(
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__' . $upperCode . '_SHOW_ALL_IN_SLIDE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'N'
		);

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachLinkParameterBlock($code, $parentGroup = '', $sort = 700, $overrideParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$relationBlockParams['SHOW_' . $upperCode] = array(
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__SHOW__' . $upperCode),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'REFRESH' => 'Y'
		);

		if (self::hasCurrentValue('SHOW_' . $upperCode) && self::getCurrentValues('SHOW_' . $upperCode) == 'Y') {
			$relationBlockParams['T_' . $upperCode] = [
				'PARENT' => $parentGroup,
				'NAME' => Loc::getMessage('ASPRO__ECP__ALT__' . $upperCode) ?: $upperCode,
				'TYPE' => 'TEXT',
				'DEFAULT' => '',
				'SORT' => $sort,
			];

			$relationBlockParams[$upperCode . '_IBLOCK_ID'] = [
				'PARENT' => $parentGroup,
				'NAME' => Loc::getMessage('ASPRO__ECP__IBLOCK_' . $upperCode) ?: $upperCode,
				'TYPE' => 'LIST',
				'REFRESH' => 'Y',
				'ADDITIONAL_VALUES' => 'Y',
				'VALUES' => self::getListCatalogIblocks(),
				'DEFAULT' => '-',
				'SORT' => $sort,
			];

			if (self::hasCurrentValue($upperCode . '_IBLOCK_ID') && self::getCurrentValues($upperCode . '_IBLOCK_ID') > 0) {
				$relationBlockParams[$upperCode . '_PROP_CODE'] = [
					'PARENT' => $parentGroup,
					'NAME' => Loc::getMessage('ASPRO__ECP__PROPERTY_REL__' . $upperCode) ?: $upperCode,
					'TYPE' => 'LIST',
					'MULTIPLE' => 'N',
					'ADDITIONAL_VALUES' => 'Y',
					'REFRESH' => 'N',
					'DEFAULT' => '-',
					'VALUES' => self::getListLinkIblockProperties(self::getCurrentValues($upperCode . '_IBLOCK_ID')),
					'SORT' => $sort,
				];
			}
		}

		if ($overrideParams) {
			self::overrideProperties($relationBlockParams, $overrideParams);
		}

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	public static function attachToggleParameterBlock($code, $parentGroup = '', $sort = 700, $additionalParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		$defaultValue = $additionalParams['toggle'] ? 'Y' : 'N';

		$relationBlockParams['SHOW_' . $upperCode] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__SHOW_' . $upperCode),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => $defaultValue,
			'REFRESH' => 'Y',
			'SORT' => $sort,
		];

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	protected static function isBlockToggledOn($code, bool $defaultValue = true)
	{
		$upperCode = mb_strtoupper($code);

		if (self::hasCurrentValue('SHOW_' . $upperCode)) {
			return self::getCurrentValues('SHOW_' . $upperCode) === 'Y';
		} else {
			return $defaultValue;
		}
	}

	public static function attachTypeParameterBlock($code, $parentGroup = '', $sort = 700, $additionalParams = [])
	{
		$upperCode = mb_strtoupper($code);

		if (!$parentGroup) {
			$parentGroup = self::PARENT_GROUP_DETAIL;
		}

		if (!is_array($additionalParams['type'])) {
			$additionalParams['type'] = array($additionalParams['type']);
		}

		$defaultValue = reset($additionalParams['type']);

		$arValues = array();
		foreach($additionalParams['type'] as $type){
			$arValues[$type] = Loc::getMessage('ASPRO__ECP__TYPE_' . $upperCode . '__' . $type);
		}

		$relationBlockParams['TYPE_' . $upperCode] = [
			'PARENT' => $parentGroup,
			'NAME' => Loc::getMessage('ASPRO__ECP__TYPE_' . $upperCode),
			'TYPE' => 'LIST',
			'DEFAULT' => $defaultValue,
			'SORT' => $sort,
			'VALUES' => $arValues,
		];

		self::$relationBlockParams = array_merge(self::$relationBlockParams, $relationBlockParams);

		self::$registeredBlocks[] = $code;
	}

	protected static function getListIblockProperties($isRequired = false)
	{
		return self::$listProperties;
	}

	protected static function getListFileIblockProperties($isRequired = false)
	{
		return self::$listFileProperties;
	}

	protected static function getListLinkIblockProperties($iblockId, $filter = [])
	{
		$properties = self::getIblockProperties($iblockId, $filter);

		$result = [];
		$result['-'] = '—';

		foreach ($properties as $property) {
			$propertyCode = (string)$property['CODE'];
			$propertyName = '[' . $propertyCode . '] ' . $property['NAME'];

			if ($property['MULTIPLE'] == 'Y'
				|| $property['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_LIST
				|| ($property['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT && (int)$property['LINK_IBLOCK_ID'] > 0)) {
				$result[$propertyCode] = $propertyName;
			}
		}

		return $result;
	}

	protected static function getListCatalogIblocks($isRequired = false)
	{
		return self::$listCatalogIblocks;
	}

	protected static function getListContentIblocks($isRequired = false)
	{
		return self::$listContentIblocks;
	}

	private static function loadProperties()
	{
		if (!self::getCurrentValues()) {
			return;
		}

		$properties = self::getIblockProperties(self::getCurrentValues('IBLOCK_ID'));

		self::$listFileProperties['-'] = '—';
		self::$listProperties['-'] = '—';

		foreach ($properties as $property) {
			$propertyCode = (string)$property['CODE'];
			$propertyName = '[' . $propertyCode . '] ' . $property['NAME'];

			if ($property['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_FILE) {
				self::$listFileProperties[$propertyCode] = $propertyName;
			}

			if ($property['MULTIPLE'] == 'Y'
				|| $property['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_LIST
				|| ($property['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT && (int)$property['LINK_IBLOCK_ID'] > 0)) {
				self::$listProperties[$propertyCode] = $propertyName;
			}
		}
	}

	private static function loadIblocks()
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		
		$iblocks = \Bitrix\Iblock\IblockTable::getList(array_filter([
			'select' => [
				'ID',
				'NAME',
				'IBLOCK_TYPE_ID',
			],
			'order' => [
				'LID' => 'ASC',
				'NAME' => 'ASC',
				'SORT' => 'DESC',
			],
			'filter' => [
				'IBLOCK_TYPE_ID' => [
					self::IBLOCK_TYPE_CATALOG,
					self::IBLOCK_TYPE_CONTENT,
				],
				'ACTIVE' => 'Y',
			],
		]))->fetchAll();

		foreach ($iblocks as $iblock) {
			$id = $iblock['ID'];
			$name = '[' . $iblock['ID'] . '] ' . $iblock['NAME'];

			switch ($iblock['IBLOCK_TYPE_ID']) {
				case self::IBLOCK_TYPE_CATALOG :
					self::$listCatalogIblocks[$iblock['ID']] = $name;
					break;
				case self::IBLOCK_TYPE_CONTENT :
					self::$listContentIblocks[$iblock['ID']] = $name;
					break;
			}
		}
	}

	private static function overrideProperties(&$currentProperties, $overrideProperties)
	{
		foreach ($overrideProperties as $overrideCode => $overrideProperty) {
			if (isset($currentProperties[$overrideCode])) {
				$currentProperties[$overrideCode] = array_merge($currentProperties[$overrideCode], $overrideProperty);
			}
		}
	}

	private static function getIblockProperties($iblockId, $filter = [])
	{
		$result = \Bitrix\Iblock\PropertyTable::getList(array(
			'select' => [
				'ID',
				'IBLOCK_ID',
				'NAME',
				'CODE',
				'PROPERTY_TYPE',
				'MULTIPLE',
				'LINK_IBLOCK_ID',
				'USER_TYPE',
				'SORT'
			],
			'filter' => array_merge([
				'=IBLOCK_ID' => $iblockId,
				'=ACTIVE' => 'Y'
			], $filter),
			'order' => [
				'SORT' => 'ASC',
				'NAME' => 'ASC'
			]
		))->fetchAll();

		return $result;
	}

	private static function getSiteId()
	{
		return isset($_REQUEST['src_site']) ? $_REQUEST['src_site'] : SITE_ID;
	}
}