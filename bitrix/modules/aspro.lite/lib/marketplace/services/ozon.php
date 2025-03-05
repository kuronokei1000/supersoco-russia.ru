<?php

namespace Aspro\Lite\Marketplace\Services;

use Aspro\Lite\Marketplace\Request;

class Ozon extends Base
{
    // protected $baseApiUrl = 'https://seller.ozon.ru';
    protected $baseApiUrl = 'https://api-seller.ozon.ru';

    protected $cacheBasedir = 'mp-ozon';

    protected $client = null;
    protected $token = null;

    public function __construct($client, $token)
    {
        $this->client = $client;
        $this->token = $token;

        parent::__construct();
    }

    public function getClientId()
    {
        return $this->client;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getClientHttpHeader(): array
    {
        return [
            'Client-Id' => $this->client,
            'Api-Key' => $this->token,
            'Content-Type' => 'application/json',
        ];
    }

    public function checkAuth(): bool
    {
        return true;
    }

    /**
     * Create a card
     *
     * @param array $card
     * [
     *    [
     *       'vendorCode' => 'string',
     *       'characteristics' => [
     *             "Предмет": "Платья",
     *             "ТНВЭД": [ "6403993600" ],
     *             "Пол": [ "Мужской" ]
     *         ],
     *         "sizes": [
     *             "techSize": "40-41",
     *             "wbSize": "",
     *              "price": 3999,
     *              "skus": [
     *                  "1000000001"
     *              ]
     *         ]
     *    ]
     * ]
     * @return array|false
     */
    
    public function cardsCreate(array $cards)
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/product/import', ['items' => $cards]);

        return !$result['error'];
    }

    /**
     * Update a card
     *
     * @param array $card See create a card
     * @return array|boolean
     */
    public function cardsUpdate(array $cards)
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/product/import', ['items' => $cards]);

        return !$result['error'];
    }

    /**
     * Get a list of vendor cards with filter
     *
     * @param array $vendorCodes
     *  [
     *      'vendorCodes' => [ '6000000001' ]
     *  ]
     * @return array|bool|mixed|void
     */
    public function getCardList(array $vendorCodes = []): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/product/list', [
            'filter' => [
                'offer_id' => $vendorCodes,
            ],
            'visibility' => 'ALL'
        ]);

        if ($result['result'] && isset($result['result']['items'])) {
            return $result['result']['items'];
        }

        return [];
    }

    /**
     *  Getting all categories
     *
     * @return array
     */
    public function getCategoryTree(): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/category/tree', [
            'language' => 'RU'
        ], true);
        return $result['result'] ?? [];
    }

    /**
     * Getting characteristics for a specified category of goods
     *
     * @param string $categoryId
     * @param array $additional_filter
     * @return array
     */
    public function getCategoryAttribute(string $categoryId, array $filter = []): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v3/category/attribute', [
            'category_id' => [$categoryId]
        ] + $filter, true);
        
        return $result['result'] ?? [];
    }
    
    /**
     * Getting list of values characteristic by property id
     *
     * @param string $categoryId
     * @param string $propId
     * @param number $last_value_id
     * @param number $limit
     * @return array
     */
    public function getPropertyValues(string $categoryId, string $propId, $last_value_id = 0, $limit = 99): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/category/attribute/values', [
            'category_id' => $categoryId,
            'attribute_id' => $propId,
            'last_value_id' => $last_value_id,
            'limit' => $limit
        ]);
        
        return $result ?? [];
    }

    /**
     * Returns a list of supplier's products with their balances
     *
     * @param array $params
     * [
     *    'search' => 'string',
     *    'skip' => 'string', (required)
     *    'take' => 'string', (required)
     *    'sort' => 'string',
     *    'order' => 'string',
     * ]
     *
     * @return array
     */
    public function getStocks(array $params = []): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_GET, '/api/v2/stocks', $params);

        return $result['stocks'] ?? [];
    }

    /**
     * Get list of supplier's warehouses
     *
     * @return array
     */
    public function getWarehouses(): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v1/warehouse/list', ['language' => 'RU'], true, 3600);

        return $result && is_array($result) ? $result : [];
    }
    
    /**
     * Get info about limits
     *
     * @return array
     * [
     *      'daily_create' => [...],
     *      'daily_update' => [...],
     *      'total' => [...],
     * ]
     */
    public function getLimits(): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v4/product/info/limit', ['language' => 'RU']);

        return $result && is_array($result) ? $result : [];
    }
    
    public function getGoods($last_id = '', $limit = 1000): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v3/products/info/attributes', [
            'filter' => [
                'visibility' => 'ALL'
            ],
            'last_id' => $last_id,
            'limit' => $limit
        ]);

        return $result && is_array($result) ? $result : [];
    }

    public function getProductList($filter = [], $limit = 1000): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/product/info/list', $filter/*[
            'filter' => $filter,
            // 'last_id' => $last_id,
            'limit' => $limit
        ]*/);

        return $result && is_array($result) ? $result : [];
    }

    /**
     * Updates stock items
     *
     * @param array $stocks
     * [
     *       [
     *          'offer_id': string,
     *          'stock': int,
     *          'warehouse_id': int
     *       ],
     *       ...
     * ]
     * @return false|void
     */
    public function updateStocks(array $stocks)
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v2/products/stocks', ['stocks' => $stocks]);

        return (bool)$result;
    }

    /**
     * Loading prices. You can upload no more than 1000 items at a time.
     *
     * @param array $prices
     * [
     *      [
     *          "offer_id" => string,
     *          "price" => string
     *          "old_price" => ?string
     *      ],
     *      ...
     * ]
     * @return void
     */
    public function updatePrices(array $prices)
    {        
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/v1/product/import/prices', ['prices' => $prices]);
        
        return $result ?? null;
    }

    public function uploadMedia(string $vendoreCode, array $media)
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/content/v1/media/save', [
            'vendorCode' => $vendoreCode,
            'data' => $media
        ]);

        return $result['uploadId'] ?? null;
    }

    protected function sendRequest(string $method, $url, $payload = [], $useCache = false, $cacheTime = 3600)
    {
        $queryResult = parent::sendRequest($method, $url, $payload, $useCache, $cacheTime);

        if (isset($queryResult['message']) && $queryResult['message'] || $queryResult['errors']) {
            if($queryResult['errors']) {
                $this->addError(implode(', ', $queryResult['errors']));
            } else {
                $this->addError($queryResult['message'] ?? 'Undefined error text');
                if(is_array($queryResult['details'])){
                    foreach ($queryResult['details'] as $keyError => $valueError) {
                        $this->addError($keyError . ': ' . $valueError);
                    }
                }
            }
        }

        return $queryResult;
    }
}