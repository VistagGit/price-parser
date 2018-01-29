<?php

namespace Vistag\PriceParser;


class StringToCurrencyParser
{
    protected $currencyTable = [
        'USD' => [
            'usd',
            'dollar',
            '$',
            '&#36;',
            '&#65129;',
            '&#65284;',
            '&#128178;',
        ],
        'GBP' => [
            'gbp',
            'pound',
            'libra',
            '£',
            '&pound;',
            '&#163;',
        ],
        'EUR' => [
            'eur',
            'euro',
            '€',
            '&euro;',
            '&#8364;',
        ],
        'CZK' => [
            'czk',
            'crown',
            'czech',
            'korun',
            'koruna',
            'kc',
            'kč',
        ],
    ];

    protected $lookUpTable = [];

    protected $input;

    public function __construct($input = null)
    {
        $this->input = strtolower($input);
        $this->buildLookUpTable();
    }

    protected function buildLookUpTable()
    {
        foreach ($this->currencyTable as $currencyCode=>$strings) {
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