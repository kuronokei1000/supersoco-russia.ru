<?
namespace Aspro\Lite;

use \Bitrix\Main\Loader,
    \Bitrix\Main\Config\Option;

use CLite as Solution,
    CLiteCache as SolutionCache,
    Aspro\Functions\CAsproLite as SolutionFunctions;

class Menu {
    public static function getTopMenuSections(){
        $arTopSections = [];
        $menuIblockId = SolutionCache::$arIBlocks[SITE_ID][Solution::partnerName.'_'.Solution::solutionName.'_catalog'][Solution::partnerName.'_'.Solution::solutionName.'_megamenu'][0];
        if($menuIblockId){
            $arTopSections = SolutionCache::CIblockSection_GetList(
                array(
                    'SORT' => 'ASC',
                    'NAME' => 'ASC',
                    'CACHE' => array(
                        'TAG' => SolutionCache::GetIBlockCacheTag($menuIblockId),
                        'MULTI' => 'Y',
                    )
                ),
                array(
                    'ACTIVE' => 'Y',
                    'GLOBAL_ACTIVE' => 'Y',
                    'IBLOCK_ID' => $menuIblockId,
                    'UF_TOP_SECTIONS' => '1',
                ),
                false,
                array(
                    'ID',
                    'NAME',
                    'IBLOCK_SECTION_ID',
                    'UF_MEGA_MENU_LINK',
                    'UF_TOP_SECTIONS',
                )
            );

            ksort($arTopSections);
        }
        return $arTopSections;
    }
    
}?>
