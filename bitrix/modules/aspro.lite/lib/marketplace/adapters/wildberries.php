<?php
/* !!!ATTENTION!!! */
/*This file is UTF-8 ONLY DO NOT CHANGE CHARSET*/
namespace Aspro\Lite\Marketplace\Adapters;

use Aspro\Lite\Marketplace\Services\Wildberries as WildberriesService;
use \Bitrix\Main\Localization\Loc;
use \Aspro\Lite\Marketplace\Maps\Wildberries as Map;
use Aspro\Lite\Marketplace\Config\Wildberries as Config;

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/aspro.lite/lib/marketplace/wildberries.php');

class Wildberries extends Base
{
    const REQUIRED_FIELD_COUNTRY = 'COUNTRY';
    const REQUIRED_FIELD_VENDOR_CODE = 'VENDOR_CODE';
    const REQUIRED_FIELD_BARCODE = 'BARCODE';
    const REQUIRED_FIELD_PRICE = 'PRICE';
    const REQUIRED_FIELD_IMAGES = 'IMAGES';
    const NAME_SIZE = 'Размер';
    const NAME_RUS_SIZE = 'Рос. размер';

    protected $scopeStructures = [];

    public function __construct($token = '')
    {
        $this->service = new WildberriesService(self::getToken());
    }

    /**
     * Update item on the Service
     *
     * @param array $item See method getBuildItem
     * @param array $payload
     *  [
     *      'ELEMENT_ID' => Bitrix element ID
     *      'ELEMENT_NAME' => Bitrix element NAME
     *  ]
     *
     * @return boolean
     */
    public function updateServiceItem(array $item, array $payload): bool
    {
        $this->service->clearErrors();

        $result = $this->service->cardsUpdate([$item]);

        $this->callbackCreateOrUpdate($result, $payload);

        return (boolean)$result;
    }

    /**
     * Update item on the Service
     *
     * @param array $item See method getBuildItem
     * @param array $payload
     *  [
     *      'ELEMENT_ID' => Bitrix element ID
     *      'ELEMENT_NAME' => Bitrix element NAME
     *  ]
     *
     * @return boolean
     */
    public function updateServiceItems(array $items, array $payload): bool
    {
        $this->service->clearErrors();

        $result = $this->service->cardsUpdate($items);

        $this->callbackCreateOrUpdateItems($result, $payload);

        return (boolean)$result;
    }

    /**
     * Create item on the Service
     *
     * @param array $item See method getBuildItem
     * @param array $payload
     *  [
     *      'ELEMENT_ID' => Bitrix element ID
     *      'ELEMENT_NAME' => Bitrix element NAME
     *  ]
     *
     * @return boolean
     */
    public function createServiceItem(array $item, array $payload): bool
    {
        $this->service->clearErrors();

        $result = $this->service->cardsCreate([$item]);

        $this->callbackCreateOrUpdate($result, $payload);

        return (boolean)$result;
    }

    /**
     * Create items on the Service
     *
     * @param array $item See method getBuildItem
     * @param array $payload
     *  [
     *      'ELEMENT_ID' => Bitrix element ID
     *      'ELEMENT_NAME' => Bitrix element NAME
     *  ]
     *
     * @return boolean
     */
    public function createServiceItems(array $items, array $payload): bool
    {
        $this->service->clearErrors();
        
        $result = $this->service->cardsCreate($items);

        $this->callbackCreateOrUpdateItems($result, $payload);

        return (boolean)$result;
    }

    /**
     *  Loading prices.
     *
     * @param array $prices
     * [
     *      [
     *          "nmId" => 1234567, (int)
     *          "price" => 1000    (int)
     *      ],
     *      ...
     * ]
     */
    public function updateServicePrices(array $prices)
    {
        $this->service->clearErrors();

        $result = $this->service->updatePrices($prices);

        if($this->service->hasErrors()) {
            $this->addErrors($this->service->getErrors());
        }

        $this->addResult([
            'RESULT' => $result
        ]);
    }

    /**
     * Loading stocks.
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
     */
    public function updateServiceStocks(array $stocks)
    {
        $result = $this->service->updateStocks($stocks);

        $this->addResult([
            'RESULT' => $result
        ]);
    }

    /**
     * Get items from Service
     *
     * @param array $params Filtering params
     * @return array[]
     */
    public function getServiceItems(array $params = []): array
    {
        return $this->service->getCardList($params);
    }

    /**
     * Get items with errors from Service
     *
     * @return array[]
     */
    public function getErrorServiceItems(): array
    {
        return $this->service->getErrorCardList();
    }

    /**
     * Get categories from Service
     *
     * @param string $categoryName Category name
     * @return array
     */
    public function getServiceCategories(string $categoryName): array
    {
        $categories = $this->service->getObjectList($categoryName);
        
        if (!$categories) {
            return [];
        }
        
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'NAME' => $category['objectName'],
                'PARENT_NAME' => $category['parentName']
            ];
        }

        return $result;
    }

    /**
     * Get properties by category from Service
     *
     * @param string $categoryName Category name
     * @param boolean $withSectionInfo If you need name root category
     * @return array
     *  $withSectionInfo = true
     *  [
     *      'object' => 'Category name'
     *      'parent' => 'Root category name',
     *      'properties' => [...]
     *  ]
     *
     *  $withSectionInfo = false
     *  [...] only properties
     */
    public function getServiceCategoryProperties(string $categoryName, bool $withSectionInfo = false): array
    {
        $objectTranslated = $this->service->getObjectTranslated($categoryName);

        if ($this->service->hasErrors()) {
            $this->addErrors($this->service->getErrors());
        }

        if (!$objectTranslated) {
            return [];
        }

        $resultProperties = [];
        
        foreach ($objectTranslated as $property) {
            if($property['name'] === 'SKU')
                continue;
            $resultProperties[$property['name']] = $this->getPrepareProperty($property);
        }

        $scopeProperties = array_merge($this->getRequiredCategoryProperties(), $resultProperties);

        unset($resultProperties);

        if (!$withSectionInfo) {
            $result = $scopeProperties;
        } else {
            $result = [
                'object' => $categoryName,
                'properties' => $scopeProperties,
            ];
        }

        return $result;
    }

    /**
     * Get list warehouses
     *
     * @return array
     */
    public function getServiceWarehouses(): array
    {
        $result = $this->service->getWarehouses();

        return $result ?: [];
    }

    /**
     * Get a list barcodes
     *
     * @return array
     */
    public function getServiceBarcodes($quantity = 1): array
    {
        $result = $this->service->getBarcodes($quantity);

        return $result ?: [];
    }

    /**
     * update media files
     * 
    */
    public function updateMedia($vendoreCode, $media): array
    {
        $result = $this->service->uploadMedia($vendoreCode, (array)$media);        

        return $result ?: [];
    }


    /**
     * Get required properties on the Service
     *
     * @return array[]
     */
    protected function getRequiredCategoryProperties(): array
    {
        return [
            self::REQUIRED_FIELD_VENDOR_CODE => [
                'code' => self::REQUIRED_FIELD_VENDOR_CODE,
                'type' => 'Артикул',
                'name' => 'Артикул',
                'is_required' => true,
                'is_number' => false,
                'is_system' => true,
                'is_available' => false,
                'is_nomenclature' => false,
                'is_variation' => false,
            ],
            self::REQUIRED_FIELD_BARCODE => [
                'code' => self::REQUIRED_FIELD_BARCODE,
                'type' => 'Штрихкод',
                'name' => 'Штрихкод',
                'is_required' => true,
                'is_number' => false,
                'is_system' => true,
                'is_available' => false,
                'is_nomenclature' => false,
                'is_variation' => false,
            ],
            self::REQUIRED_FIELD_IMAGES => [
                'code' => self::REQUIRED_FIELD_IMAGES,
                'type' => 'Фото',
                'name' => 'Фото',
                'is_required' => true,
                'is_number' => false,
                'is_system' => false,
                'is_available' => false,
                'is_nomenclature' => true,
                'is_variation' => false,
            ],
            self::REQUIRED_FIELD_PRICE => [
                'code' => self::REQUIRED_FIELD_PRICE,
                'type' => 'Розничная цена',
                'name' => 'Розничная цена',
                'is_required' => true,
                'is_number' => true,
                'is_system' => false,
                'is_available' => false,
                'is_nomenclature' => false,
                'is_variation' => true,
            ],
        ];
    }

    /**
     * Build item for Service
     *
     * @param array $element Bitrix element property values
     * @return array|null
     */
    public function getBuildItem(array $element): ?array
    {
        $structure = $this->getBuildScopeStructure($element['IBLOCK_SECTION_ID']);

        $errorValidate = false;
        if (!$this->checkRequiredValues($structure, $element)) {
            $errorValidate = true;
        }

        if ($element['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_PRODUCT) {
            if (!$this->checkRequiredNomenclatureValues($structure, $element)) {
                $errorValidate = true;
            }
        }

        if ($element['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SKU && $element['OFFERS']) {
            /** here logic for validate sku offers ($arElement['OFFERS'])  */
        }

        if (!$this->checkPropertyValues($structure, $element)) {
            $errorValidate = true;
        }

        if ($errorValidate) {
            $this->addResult([
                'ELEMENT_ID' => $element['ID'],
                'ELEMENT_NAME' => $element['NAME'],
                'RESULT' => false,
                'STATUS_CODE' => Config::STATUS_ERROR,
                'WARNINGS' => $this->getGroupErrorSummary("element-item-$element[ID]", PHP_EOL)
            ]);

            return null;
        }

        $imtId = (int)$element[Config::PROPERTY_IMT_ID] ?? null;

        $characteristics = $this->getBuildItemProperties($structure['common_properties'], $element);
        $characteristics[]['Предмет'] = $structure['object'];

        $item = array_filter([
            'imtID' => (int)$element[Config::PROPERTY_IMT_ID] ?? null,
            'nmID' => (int)$element[Config::PROPERTY_NM_ID] ?? null,
            'characteristics' => $characteristics,
            'sizes' => $this->getBuildNomenclatures($structure, $element),
            'vendorCode' => $element[$structure['vendor_field']],
        ]);

        return $item;
    }

    /**
     * Build the structure on the map
     *
     * @param int|string $iblockSectionId Bitrix section Id
     * @return array
     */
    protected function getBuildScopeStructure($iblockSectionId): array
    {
        if (isset($this->scopeStructures[$iblockSectionId]) && $this->scopeStructures[$iblockSectionId]) {
            return $this->scopeStructures[$iblockSectionId];
        }

        $mapCategory = $this->getMap($iblockSectionId);

        $countryPropertyCode = $mapCategory['properties'][self::REQUIRED_FIELD_COUNTRY]['bx_property_code'] ?? null;
        $vendorPropertyCode = $mapCategory['properties'][self::REQUIRED_FIELD_VENDOR_CODE]['bx_property_code'] ?? null;
        $barcodePropertyCode = $mapCategory['properties'][self::REQUIRED_FIELD_BARCODE]['bx_property_code'] ?? null;
        $pricePropertyCode = $mapCategory['properties'][self::REQUIRED_FIELD_PRICE]['bx_property_code'] ?? null;

        $allProperties = array_filter($mapCategory['properties'] ?? [], function ($propertyStructure) {
            if (isset($propertyStructure['is_system']) && $propertyStructure['is_system'] === true) {
                return false;
            }

            return true;
        });

        $commonProperties = array_filter($allProperties, function ($propertyStructure) {
            if (
                (isset($propertyStructure['is_variation']) && $propertyStructure['is_variation'] === true)
                || (isset($propertyStructure['is_nomenclature']) && $propertyStructure['is_nomenclature'] === true)

            ) {
                return false;
            }

            return true;
        });

        $variationProperties = array_filter($mapCategory['properties'] ?? [], function ($propertyStructure) {
            if (isset($propertyStructure['is_variation']) && $propertyStructure['is_variation'] === true) {
                return true;
            }

            return false;
        });

        $nomenclatureProperties = array_filter($mapCategory['properties'] ?? [], function ($propertyStructure) {
            if (isset($propertyStructure['is_nomenclature']) && $propertyStructure['is_nomenclature'] === true) {
                return true;
            }

            return false;
        });

        $this->scopeStructures[$iblockSectionId] = [
            'object' => $mapCategory['object'] ?? null,
            'parent' => $mapCategory['parent'] ?? null,
            'country_field' => $countryPropertyCode,
            'vendor_field' => $vendorPropertyCode,
            'barcode_field' => $barcodePropertyCode,
            'price_field' => $pricePropertyCode,
            'all_properties' => $allProperties,
            'common_properties' => $commonProperties,
            'nomenclature_properties' => $nomenclatureProperties,
            'variation_properties' => $variationProperties,
        ];

        return $this->scopeStructures[$iblockSectionId];
    }

    /**
     * Validate required properties
     *
     * @param array $structure See getBuildScopeStructure
     * @param array $element Bitrix element property values
     * @return bool
     */
    protected function checkRequiredValues(array $structure, array $element): bool
    {
        $error = false;

        $this->beginErrorGroup("element-item-$element[ID]", "$element[NAME][ID:$element[ID]]", [
            'ELEMENT_ID' => $element['ID'],
        ]);

        if (
            !isset($structure['object'])
            || !$structure['object']
        ) {
            $this->addError($this->utf(Loc::getMessage('AS_ERROR_REQUIRED_SECTION')));

            $error = true;
        }

        $this->endErrorGroup();

        return !$error;
    }

    /**
     * Validate required nomenclature properties
     *
     * @param array $structure See getBuildScopeStructure
     * @param array $element Bitrix element property values
     * @return bool
     */
    protected function checkRequiredNomenclatureValues(array $structure, array $element): bool
    {
        $error = false;

        $this->beginErrorGroup("element-item-$element[ID]", "$element[NAME][ID:$element[ID]]", [
            'ELEMENT_ID' => $element['ID'],
        ]);
        
        if (!isset($element[$structure['barcode_field']]) || !$element[$structure['barcode_field']]) {
            $this->addError($this->utf(Loc::getMessage('AS_ERROR_REQUIRED_BARCODE')));

            $error = true;
        }

        if (!isset($element[$structure['vendor_field']]) || !$element[$structure['vendor_field']]) {
            $this->addError($this->utf(Loc::getMessage('AS_ERROR_REQUIRED_ARTICLE')));

            $error = true;
        }

        $this->endErrorGroup();

        return !$error;
    }

    /**
     * Validate properties
     *
     * @param array $structure See getBuildScopeStructure
     * @param array $element Bitrix element property values
     * @return bool
     */
    protected function checkPropertyValues(array $structure, array $element): bool
    {
        $error = false;

        $this->beginErrorGroup("element-item-$element[ID]", "$element[NAME][ID:$element[ID]]", [
            'ELEMENT_ID' => $element['ID'],
        ]);

        foreach ($structure['all_properties'] as $propertyStructure) {
            $value = $element[$propertyStructure['bx_property_code']]
                ?? $element[$propertyStructure['default_value']] // If default_value is a property
                ?? $propertyStructure['default_value']
                ?? null;

            if (
                $propertyStructure['is_required'] === true
                && !$value
            ) {
                $label =
                    $propertyStructure['name']
                        ?: $propertyStructure['bx_property_label']
                        ?: $propertyStructure['bx_property_code']
                        ?: $propertyStructure['wb_property_code'];

                $this->addError($this->utf(Loc::getMessage('AS_ERROR_REQUIRED', [
                    '#CODE#' => $this->encoding($label),
                ])));

                $error = true;
            }
        }

        $this->endErrorGroup();

        return !$error;
    }

    /**
     * Build value object for property
     *
     * @param array $propertyStructures See getBuildScopeStructure
     * @param array $element Bitrix element property values
     * @return array
     */
    protected function getBuildItemProperties(array $propertyStructures, array $element): array
    {
        $result = [];

        foreach ($propertyStructures as $servicePropertyCode => $propertyStructure) {
            $value = $element[$propertyStructure['bx_property_code']]
                ?: $element[$propertyStructure['default_value']] // If default_value is a property
                ?? $propertyStructure['default_value']
                    ?: null;

            $params = [];
            if (isset($propertyStructure['is_text']) && $propertyStructure['is_text']) {
                $value = strip_tags($value);
                $value = str_replace(['—'], '-', $value);
            }

            $propValue = '';

            if($value){
                if(is_array($value)){
                    $arValues = [];
                    foreach ($value as $_value) {
                        if ($propertyStructure['is_number']) {
                            $arValues[] = round((float)$_value, 2);
                        } else {
                            $arValues[] = $this->getBuildItemPropertyValue($propertyStructure['type'], $_value);
                        }
                    }
                    $propValue = $arValues;
                } else {
                    if ($propertyStructure['is_number']) {
                        $value = round((float)$value, 2);
                        $propValue = $value;
                    } else {
                        $value = $this->getBuildItemPropertyValue($propertyStructure['type'], $value);
                        $propValue = [$value];
                    }
                }
            }

            if ($propValue) {
                $result[] = [
                    $propertyStructure['type'] => $propValue
                ];
            }
        }

        return $result;
    }

    /**
     * Additional processing of values by property type
     *
     * @param string $propertyType WB property type
     * @param string $propertyValue Value
     * @return string
     */
    protected function getBuildItemPropertyValue(string $propertyType, string $propertyValue): string
    {
        if($propertyType === 'Основной цвет') {
            return (string) mb_strtolower($propertyValue, 'UTF-8');
        }

        if($propertyType === 'Доп. цвета') {
            return (string) mb_strtolower($propertyValue, 'UTF-8');
        }

        return (string) $propertyValue;
    }

    /**
     * Build nomenclature object for item
     *
     * @param array $structure See getBuildScopeStructure
     * @param array $element Bitrix element product or element with sku offers
     * @return array
     */
    protected function getBuildNomenclatures(array $structure, array $element): array
    {
        $result = [];

        if ($element['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_PRODUCT) {    
            
            $techSize= $rusSize = '';
            if(isset($structure['variation_properties'][self::NAME_SIZE]["bx_property_code"]))
                $techSize = $element[$structure['variation_properties'][self::NAME_SIZE]["bx_property_code"]];

            if(isset($structure['variation_properties'][self::NAME_RUS_SIZE]["bx_property_code"]))
                $rusSize = $element[$structure['variation_properties'][self::NAME_RUS_SIZE]["bx_property_code"]];
            
            
            $productInfo = [
                'techSize' => (string)$techSize ?: '',
                'wbSize' => (string)$rusSize ?: '',
                'skus' => [$element[$structure['barcode_field']]],
                'price' => (int)$element[$structure['price_field']],
            ];
            if((int)$element[Config::PROPERTY_CHRT_ID]){
                $productInfo['chrtId'] = (int)$element[Config::PROPERTY_CHRT_ID];
            }
            $result[] = $productInfo;
        }

        if ($element['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SKU && $element['OFFERS']) {
            /** here logic for build sku offers ($arElement['OFFERS'])  */
        }

        return $result;
    }

    /**
     * Get mapping
     *
     * @param int|string $sectionId Bitrix section id
     * @return array
     */
    protected function getMap($sectionId = null): array
    {
        return $sectionId && $this->mapping[Map::GROUP_SECTIONS][$sectionId]
            ? $this->mapping[Map::GROUP_SECTIONS][$sectionId]
            : $this->mapping[Map::GROUP_SECTIONS]
            ?? [];
    }

    /**
     * Helper, default values for property
     *
     * @param array $property
     * @param array $appended
     * @return array
     */
    private function getPrepareProperty(array $property, array $appended = []): array
    {
        return array_merge([
            'type' => $property['name'],
            'is_available' => $property['isAvailable'] ?? false,
            'is_number' => $property['charcType'] == 4 ?? false,
            'is_required' => $property['required'] ?? false,
            'is_variation' => $property['name'] === self::NAME_SIZE || $property['name'] === self::NAME_RUS_SIZE ?: false,
        ], $appended);
    }

    /**
     * Helper. Callback function after doing save or update service card
     *
     * @param $result
     * @param array $payload
     * @return void
     *
     */
    private function callbackCreateOrUpdate($result, array $payload)
    {
        $hasErrors = $this->service->hasErrors();

        if ($hasErrors) {
            $label = "$payload[ELEMENT_NAME] [ID: $payload[ELEMENT_ID]]";

            $this->beginErrorGroup("element-item-$payload[ELEMENT_ID]", $label, $payload);
            $this->addErrors($this->service->getErrors());
            $this->endErrorGroup();
        }

        if (!$result || $result['error'] === true) {
            $status = Config::STATUS_ERROR;
        } else {
            if ($hasErrors) {
                $status = Config::STATUS_WARNING;
            } else {
                $status = Config::STATUS_SUCCESS;
            }
        }

        $this->addResult(array_merge($payload, [
            'RESULT' => $result ?? false,
            'STATUS_CODE' => $status,
            'WARNINGS' => $hasErrors ? $this->service->getErrorSummary(PHP_EOL) : ''
        ]));
    }

    /**
     * Helper. Callback function after doing save or update service card
     *
     * @param $result
     * @param array $payload
     * @return void
     *
     */
    private function callbackCreateOrUpdateItems($result, array $payload)
    {
        $hasErrors = $this->service->hasErrors();

        if ( !$result || $result['error'] === true ) {
            $status = Config::STATUS_ERROR;
        } else {
            if ($hasErrors) {
                $status = Config::STATUS_WARNING;
            } else {
                $status = Config::STATUS_SUCCESS;
            }
        }

        foreach ($payload as $arItem) {
            $this->addResult(array_merge($arItem, [
                'RESULT' => $result ?? false,
                'STATUS_CODE' => $status,
                'WARNINGS' => $hasErrors ? $this->service->getErrorSummary(PHP_EOL) : ''
            ]));
        }
    }

    static public function getToken(): string
    {
        return Config::getApiKey();
    }
}