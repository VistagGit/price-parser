<?php

namespace Vistag\PriceParser;


class StringToCurrencyParser
{
    protected $lookUpTable = [];

    protected $input;

    public function __construct($input = null)
    {
        $this->input = strtolower($input);
        $this->buildLookUpTable();
    }

    protected function buildLookUpTable()
    {
        $currencies = json_decode(file_get_contents(__DIR__ . '/currencies.json'), true);
        
        foreach ($currencies as $currencyCode=>$strings) {
            foreach ($strings as $string) {
                $this->lookUpTable[strtolower($string)] = $currencyCode;
            }
        }
    }

    public function price($input = null)
    {
        $this->input = strtolower($input);
    }

    public function get()
    {
        if (is_null($this->input)) {
            return null;
        }

        return $this->parseCurrency($this->input);
    }

    protected function parseCurrency($value)
    {
        $value = str_replace(range(0, 9), ' ', $value);
        $value = str_replace([',', '.'], ' ', $value);
        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value);

        return empty($value) ? null : $this->matchCurrency($value);
    }

    protected function matchCurrency($value)
    {
        $values = explode(' ', $value);

        foreach ($values as $currency) {
            if (isset($this->lookUpTable[$currency])) {
                return $this->lookUpTable[$currency];
            }
        }

        return null;
    }
}