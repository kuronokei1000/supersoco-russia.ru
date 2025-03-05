<?
namespace Aspro\Lite\Panel;

use Bitrix\Main\Localization\Loc,
    CLite as Solution;

Loc::loadMessages(__FILE__);

class Button {
    const BUTTON_ID = 'aspro-solution_panel-button';

    protected static $instance;

    protected $siteId;
    protected $languageId;
    protected $items;
    protected $defaultLink;
    protected $wizard;

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
        
        $this->items = [];
        $this->defaultLink = '';
        $this->wizard = Wizard::getInstance([
            'siteId' => $this->siteId, 
            'languageId' => $this->languageId,
        ]);
    }

    protected function __clone() {}
    protected function __wakeup() {}

    public function __get($name) {
        switch ($name) {
            case 'siteId':
                return $this->siteId;
            case 'languageId':
                return $this->languageId;
            case 'items':
                return $this->items;
            case 'defaultLink':
                return $this->getDefaultLink();
            case 'wizard':
                return $this->wizard;
        }
    }

    public function __set($name, $value) {
        switch ($name) {
            case 'defaultLink':
                return $this->defaultLink = trim($value);
        }
    }

    public function show() {
        if ($this->wizard->canWrite()) {
            $this->wizard->addPanelButtonMenuItem();
        }

        if (
            $this->items ||
            strlen($this->defaultLink)
        ) {
            $iconSrc = $this->getIconSrc();
            if (
                @file_exists($_SERVER['DOCUMENT_ROOT'].$this->getIconSpriteSrc()) &&
                @file_exists($_SERVER['DOCUMENT_ROOT'].$this->getIconSpriteCssHref())
            ) {
                $iconSrc = '';
                $GLOBALS['APPLICATION']->AddHeadString('<link href="'.$this->getIconSpriteCssHref().'" type="text/css" rel="stylesheet" />', true);
            }

            $arButton = [
                'ID' => static::BUTTON_ID,
                'TEXT' => Loc::getMessage('ASPRO_PANEL_BUTTON_TEXT'),
                'TYPE' => 'BIG',
                'MAIN_SORT' => 2000,
                'SORT' => 100,
                'HREF' => $this->getDefaultLink(),
                'ICON' => 'aspro_panel_icon',
                'SRC' => $iconSrc,
                'MENU' => [],
            ];

            if ($this->items) {
                foreach ($this->items as $item) {
                    $arButton['MENU'][] = $item;
                }
            }

            $GLOBALS['APPLICATION']->AddPanelButton($arButton, true);
        }
    }

    public function addItem(array $item) {
        $this->items[] = $item;
    }

    public function getIconSpriteCssHref() {
        return '/bitrix/css/'.Solution::moduleID.'/panel_icon.min.css';
    }

    public function getIconSpriteSrc() {
        return '/bitrix/images/'.Solution::moduleID.'/panel_icon_sprite.gif';
    }

    public function getIconSrc() {
        return '/bitrix/images/'.Solution::moduleID.'/panel_icon.gif';
    }

    protected function getDefaultLink() {
        if (
            !strlen($this->defaultLink) &&
            $this->wizard->canWrite()
        ) {
            return 'javascript: '.$this->wizard->getPopupLink().';';
        } else {
            return $this->defaultLink;
        }
    }
}
