<?
namespace Aspro\Lite\Marketplace\Ozon;

use Aspro\Lite\Marketplace\IService as IMPService,
    Aspro\Lite\Marketplace\Base as MarketplaceBase;

use Aspro\Lite\Marketplace\Request as HttpClient;

class Connector implements IMPService{
    const API_URL = 'https://api-seller.ozon.ru';
    public function checkAuth()
    {
        $client = new HttpClient(static::API_URL);
        // $client->call('/v2/product/list', ['HEADERS' => ['Client-Id' => 411449, 'Api-Key' => '7a7af6ee-6ec1-42f3-9ff5-c277335b5708']]);
        $client->call('/v2/category/tree', ['HEADERS' => ['Client-Id' => 411449, 'Api-Key' => '7a7af6ee-6ec1-42f3-9ff5-c277335b5708'], 'DATA' => ['category_id' => 17035748]]);
        return 'checkAuth';
    }
}