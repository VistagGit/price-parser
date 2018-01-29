<?php

namespace Vistag\PriceParser;


use function FastRoute\TestFixtures\empty_options_cached;

class StringToPriceParser
{
    protected $input;

    public function __construct($input = null)
    {
        $this->input = strtolower($input);
    }

    public function price($input = null)
    {
        $this->input = $input;
    }

    public function get()
    {
        if (is_null($this->input)) {
            return null;
        }

        return $this->parsePrice($this->input);
    }

    protected function parsePrice($value)
    {
        $value = $this->sanitize($value);
        $delimiter = $this->getDelimiter($value);

        $result = preg_replace('/[^0-9\\' . $delimiter . ']/', '', $value);

        $result = $this->replaceTailDelimiter($result, $delimiter);

        $result = str_replace(',', '.', $result);

        if (strlen($this->getDelimiters($result)) > 1) {
            $result = str_replace('.', '', $result);
        }

        $parts = explode('.', $result);

        if (isset($parts[1]) && strlen($parts[1] ?? '')>2) {
            $result = str_replace('.', '', $result);
        }

        return $result;
    }

    protected function sanitize($value)
    {
        return preg_replace('/[^0-9\.\,]/', '', $value);
    }

    protected function getDelimiter($value)
    {
        $delimiters = $this->getDelimiters($value);

        return empty($delimiters) ? '.' : $delimiters[strlen($delimiters) - 1];
    }

    protected function getDelimiters($value)
    {
        return preg_replace('/[0-9]/', '', $value);
    }

    protected function replaceTailDelimiter($value, $delimiter)
    {
        if (empty($delimiter)) {
            return $value;
        }

        $index = strlen($value) - 1;

        if ( ($value[$index] ?? '') === $delimiter) {
            $value = substr($value, 0, -1);
        }

        return $value;
    }
}