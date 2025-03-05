<?
namespace Aspro\Lite\Marketplace;

use Aspro\Lite\Marketplace\IService as IMPService;

abstract class Base {
    abstract function getService():IMPService;
    public function checkAuth(){
        $service = $this->getService();
        $service->checkAuth();
        echo "<pre>";
        print_r($service);
        echo "</pre>";
        return 123;
    }
}