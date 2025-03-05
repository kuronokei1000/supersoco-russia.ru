<?
namespace Aspro\Lite\Panel;

use Bitrix\Main\Localization\Loc,
    CLite as Solution;

Loc::loadMessages(__FILE__);

class Wizard {
    protected static $instance;

    protected $siteId;
    protected $languageId;

    public static function getInstance(array $params) {
        $siteId = trim($params['siteId']) ?: '';
        $languageId = trim($params['languageId']) ?: '';

        if (!isset(static::$instance[$siteId])) {
            static::$instance[$siteId] = [];
        }

        if (!static::$instance[$siteId][$languageId]) {
            static::$instance[$siteId][$languageId] = new static([
                'siteId' => $siteId, 
                'languageId' => $languageId,
            ]);
        }

        return static::$instance[$siteId][$languageId];
    }

    protected function __construct(array $params) {
        $siteId = trim($params['siteId']) ?: '';
        if (!$siteId) {
            throw new \Exception('Incorrect param `siteId`.');
        }
        $this->siteId = $siteId;

        $languageId = trim($params['languageId']) ?: '';
        if (!$languageId) {
            throw new \Exception('Incorrect param `languageId`.');
        }
        $this->languageId = $languageId;
    }
    
    protected function __clone() {}
    protected function __wakeup() {}

    public function __get($name) {
        switch ($name) {
            case 'siteId':
                return $this->siteId;
                break;
            case 'languageId':
                return $this->languageId;
                break;
        }
    }

    public function canWrite() {
        return Solution::checkModuleRight('W', false, ['Y']);
    }

    public function getLink() {
        return '/bitrix/admin/'.Solution::moduleID.'_wizard.php?lang='.$this->languageId.'&site='.$this->siteId;
    }

    public function getPopupLink() {
        return '(function(t){BX.ready(function(){if(!BX.data(t,\'load\')){BX.data(t,\'load\',true);t.setAttribute(\'data-name\',\'wizard_solution\');t.setAttribute(\'data-param-form_id\',\'wizard_solution\');t.setAttribute(\'data-param-template\',\'\');t.setAttribute(\'data-param-site\',\''.$this->siteId.'\');t.setAttribute(\'data-param-lang\',\''.$this->languageId.'\');var m=setInterval(function(){if(typeof $==\'function\'&&typeof $.fn==\'object\'&&typeof $.fn.jqmEx==\'function\'){clearInterval(m);$(t).jqmEx();$(t).trigger(\'click\');}},100);}});})(this);';
    }

    public function addPanelButtonMenuItem() {
        Button::getInstance([
            'siteId' => $this->siteId,
            'languageId' => $this->languageId,
        ])->addItem([
            'TEXT' => Loc::getMessage('ASPRO_WIZARD_TEXT'),
            'TITLE' => Loc::getMessage('ASPRO_WIZARD_TITLE'),
            'DEFAULT' => true,
            'SORT' => 10,
            'ICON' => '',
            'ACTION' => $this->getPopupLink()
        ]);
    }
}