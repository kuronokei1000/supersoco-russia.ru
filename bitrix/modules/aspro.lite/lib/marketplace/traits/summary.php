<?php

namespace Aspro\Lite\Marketplace\Traits;

trait Summary
{
    /** @var array Collection of errors */
    private $errors = [];

    /** @var array Action summary collection */
    private $summary = [];

    private $errorGroup = [];

    public function beginErrorGroup($key, $label, array $payload = [])
    {
        $this->errorGroup = [
            'key' => $key,
            'label' => $label,
            'payload' => $payload,
        ];
    }

    public function endErrorGroup()
    {
        $this->errorGroup = [];
    }

    public function addErrors($errors)
    {
        if ($this->checkAndCreateErrorGroup()) {
            $this->errors[$this->errorGroup['key']]['messages'] = array_merge($this->errors[$this->errorGroup['key']]['messages'], $errors);
        } else {
            $this->errors = array_merge($this->errors, $errors);
        }
    }

    public function addError($message)
    {
        if ($this->checkAndCreateErrorGroup()) {
            $this->errors[$this->errorGroup['key']]['messages'][] = $message;
        } else {
            $this->errors[] = $message;
        }
    }

    public function hasErrors(): bool
    {
        return (bool)$this->errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getGroupErrorSummary($groupKey, $delimiter = '\n'): string
    {
        if(!isset($this->errors[$groupKey])) {
            return '';
        }

        $error = $this->errors[$groupKey];

        if(is_array($error)) {
            $errorText = $error['label'] . $delimiter;
            foreach ($error['messages'] as $message) {
                $errorText .= '  - ' . $message . $delimiter;
            }
        } else {
            $errorText = $error;
        }

        return $errorText;
    }

    public function getErrorSummary($delimiter = '\n'): string
    {
        return implode($delimiter, array_map(function($error) use($delimiter) {
            if(is_array($error)) {
                $errorText = $error['label'] . $delimiter;
                foreach ($error['messages'] as $message) {
                    $errorText .= '  - ' . $message . $delimiter;
                }
            } else {
                $errorText = $error;
            }

            return $errorText;
        }, $this->errors));
    }

    public function clearErrors(): bool
    {
        $this->errors = [];

        return true;
    }

    public function addSummary($message)
    {
        $this->summary[] = $message;
    }

    public function getSummary($delimiter = '\n'): string
    {
        return implode($delimiter, $this->summary);
    }

    protected function checkAndCreateErrorGroup(): bool
    {
        if(!$this->errorGroup) {
            return false;
        }

        if (!isset($this->errors[$this->errorGroup['key']])) {
            $this->errors[$this->errorGroup['key']] = [
                'label' => $this->errorGroup['label'],
                'messages' => [],
                'payload' => $this->errorGroup['payload'],
            ];
        }

        return true;
    }
}