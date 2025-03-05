<?php

namespace Aspro\Lite\Marketplace\Ajax\Export_ozon;

use \Bitrix\Main\Application,
    \Bitrix\Main\Web\Json,
    \Bitrix\Main\Localization\Loc;

use \Aspro\Lite\Marketplace\Models\Ozon\GoodsTable as Goods,
    Aspro\Lite\Traits\Serialize;

class Sections extends Base
{
    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->entity = '\Aspro\Lite\Marketplace\Models\Ozon\SectionsTable';
    }

    public function checkTable()
    {
        if (!$this->entity::getEntity()->getConnection()->isTableExists($this->entity::getTableName())) {
            $this->entity::getEntity()->createDbTable();
        }
    }

    protected function html()
    {
        $url = $this->request->getHeader('origin') . $this->request->getRequestedPage();
        $postData = Json::encode($this->request->getPostList()->getValues());
?>
        <p class="errortext"><?= GetMessage("GET_PROPERTY_VALUES_WARNING"); ?></p>
        <div id="progress_tree"><?= GetMessage("GET_CATEGORIES_TREE"); ?> <span id="progress_tree_value" hidden>- Ok</span></div>
        <div id="progress_import" hidden><?= GetMessage("SET_CATEGORIES_TREE"); ?> <span id="progress_import_value">0</span></div>
        <script>
            ;
            (function requestData(action = 'getSections', last_id = 0) {

                const url = '<?= $url; ?>';
                const postData = <?= $postData; ?>

                delete postData.action;

                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'json',
                    data: Object.assign({}, postData, {
                        action: action,
                        type: '<?= $this->type ?>',
                        last_id: last_id,
                        controller: 'ozon'
                    }),
                    success: (data) => {
                        if (!data) {
                            setTimeout(() => {
                                top.BX.closeWait();
                                top.BX.WindowManager.Get().Close();

                                const obDetailWindow = new BX.CAdminDialog({
                                    'content_url': url + '?bxpublic=Y',
                                    'content_post': postData,
                                    'width': 500,
                                    'height': 500,
                                    'resizable': false
                                });
                                obDetailWindow.Show();
                            }, 1000)

                            return;
                        }

                        if (data.tree) {
                            document.getElementById('progress_tree_value').removeAttribute('hidden');
                            document.getElementById('progress_import').removeAttribute('hidden');
                        }
                        if (data.next) {
                            if (data.count) {
                                const $value = document.getElementById('progress_import_value');
                                $value.textContent = parseInt($value.textContent) + data.count;
                            }

                            requestData(data.next, data.last_id)
                        }
                    }
                });
            })()
        </script>
<? }

    protected function action()
    {
        if ($this->checkRequest($this->request)) {

            $GLOBALS['APPLICATION']->RestartBuffer();

            switch ($this->request->get('action')) {
                case 'getSections':
                    echo Json::encode($this->getTree());
                    break;
                case 'setSections':
                    echo Json::encode($this->addValues($this->request->get('last_id'), $this->request->get('action')));
                    break;
            }

            die();
        }
    }

    public function getTree()
    {
        $arResult = $this->adapter->getServiceCategories(true);

        $this->setTreeValues($arResult);

        return [
            'tree' => $arResult,
            'next' => 'setSections'
        ];
    }

    private function setTreeValues($tree)
    {
        $_SESSION['TREE'] = $tree;
    }
    
    private function getTreeValues()
    {
        return $_SESSION['TREE'];
    }

    public function addValues($last_id, $next)
    {
        $items = $this->getSectionsFromGoods($last_id);

        if ($items) {
            $sections = $this->getSectionsTree($items['SECTIONS']);
            $this->processCategories($sections);

            return [
                'last_id' => $items['ID'],
                'count' => count($sections),
                'next' => $next
            ];
        }
    }

    private function getSectionsFromGoods($last_id)
    {
        $result = [];

        $items = Goods::getList([
            'filter' => [
                '>ID' => $last_id,
                'CLIENT_ID' => $this->adapter->getServiceClientId()
            ],
            'limit' => 1
        ])->fetch();

        if ($items) {
            $result['ID'] = $items['ID'];

            if ($items['VALUE']) {
                $values = Serialize::unserialize($items['VALUE']);

                foreach ($values as $item) {
                    $result['SECTIONS'][$item['category_id']] = $item['category_id'];
                }
            }
        }

        return $result;
    }

    private function getSectionsTree($sections):array
    {
        $result = [];
        foreach ($sections as $section) {
            $this->getSectionTree($section, $result);
        }

        return $result;
    }

    private function getSectionTree($section_id, &$result)
    {
        foreach ($this->getTreeValues() as $rootCategory) {
            foreach ($rootCategory['children'] as $deepCategory) {
                foreach ($deepCategory['children'] as $deepestCategory) {
                    if ($deepestCategory['category_id'] === $section_id) {
                        $this->prepareCategory($result, $rootCategory);
                        $this->prepareCategory($result, $deepCategory, $rootCategory['category_id']);
                        $this->prepareCategory($result, $deepestCategory, $deepCategory['category_id']);
                    }
                }
            }
        }
    }

    private function prepareCategory(&$result, $arCategory, $parentCategoryId = 0)
    {
        $result[$arCategory['category_id']] = [
            'ozon_id' => $arCategory['category_id'],
            'parent_id' => $parentCategoryId,
            'title' => $arCategory['title'],
        ];
    }

    private function processCategories($sections)
    {
        foreach ($sections as $key => $section) {
            $this->processCategory($section);
        }
    }
    private function processCategory($section)
    {
        if (!$this->getValues([
            'filter' => [
                'OZON_ID' => $section['ozon_id']
            ]
        ])->getAll()) {
            $arSetValues = [
                'CLIENT_ID' => $this->adapter->getServiceClientId(),
                'OZON_ID' => $section['ozon_id'],
                'PARENT_ID' => $section['parent_id'],
                'TITLE' => $section['title'],
            ];
            
            $this->setValues($arSetValues);
        }
    }
    
    protected function summary()
    {
        $this->summary->add(Loc::getMessage('GET_ITEMS_OZON', ['#CNT#' => count($this->getValues())]));
        $this->summary->add(Loc::getMessage('IMPORTED_ITEMS_OZON'));
    }
}
