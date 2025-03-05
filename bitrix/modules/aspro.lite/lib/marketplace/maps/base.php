<?php

namespace Aspro\Lite\Marketplace\Maps;

use Aspro\Lite\Marketplace\Traits\Summary;

abstract class Base
{
    use Summary;

    /** @var int Id инфоблока */
    protected $iblockId = null;

    /** @var array Свойства */
    protected $allProperties = [];

    /** @var array Данные для сохранения */
    protected $mapToSave = [];

    /** @var array Данные из файла */
    protected $mapStructure = [];

    public function __construct($iblockId)
    {
        $this->iblockId = $iblockId;
    }

    abstract public function setPostData(array $dataSections, array $dataStores = []);

    /**
     * Сохранить
     */
    public function saveMap()
    {
        //file_put_contents($this->getFilePath(), \Bitrix\Main\Web\Json::encode($this->mapToSave));
        \Bitrix\Main\IO\File::putFileContents($this->getFilePath(), \Bitrix\Main\Web\Json::encode($this->mapToSave));
    }

    /**
     * Получить данные структуры из файла
     *
     * @return array
     */
    public function getMapStructure(): array
    {
        if ($this->mapStructure) {
            return $this->mapStructure;
        }


        if(!file_exists($this->getFilePath())) {
            return [];
        }

        $fileContent = file_get_contents($this->getFilePath());

        $contentArray = \Bitrix\Main\Web\Json::decode($fileContent);

        $this->mapStructure = $contentArray ?? [];

        return $this->mapStructure;
    }

    /**
     * Директория
     *
     * @return string
     */
    protected function getDir(): string
    {
        return __DIR__ . '/json/';
    }

    /**
     * Файл
     *
     * @return string
     */
    protected function getFileName(): string
    {
        $explode = explode('\\', static::class);

        $alias = mb_strtolower(array_pop($explode));

        return "map_iblock_{$this->iblockId}_{$alias}.json";
    }

    /**
     * Путь до файла
     *
     * @return string
     */
    protected function getFilePath(): string
    {
        return $this->getDir() . $this->getFileName();
    }

    /**
     * Свойства
     *
     * @return array
     */
    protected function getAllProperties(): array
    {
        if ($this->allProperties) {
            return $this->allProperties;
        }

        $rsProperties = \CIBlockProperty::GetList(
            ['SORT' => 'ASC', 'NAME' => 'ASC'],
            ['IBLOCK_ID' => $this->iblockId, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N']
        );

        while ($arProperty = $rsProperties->Fetch()) {
            $arItem = $arProperty;

            $this->allProperties[$arProperty['CODE']] = $arItem;
        }

        $this->allProperties = \Bitrix\Main\Text\Encoding::convertEncoding($this->allProperties, LANG_CHARSET, 'UTF-8');

        return $this->allProperties;
    }


}