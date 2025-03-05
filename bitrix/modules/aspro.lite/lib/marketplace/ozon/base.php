<?
namespace Aspro\Lite\Marketplace\Ozon;

use Aspro\Lite\Marketplace\IService as IMPService,
    Aspro\Lite\Marketplace\Base as MarketplaceBase,
    Aspro\Lite\Marketplace\Ozon\Connector as OzonConnector;


class Base extends MarketplaceBase{
    const API_URL = 'https://seller.ozon.ru/';
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
    public function getService():IMPService
    {
        return new OzonConnector($this->token);
    }
}