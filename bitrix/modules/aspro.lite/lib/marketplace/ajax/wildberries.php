<?php

namespace Aspro\Lite\Marketplace\Ajax;

use \Bitrix\Main\Application;
use \Aspro\Lite\Marketplace\Adapters\Wildberries as Adapters;

class Wildberries
{
    protected $alias = 'wildberries';

    protected $request = null;

    protected $adapver = null;

    public function __construct()
    {
        $this->adapver = new Adapters();
    }

    public function checkRequest($request): bool
    {
        if (!$request->isAjaxRequest()) {
            return false;
        }

        if ($request->get('controller') === $this->alias) {
            return true;
        }

        return false;
    }

    public function getCategoriesForSelect($searchText): array
    {
        $categories = $this->adapver->getServiceCategories($searchText);

        $result = [];

        $result[] = [
            'id' => '',
            'text' => '-'
        ];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category['NAME'],
                'text' => $category['NAME'],
            ];
        }

        return $this->adapver->encoding($result);
    }

    public function getCategoryProperties($categoryName): array
    {
        $properties = $this->adapver->getServiceCategoryProperties($categoryName);

        $result = [];

        foreach ($properties as $property) {
            $result[] = [
                'code' => $property['code'] ?: $property['type'],
                'name' => $property['name'] ?? $property['type'],
                'isRequired' => $property['is_required']
            ];
        }

        return $this->adapver->encoding($result);;
    }


}