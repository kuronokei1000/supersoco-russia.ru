<?php

namespace Aspro\Lite\Marketplace\Adapters;

use Aspro\Lite\Marketplace\Traits\Summary;

abstract class Base
{
    use Summary;

    /** @var \Aspro\Lite\Marketplace\Services\Base Service */
    protected $service = null;
    /** @var array Results of successful operations */
    protected $result = [];
    /** @var array Mapping sections and properties */
    protected $mapping = [];

    /**
     * Set mapping for sections and properties
     *
     * @param $mapping
     * @return void
     */
    public function loadMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Add success result
     *
     * @param $result
     * @return void
     */
    public function addResult($result)
    {
        $this->result[] = $result;
    }

    /**
     * Get a successful result
     *
     * @return array|null
     */
    public function getResults(): ?array
    {
        return $this->result;
    }

    /**
     * Checking successful result
     *
     * @return bool
     */
    public function hasResults(): bool
    {
        return (boolean)$this->result;
    }

    /**
     * Encoding to site charset
     *
     * @param $value
     * @return mixed
     */
    public function encoding($value)
    {
        return \Bitrix\Main\Text\Encoding::convertEncoding($value, 'UTF-8', LANG_CHARSET);
    }

    /**
     * Encoding to UTF
     *
     * @param $value
     * @return mixed
     */
    public function utf($value)
    {
        return \Bitrix\Main\Text\Encoding::convertEncoding($value, LANG_CHARSET, 'UTF-8');
    }
}