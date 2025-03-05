<?
namespace Aspro\Lite\Search;

use \Bitrix\Main\Loader,
	\Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Config\Option;

use CLite as Solution,
    CLiteCache as SolutionCache,
    Aspro\Functions\CAsproLite as SolutionFunctions;

class Common {
    public static function SortBySearchRank($searchQuery, $arElements, $arParams){
		$arResult = array();

		if($arElements){
			if(Loader::includeModule('search')){
				$aSort = array(
					'CUSTOM_RANK' => 'DESC',
					'TITLE_RANK' => 'DESC',
					'RANK' => 'DESC',
					'DATE_CHANGE' => 'DESC'
				);

				$exFILTER = \CSearchParameters::ConvertParamsToFilter($arParams, 'arrFILTER');

				$arFilter = array(
					'SITE_ID' => SITE_ID,
					'QUERY' => trim($searchQuery),
					'ITEM_ID' => array_values($arElements),
				);

				$obSearch = new \CSearch();

				$obSearch->SetOptions(array(
					'ERROR_ON_EMPTY_STEM' => $arParams['RESTART'] != 'Y',
					'NO_WORD_LOGIC' => $arParams['NO_WORD_LOGIC'] == 'Y',
				));

				$obSearch->Search($arFilter, $aSort, $exFILTER);

				//echo $obSearch->errorno;
				//echo $obSearch->error;

				if($obSearch->errorno == 0){
					$obSearch->NavStart(20, false);
					$ar = $obSearch->GetNext();

					if(!$ar && $obSearch->Query->bStemming){
						$exFILTER['STEMMING'] = false;
						$obSearch = new \CSearch();
						$obSearch->Search($arFilter, $aSort, $exFILTER);

						//echo $obSearch->errorno;
						//echo $obSearch->error;

						if($obSearch->errorno == 0){
							$obSearch->NavStart($arParams['PAGE_RESULT_COUNT'], false);
							$ar = $obSearch->GetNext();
						}
					}

					while($ar){
						$arResult[] = $ar['ITEM_ID'];
						$ar = $obSearch->GetNext();
					}
				}

			}
		}

		return $arResult;
	}

	public static function SortBySearchOrder($arElementsIDsSorted, $arItemsToSort){
		$arResult = array();

		if($arItemsToSort){
			$arResult = array_column($arItemsToSort, 'ID');
			$arElementsIDsSorted = array_values($arElementsIDsSorted);

			usort($arResult, function($a, $b) use ($arElementsIDsSorted){
				$posA = array_search($a, $arElementsIDsSorted);
				$posB = array_search($b, $arElementsIDsSorted);

				if($posA !== false && $posB !== false){
					return $posA <=> $posB;
				}

				return $posA !== false ? -1 : ($posB !== false ? 1 : 0);
			});
		}

		return $arResult;
	}
}
?>