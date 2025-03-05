<?
namespace Aspro\Lite\Marketplace\Wildberries;

use Aspro\Lite\Marketplace\IService as IMPService,
    Aspro\Lite\Marketplace\Base as MarketplaceBase,
    Aspro\Lite\Marketplace\Wildberries\Connector as WildberriesConnector;


class Base extends MarketplaceBase{
    const API_URL = 'https://suppliers-api.wildberries.ru/';
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
    public function getService():IMPService
    {
        return new WildberriesConnector($this->token);
    }
    public function sendRequest()
    {
        return 'text';
    }
}