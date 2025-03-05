<?php

namespace Aspro\Lite\Marketplace\Ajax\Export_ozon;

use \Bitrix\Main\Application,
    \Bitrix\Main\Web\Json,
    \Bitrix\Main\Localization\Loc;

use Aspro\Lite\Traits\Serialize;

class Goods extends Base
{
    private $limit = 15; // count items for getting from Ozon

    public const PROPS = [
        'BRAND' => 85,
        'PREVIEW_TEXT' => 4191,
    ];

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->entity = '\Aspro\Lite\Marketplace\Models\Ozon\GoodsTable';
    }

    public function checkTable()
    {
        if (!$this->entity::getEntity()->getConnection()->isTableExists($this->entity::getTableName())) {
            $err = $GLOBALS['DB']->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/aspro.lite/lib/marketplace/db/{$this->alias}/installGoods.sql");
            if ($err) {
                $GLOBALS['APPLICATION']->ThrowException(implode("", $err));
            }
        }
    }

    protected function html()
    {
        $url = $this->request->getHeader('origin') . $this->request->getRequestedPage();
        $postData = Json::encode($this->request->getPostList()->getValues());
?>
        <p class="errortext"><?= GetMessage("GET_PROPERTY_VALUES_WARNING"); ?></p>
        <div id="progress"><?= GetMessage("SET_ITEMS_TREE"); ?> <span id="progress_value">0</span></div>
        <script>
            ;
            (function requestData(step = 1, last_id = '') {
                const url = '<?= $url; ?>';
                const postData = <?= $postData; ?>

                // delete postData.action;

                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'json',
                    data: Object.assign({}, postData, {
                        action: 'getGoods',
                        last_id: last_id,
                        step: step,
                        type: '<?= $this->type ?>',
                        controller: 'ozon'
                    }),
                    success: (data) => {
                        if (data.last_id) {
                            const $value = document.getElementById('progress_value');
                            $value.textContent = parseInt($value.textContent) + data.result.length;

                            requestData(++step, data.last_id);
                        } else {
                            setTimeout(() => {
                                top.BX.closeWait();
                                top.BX.WindowManager.Get().Close();

                                postData.stage = 'sections'; // for sync next stage

                                const obDetailWindow = new BX.CAdminDialog({
                                    'content_url': url + '?bxpublic=Y',
                                    'content_post': postData,
                                    'width': 500,
                                    'height': 500,
                                    'resizable': false
                                });
                                obDetailWindow.Show();
                            }, 1000)
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
                case 'getGoods':
                    echo Json::encode($this->addValues($this->request->get('last_id'), $this->request->get('step')));
                    break;
            }

            die();
        }
    }

    public function addValues($last_id, $step = 1)
    {
        $arResult = $this->adapter->getServiceGoods($last_id, $this->limit);
        if ($arResult['last_id']) {

            $arInfo = $this->adapter->getServiceProductList([
                'product_id' => array_map(fn($item) => $item['id'], $arResult['result'])
            ], $arResult['total']);


            $arSetValues = [
                'CLIENT_ID' => $this->adapter->getServiceClientId(),
                'STEP' => $step,
                'VALUE' => array_map(function ($item) use ($arInfo) {
                    $props = [];

                    foreach ($item['attributes'] as $attr) {
                        if (in_array($attr['attribute_id'], self::PROPS)) {
                            $props[array_search($attr['attribute_id'], self::PROPS)] = substr(current($attr['values'])['value'], 0, 1000);
                        }
                    }

                    return array_merge([
                        'id' => $item['id'],
                        'category_id' => $item['category_id'],
                        'name' => $item['name'],
                        'offer_id' => $item['offer_id'],
                        'width' => $item['width'],
                        'height' => $item['height'],
                        'depth' => $item['depth'],
                        'weight' => $item['weight'],
                        'weight_unit' => $item['weight_unit'],
                        'dimension_unit' => $item['dimension_unit'],
                        'images' => $item['images'],
                        'props' => $props,
                        // 'prices' => ,
                    ], $arInfo[$item['id']]);
                }, $arResult['result']),
            ];

            $this->setValues($arSetValues);
        }

        return $arResult;
    }

    protected function summary()
    {
        $cnt = array_reduce($this->getValues()->getAll(), fn($acc, $item) => $acc + count(Serialize::unserialize($item['VALUE'])));
        
        $this->summary->add(Loc::getMessage('GET_ITEMS_OZON', ['#CNT#' => $cnt]));
        $this->summary->add(Loc::getMessage('IMPORTED_ITEMS_OZON'));
    }
}
