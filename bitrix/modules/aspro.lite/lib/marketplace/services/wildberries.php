<?php

namespace Aspro\Lite\Marketplace\Services;

use Aspro\Lite\Marketplace\Request;

class Wildberries extends Base
{
    protected $baseApiUrl = 'https://suppliers-api.wildberries.ru';

    protected $cacheBasedir = 'mp-wildberries';

    protected $token = null;

    public function __construct($token)
    {
        $this->token = $token;

        parent::__construct();
    }

    public function getClientHttpHeader(): array
    {
        return [
            'Authorization' => $this->token,
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
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/content/v1/cards/upload', [$cards]);

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
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/content/v1/cards/update', $cards);

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
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/content/v1/cards/filter', [
            'vendorCodes' => $vendorCodes,
        ]);

        return $result['data'] ?? [];
    }

    /**
     * Get a list barcodes
     *
     * @param int $quantity
     * @return array
     */
    public function getBarcodes(int $quantity): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/content/v1/barcodes', [
            'count' => $quantity,
        ]);
        
        return $result['data'] ?? [];
    }

    /**
     * Get a list of objects with errors
     *
     * @return array
     */
    public function getErrorCardList(): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_GET, '/content/v1/cards/error/list', []);

        return $result['data'] ?? [];
    }

    /**
     *  Search for a subsections by pattern
     *
     * @param string $pattern Search text
     * @param string $top
     * @return array
     */
    public function getObjectList(string $pattern, int $top = 0): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_GET, '/content/v1/object/all', [
            'name' => $pattern,
            'top' => $top,
        ], true);

        return $result['data'] ?? [];
    }

    /**
     * Getting a subsection configuration
     *
     * @param string $subsectionName Subsection name
     * @return array
     */
    public function getObjectTranslated(string $subsectionName): array
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_GET, '/content/v1/object/characteristics/list/filter', [
            'name' => $subsectionName
        ], true);
        
        return $result['data'] ?? [];
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
        $result = $this->sendRequest(Request::HTTP_METHOD_GET, '/api/v3/warehouses', [], true, 3600);

        return $result && is_array($result) ? $result : [];
    }

    /**
     * Updates stock items
     *
     * @param array $stocks
     * [
     *       [
     *          'barcode': "656335639",
     *          'stock': 1,
     *          'warehouseId': 7543
     *       ],
     *       ...
     * ]
     * @return false|void
     */
    public function updateStocks(array $stocks)
    {
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/api/v2/stocks', $stocks);

        return (bool)$result;
    }

    /**
     * Loading prices. You can upload no more than 1000 items at a time.
     *
     * @param array $prices
     * [
     *      [
     *          "nmId" => 1234567, (int)
     *          "price" => 1000    (int)
     *      ],
     *      ...
     * ]
     * @return void
     */
    public function updatePrices(array $prices)
    {        
        $result = $this->sendRequest(Request::HTTP_METHOD_POST, '/public/api/v1/prices', $prices);
        
        return $result['uploadId'] ?? null;
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

        if (isset($queryResult['error']) && $queryResult['error'] || $queryResult['errors']) {
            if($queryResult['errors']) {
                $this->addError(implode(', ', $queryResult['errors']));
            } else {
                $this->addError($queryResult['errorText'] ?? $queryResult['error']['message'] ?? 'Undefined error text');
                if(is_array($queryResult['additionalErrors'])){
                    foreach ($queryResult['additionalErrors'] as $keyError => $valueError) {
                        $this->addError($keyError . ': ' . $valueError);
                    }
                }
            }
        }

        return $queryResult;
    }
}