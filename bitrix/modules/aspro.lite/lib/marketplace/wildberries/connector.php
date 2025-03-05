<?
namespace Aspro\Lite\Marketplace\Wildberries;

use Aspro\Lite\Marketplace\IService as IMPService,
    Aspro\Lite\Marketplace\Base as MarketplaceBase;

use Aspro\Lite\Marketplace\Request as HttpClient;

class Connector implements IMPService{
    const API_URL = 'https://suppliers-api.wildberries.ru/';
    public function checkAuth()
    {
        $client = new HttpClient(static::API_URL);
        $client->call('test', ['test' => 2, 'test3' => 'v']);
        return 'checkAuth';
    }
}