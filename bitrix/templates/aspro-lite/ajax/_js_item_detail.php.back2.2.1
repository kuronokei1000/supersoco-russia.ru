<?
use \Bitrix\Main\Loader;

include_once('const.php');

if(isset($_REQUEST['site_id'])) {
	$SITE_ID = htmlspecialchars($_REQUEST['site_id']);
	define('SITE_ID', $SITE_ID);
}
if(isset($_REQUEST['site_dir'])) {
	$SITE_DIR = htmlspecialchars($_REQUEST['site_dir']);
	define('SITE_DIR', $SITE_DIR);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

// need for solution class and variables
if (!include_once('../vendor/php/solution.php')) {
    return false;
}

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$arPost = $request->getPostList()->toArray();
$clearCache = $request->getQuery('clear_cache');

global $APPLICATION;
$arPost = $APPLICATION->ConvertCharsetArray($arPost, 'UTF-8', LANG_CHARSET);

$bDetail = $arPost["IS_DETAIL"] === 'Y';

$offerID = $arPost["SELECTED_OFFER_ID"];
$maiItemID = $arPost['ID'];

$arOffer = [];

if($arPost["PARAMS"]){

	$arPropsTmp = [];
	foreach ($arPost as $key => $value) {
		if (strpos($key, 'PROP_') !== false) {
			$arPropsTmp[$key] = $value;
		}
	}
	$arSelectedProps = json_encode($arPropsTmp);
    
    /*get info for current offer only*/
    if($offerID){

        global $USER;
        $USER_ID = $USER->GetID();
        $arUserGroups = $USER->GetUserGroupArray();

        if (!$arPost["PARAMS"]['ADD_PICT_PROP']) {
            $arPost["PARAMS"]['ADD_PICT_PROP'] = 'MORE_PHOTO';
        }

        $obSKU = new TSolution\SKU($arPost["PARAMS"]);

        // get currency params for prices
        $arCurrencyParams = [];
        if (TSolution::isSaleMode() && $arPost["PRICE_PARAMS"]["PRICE_CODE"]) {
            $arCurrencyParams = [];
            $bPriceVat = $obSKU->config['PRICE_VAT_INCLUDE'] === true || $obSKU->config['PRICE_VAT_INCLUDE'] === 'true';	
            if($obSKU->config['CONVERT_CURRENCY'] === 'Y'){
                $arCurrencyParams["CURRENCY_ID"] = $obSKU->config['CURRENCY_ID'];
            }
        }

        // get only need prop code
        $needProps = array_diff($obSKU->config['SKU_PROPERTY_CODE'], $arPost["PARAMS"]['SKU_TREE_PROPS'], ['']);

        // get only need params for cache
        $arCacheParams = [
            $arPost["PARAMS"]["STORES"],
            $arPost["PARAMS"]["USE_REGION"],
            $needProps,
            $arCurrencyParams,
        ];

        // cache
        $obCache = new CPHPCache();
        $cacheTag = "element_".$offerID;
        $cacheID = "getSKUjs".$cacheTag.md5(serialize(array_merge((array)($arPost["PARAMS"]["CACHE_GROUPS"]==="N"? false : $USER->GetGroups()), $arCacheParams)));
        $cachePath = "/CLiteCache/iblock/getSKUjs/".$cacheTag."/";
        $cacheTime = $arPost["PARAMS"]["CACHE_TIME"];

        if(isset($clearCache) && $clearCache === "Y" && $USER->IsAdmin()){
            TSolution\Cache::ClearSKUjsCache($offerID);
        }

        if( $obCache->InitCache($cacheTime, $cacheID, $cachePath))
        {
            $res = $obCache->GetVars();
            $arOffer = $res["arOffer"];
        }
        else
        {
            $arSelect = array("ID", "IBLOCK_ID", "NAME", 'SORT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE');
            /* select prices */
            if (TSolution::isSaleMode() && $arPost["PRICE_PARAMS"]["PRICE_CODE"]) {
                $arPricesIDs = TSolution\Product\Price::getPricesID($arPost["PRICE_PARAMS"]["PRICE_CODE"], true);
                if ($arPricesIDs) {
                    foreach ($arPricesIDs as $priceID) {
                        $arSelect[] = "CATALOG_GROUP_".$priceID;
                    }
                } else {
                    $arSelect[] = "CATALOG_QUANTITY";
                }
            }
            /**/

            $arFilter = [
                // 'PROPERTY_CML2_LINK' => $arPost['ID'],
                // 'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arPost['SKU_IBLOCK_ID'],
                '=ID' => $offerID
            ];

            //get offer
			$arOffer = TSolution\Cache::CIBlockElement_GetList(
                [
                    'ID' => 'ASC',
                    "CACHE" => [
                        "TAG" => TSolution\Cache::GetIBlockCacheTag($arFilter['IBLOCK_ID']),
                        "MULTI" => 'N'
                    ]
                ],				
				$arFilter,
				false,
				false,
				$arSelect
			);
            
            if($arOffer){
                //get offer props
                $arProps = [];
                if(!empty($needProps)){
                    \CIBlockElement::GetPropertyValuesArray(
                        $arProps,
                        $arPost['SKU_IBLOCK_ID'],
                        [
                            '=ID' => $arOffer['ID']
                        ],
                        [
                            'CODE' => $needProps
                        ]
                    );
                    if ($arProps) {
                        foreach ($arProps as $key => $arProp) {
                            $arOffer['DISPLAY_PROPERTIES'] = $arProp;
                            if (!$arOffer['DISPLAY_PROPERTIES']['CML2_ARTICLE'] && $arOffer['DISPLAY_PROPERTIES']['ARTICLE']) {
                                $arOffer['DISPLAY_PROPERTIES']['CML2_ARTICLE'] = $arOffer['DISPLAY_PROPERTIES']['ARTICLE'];
                            }                        
                        }
                    }
                }

                // set measure ratio
                if (TSolution::isSaleMode()){
                    $arOffer['CATALOG_MEASURE_RATIO'] = TSolution\Product\Common::getMeasureRatio($arOffer['ID']);
                }

                // get prices
                if (TSolution::isSaleMode() && $arPost["PRICE_PARAMS"]["PRICE_CODE"]) {
                    $arPrices = \CIBlockPriceTools::GetCatalogPrices(false, $arPost["PRICE_PARAMS"]["PRICE_CODE"]);                    
                    if ($arPrices) {	
                        $arOffer['PRICES'] = \CIBlockPriceTools::GetItemPrices($arOffer["IBLOCK_ID"], $arPrices, $arOffer, $bPriceVat, $arCurrencyParams);  
                    }
                }

                // get total count
                $arOffer['TOTAL_COUNT'] = TSolution\Product\Quantity::getTotalCount([
                    'ITEM' => $arOffer, 
                    'PARAMS' => $arPost['PARAMS']
                ]);
            }
        }

        if(\Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") != "N")
		{
			$obCache->StartDataCache($cacheTime, $cacheID, $cachePath);
			$obCache->EndDataCache(array("arOffer" => $arOffer));
		}

        if($arOffer){
            /* get main element */
            $arElement = TSolution\Cache::CIBlockElement_GetList(
                [
                    "CACHE" => [
                        "TAG" => TSolution\Cache::GetIBlockCacheTag($arPost['IBLOCK_ID']),
                        'MULTI' => 'N'
                    ]
                ],
                [
                    'ID' => $arPost['ID'],
                    'IBLOCK_ID' => $arPost['IBLOCK_ID'],
                    // 'ACTIVE' => 'Y'
                ],
                false,
                false,
                [
                    'ID',
                    'NAME',
                    'IBLOCK_ID',
                    'PREVIEW_PICTURE',
                    'DETAIL_PICTURE',
                    'DETAIL_PAGE_URL',
                    'PROPERTY_'.$arPost["PARAMS"]['ADD_PICT_PROP'],
                    'PROPERTY_HIT',
                    'PROPERTY_SALE_TEXT'
                ]
            );

            if (
                (!$bDetail && ($arElement['DETAIL_PICTURE'] && $arElement['PREVIEW_PICTURE'])) ||
                (!$arElement['DETAIL_PICTURE'] && $arElement['PREVIEW_PICTURE'])
            ) {
                $arElement['DETAIL_PICTURE'] = $arElement['PREVIEW_PICTURE'];
            }
            if ($arElement['PROPERTY_'.$arPost["PARAMS"]['ADD_PICT_PROP'].'_VALUE']) {
                $arElement['PROPERTIES'][$arPost["PARAMS"]['ADD_PICT_PROP']]['PROPERTY_TYPE'] = 'F';
                foreach ((array)$arElement['PROPERTY_'.$arPost["PARAMS"]['ADD_PICT_PROP'].'_VALUE'] as $value) {
                    $arElement['PROPERTIES'][$arPost["PARAMS"]['ADD_PICT_PROP']]['VALUE'][] = $value;
                }
            }
            /* */

            $bShowCompare = $arPost['PARAMS']['DISPLAY_COMPARE'] != 'false';
            $bShowFavorit = $arPost['PARAMS']['SHOW_FAVORITE'] == 'Y';

            // remove FV_ from IMG_PARAMS
            if ($arPost['PARAMS']['SHOW_GALLERY'] !== 'Y' && $arPost['IMG_PARAMS']) {
                $arImgParams = $arPost['IMG_PARAMS'];
                $arPost['IMG_PARAMS'] = [];
                foreach ($arImgParams as $key => $value) {
                    $arPost['IMG_PARAMS'][str_replace('FV_', '', $key)] = $value;
                }
            }

            if ($arElement['DETAIL_PAGE_URL']) {
                $arOffer['DETAIL_PAGE_URL'] = $arElement['DETAIL_PAGE_URL'];
                if ($arPost["OID"]) {
                    $arOffer['DETAIL_PAGE_URL'] .= '?'.$arPost["OID"].'='.$arOffer['ID'];
                }
            }
            
            if ($arPost['PRICE_PARAMS']['PARAMS']['IS_GIFT']) {
                foreach ($arOffer['PRICES'] as $priceCode => $value) {
                    $arOffer['PRICES'][$priceCode]['DISCOUNT_VALUE'] = $arOffer['PRICES'][$priceCode]['DISCOUNT_DIFF'];
                    $arOffer['PRICES'][$priceCode]['PRINT_DISCOUNT_VALUE'] = $arOffer['PRICES'][$priceCode]['PRINT_DISCOUNT_DIFF'];
                }
            }

            $arOffer['PRICES_HTML'] = TSolution\Product\Price::show(
                array_merge(
                    (array)$arPost['PRICE_PARAMS'],
                    [
                        'ITEM' => $arOffer,
                        'SHOW_SCHEMA' => false,
                        'RETURN' => true,
                    ]
                )
            );
            
            $pictureID = $arOffer['PREVIEW_PICTURE'];
            if (!$pictureID && $arOffer['DETAIL_PICTURE']) {
                $pictureID = $arOffer['DETAIL_PICTURE'];
            }
            if ($pictureID) {
                $arOffer['PICTURE_SRC'] = \CFile::GetPath($pictureID);
            }
            $bPreviewFromElement = false;
            if ($arElement['PREVIEW_PICTURE'] && !$pictureID) {
                $arOffer['PREVIEW_PICTURE'] = $arElement['PREVIEW_PICTURE'];
                $bPreviewFromElement = true;
            }

            if ($arPost['PARAMS']['SHOW_GALLERY'] === 'Y') {
                if(!$bPreviewFromElement && $arOffer["PREVIEW_PICTURE"]){
                    if(!$bDetail){
                        $arOffer["DETAIL_PICTURE"] = $arOffer["PREVIEW_PICTURE"];
                    } else if(!$arOffer["DETAIL_PICTURE"]) {
                        $arOffer["DETAIL_PICTURE"] = $arOffer["PREVIEW_PICTURE"];
                    }
                }

                $arOfferGallery = TSolution\Functions::getSliderForItem([
                    'TYPE' => 'catalog_block',
                    'PROP_CODE' => $arPost["PARAMS"]['ADD_PICT_PROP'],
                    // 'ADD_DETAIL_SLIDER' => false,
                    'ITEM' => $arOffer,
                    'PARAMS' => $arParams,
                ]);

                $arOffer['GALLERY'] = TSolution\Functions::getSliderForItem([
                    'TYPE' => 'catalog_block',
                    'PROP_CODE' => $arPost["PARAMS"]['ADD_PICT_PROP'],
                    // 'ADD_DETAIL_SLIDER' => false,
                    'ITEM' => $arElement,
                    'PARAMS' => $arParams,
                ]);

                $arOffer['GALLERY'] = array_merge($arOfferGallery, $arOffer['GALLERY']);

                if(!$bDetail)
                    array_splice($arOffer['GALLERY'], $arPost['MAX_GALLERY_ITEMS']);

                $arOffer['GALLERY_HTML'] = TSolution\Product\Image::showImage(
                    array_merge(
                        (array)$arPost['IMG_PARAMS'],
                        [
                            'ITEM' => $arOffer,
                            'PARAMS' => $arPost['PARAMS'],
                            'RETURN' => true
                        ]
                    )
                );
            } else {
                $arOffer['FAST_VIEW_HTML'] = TSolution\Product\Common::showFastView(
                    array_merge(
                        (array)$arPost['IMG_PARAMS'],
                        [
                            'ITEM' => $arOffer,
                            'PARAMS' => $arPost['PARAMS'],
                            'RETURN' => true
                        ]
                    )
                );
            }
            
            $arOffer['ICONS_HTML'] = '';
            $arIconProps = $arPost['PARAMS']['ICONS_PROPS'] ?? [];
            if ($bShowFavorit) {
                $arOffer['ICONS_HTML'] .= TSolution\Product\Common::getActionIcon(array_merge([
                    'ITEM' => $arOffer,
                    'PARAMS' => $arPost['PARAMS'],
                    'CATALOG_IBLOCK_ID' => $arOffer['IBLOCK_ID'],
                    'ITEM_ID' => $arOffer['ID'],
                ], $arIconProps));
            }
            
            if ($bShowCompare) {
                $arOffer['ICONS_HTML'] .= TSolution\Product\Common::getActionIcon(array_merge([
                    'ITEM' => TSolution::isSaleMode() ? $arOffer : $arElement,
                    'PARAMS' => $arPost['PARAMS'],
                    'TYPE' => 'compare',
                    'SVG_SIZE' => ['WIDTH' => 20,'HEIGHT' => 16],
                    'CATALOG_IBLOCK_ID' => $arElement['IBLOCK_ID'],
                    'ITEM_ID' => $arElement['ID'],
                ], $arIconProps));
            }

            $arOffer['BASKET_JSON'] = TSolution::getDataItem($arOffer, false);
            if ($arOffer['DETAIL_PAGE_URL']) {
                $arOffer['BASKET_JSON']['DETAIL_PAGE_URL'] = $arOffer['DETAIL_PAGE_URL'];
            }

            // props for display
            $arOffer['OFFER_PROP'] = TSolution::PrepareItemProps($arOffer['DISPLAY_PROPERTIES']);

            //unset HINT in prop if not show HINTS 
            if ($arOffer['OFFER_PROP'] && $arPost['PARAMS']['SHOW_HINTS'] !== 'Y') {
                foreach ($arOffer['OFFER_PROP'] as $key => $arProp) {
                    if ($arProp['HINT']) {
                        unset($arOffer['OFFER_PROP'][$key]['HINT']);
                    }
                }
            }

            // avaible
            $arOffer['STATUS'] = TSolution\Product\Quantity::getStatus([
                'ITEM' => $arOffer, 
                'PARAMS' => $arPost['PARAMS'],
                'TOTAL_COUNT' => $arOffer['TOTAL_COUNT'],
                'IS_DETAIL' => $bDetail,
            ]);

            // basket buttons
            $arBasketConfig = TSolution\Product\Basket::getOptions(array_merge(
                (array)$arPost['BASKET_PARAMS'], 
                [
                    'ITEM' => $arOffer,
                    'IS_OFFER' => true,
                    'PARAMS' => $obSKU->config,
                    'TOTAL_COUNT' => $arOffer['TOTAL_COUNT'],
                    // 'ORDER_BTN' => ($arOffer["DISPLAY_PROPERTIES"]["FORM_ORDER"]["VALUE_XML_ID"] == "YES"),
                    'SHOW_COUNTER' => false,
                ]
            ));
            $arOffer['BASKET_HTML'] = $arBasketConfig['HTML'];

            // viewed
            if ($bDetail) {
                $arOffer['VIEWED_PARAMS'] = TSolution\Product\Common::getViewedParams([
                    'ITEM' => $arOffer,
                ]);
            }
            
            //remove CATALOG_* fields
            $arOfferTmp = array_filter($arOffer, function($key){
                return strpos($key, 'CATALOG_') === false;
            }, ARRAY_FILTER_USE_KEY);

            // remove unused properties
            if(isset($arOfferTmp['DISPLAY_PROPERTIES']['CML2_ARTICLE'])){
                $arOfferTmp['DISPLAY_PROPERTIES'] = ['CML2_ARTICLE' => $arOfferTmp['DISPLAY_PROPERTIES']['CML2_ARTICLE']];
            } else {
                unset($arOfferTmp['DISPLAY_PROPERTIES']);
            }
            
            $arOffer = $arOfferTmp;
        }
    }	
    /**/
    
    die(\Bitrix\Main\Web\Json::encode($arOffer));
}