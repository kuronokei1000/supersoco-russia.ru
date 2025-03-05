<?php

namespace Aspro\Lite\Marketplace;

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\ArgumentException;

class Request
{
    const ERROR_WRONG_ANSWER = 'WRONG_ANSWER';

    const HTTP_SOCKET_TIMEOUT = 10;
    const HTTP_STREAM_TIMEOUT = 10;

    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET = 'GET';

    protected $baseUrl = '';

    protected $errorCollection = null;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->errorCollection = new ErrorCollection();
    }

    /**
     * Low-level function for REST method call. Returns method response including paging params and error messages
     *
     * @param string $requestMethod
     * @param string $requestUrl
     * @param array $arParams
     * @return false
     * @throws ArgumentException
     */
    public function call(string $requestMethod, string $requestUrl, array $arParams = ['HEADERS' => null, 'DATA' => null])
    {
        $httpClient = $this->getHttpClient();

        $this->appendHeaders($httpClient, $arParams['HEADERS']);

        $queryJsonData = null;
        if ($requestMethod === Request::HTTP_METHOD_GET) {
            $queryUrl = $this->getRequestUrl($requestUrl, $arParams['DATA'] ?? []);
        } else {
            $queryUrl = $this->getRequestUrl($requestUrl);
            $queryJsonData = json_encode($arParams['DATA']);
        }

        if ($httpClient->query($requestMethod, $queryUrl, $queryJsonData)) {
            return json_decode($httpClient->getResult(), true);
        } else {
            $this->errorCollection->add([
                new Error('Wrong answer from service', static::ERROR_WRONG_ANSWER)
            ]);
        }

        return false;
    }

    protected function appendHeaders(&$httpClient, $arHeaders)
    {
        if (is_array($arHeaders)) {
            foreach ($arHeaders as $key => $value) {
                $httpClient->setHeader($key, $value);
            }
        }

        return $arHeaders;
    }

    protected function getRequestUrl(string $url, array $queryParams = []): string
    {
        $query = '';

        if($queryParams) {
            $query = '?' . http_build_query($queryParams, '&');
        }

        return $this->baseUrl . $url . $query;
    }

    public function getErrorCollection(): ErrorCollection
    {
        return $this->errorCollection;
    }

    protected function getHttpClient(): HttpClient
    {
        return new HttpClient([
            'socketTimeout' => static::HTTP_SOCKET_TIMEOUT,
            'streamTimeout' => static::HTTP_STREAM_TIMEOUT,
        ]);
    }
}