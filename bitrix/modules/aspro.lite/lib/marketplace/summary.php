<?php

namespace Aspro\Lite\Marketplace;

class Summary
{
    /** @var array Collection of items */
    private $items = [];

    private $group = [];

    public function beginGroup($key, $label = '', array $payload = [])
    {
        $this->group = [
            'key' => $key,
            'label' => $label,
            'payload' => $payload,
        ];
    }

    public function endGroup()
    {
        $this->group = [];
    }

    public function addMany($items)
    {
        if ($this->checkAndCreateGroup()) {
            $this->items[$this->group['key']]['messages'] = array_merge($this->items[$this->group['key']]['messages'], $items);
        } else {
            $this->items = array_merge($this->items, $items);
        }
    }

    public function add($message)
    {
        if ($this->checkAndCreateGroup()) {
            $this->items[$this->group['key']]['messages'][] = $message;
        } else {
            $this->items[] = $message;
        }
    }

    public function hasItems(): bool
    {
        return (bool)$this->items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getGroupSummary($groupKey, $delimiter = '<br/>'): string
    {
        if(!isset($this->items[$groupKey])) {
            return '';
        }

        $items = $this->items[$groupKey];

        if(is_array($items)) {
            $info = $items['label'];
            foreach ($items['messages'] as $message) {
                $info .= '  - ' . $message . $delimiter;
            }
        } else {
            $info = $items;
        }

        return $info;
    }
    
    public function getGroupsSummary($delimiter = '<br/>'): string
    {
        $info = '';
        foreach ($this->items as $groupItem) {
            $info .= $groupItem['label'];
            foreach ($groupItem['messages'] as $message) {
                $info .= '  - ' . $message . $delimiter;
            }
        }

        return $info;
    }

    public function clear(): bool
    {
        $this->items = [];

        return true;
    }

    protected function checkAndCreateGroup(): bool
    {
        if(!$this->group) {
            return false;
        }

        if (!isset($this->items[$this->group['key']])) {
            $this->items[$this->group['key']] = [
                'label' => $this->group['label'] ? '<h3>'.$this->group['label'].'</h3>' : '',
                'messages' => [],
                'payload' => $this->group['payload'],
            ];
        }

        return true;
    }
}