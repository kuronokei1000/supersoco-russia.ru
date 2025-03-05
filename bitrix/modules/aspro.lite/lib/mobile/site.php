<?

namespace Aspro\Lite\Mobile;

use CLite as Solution;
use \Bitrix\Main\Config\Option;

class Site
{
    const mobileTemplateName	= 'aspro-lite-mobile';

    public static function getTemplatesList($siteId)
    {
      $arTemplates = [];
      $rsTemplates = \CSite::GetTemplateList($siteId);
      while ($arTemplate = $rsTemplates->Fetch()) {
        $arTemplates[]  = $arTemplate;
      }
      return $arTemplates;
    }

    public static function findMobileTemplate($templates)
    {
      if (!$templates) {
        return false;
      }
      return (bool)array_filter($templates, function($arTemplate){
        return strpos($arTemplate['TEMPLATE'], self::mobileTemplateName) !== false;
      });
    }
    
    public static function removeMobileTemplate($siteId, $templates)
    {
      $arTemplates = array_filter($templates, function($arTemplate){
        return strpos($arTemplate['TEMPLATE'], self::mobileTemplateName) === false;
      });

      $obSite = new \CSite();
        $obSite->Update($siteId, [
            'ACTIVE' => "Y",
            'TEMPLATE' => $arTemplates
        ]);
    }
    
    public static function addMobileTemplate($siteId, $arTemplates)
    {
		    $arTemplates[] = [
            'CONDITION' => "defined('TEMPLATE_TYPE') && TEMPLATE_TYPE === 'mobile'",
            'SORT' => 1,
            'TEMPLATE' => self::mobileTemplateName
        ];

        $obSite = new \CSite();
        $obSite->Update($siteId, [
            'ACTIVE' => "Y",
            'TEMPLATE' => $arTemplates
        ]);
	  }

}
