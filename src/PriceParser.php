<?php

namespace Vistag\PriceParser;


class PriceParser
{
    protected $isValid = false;

    protected $price;
    protected $currency;

    public function __construct($input)
    {
        $this->input = $input;

        $this->price = (new StringToPriceParser($input))->get();
        $this->currency = (new StringToCurrencyParser($input))->get();
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function isValid(): bool
    {
        return ! empty($this->price);
    }

    public function getFormatted()
    {
        $value = $this->getPrice() ?? 0;
        $decimals = (floor($value) == $value) ? 0 : 2;
        
        switch($this->getCurrency()) {
            case 'USD':
                $price = '$' . number_format($value, $decimals, '.', '');
                break;
            case 'GBP':
                $price = '£' . number_format($value, $decimals, '.', '');
                break;
            case 'CZK':
                $price = number_format($value, $decimals, ',', '') . ' Kč';
                break;
            case 'RON':
                $price = number_format($value, $decimals, ',', '') . ' lei';
                break;
            default:
                $price = number_format($value, $decimals, ',', '') . ' €';
        }

        return $price;
    }

    protected function parsePrice($value)
    {
        // Keep numbers and delimiters only
        $value = preg_replace('/[^0-9\.\,]/', '', $value);

        $allDelimiters = $this->getDelimitersOnly($value);
        $delimiter = empty($allDelimiters) ? '.' : $allDelimiters[strlen($allDelimiters) - 1];

        $result = preg_replace('/[^0-9\\' . $delimiter . ']/', '', $value);

        // Replace delimiter from the tail
        if ($result[strlen($result) - 1] ?? '' === $delimiter) {
            $result = substr($result, 0, -1);
        }

        $result = str_replace(',', '.', $result);

        if (strlen($this->getDelimitersOnly($result)) > 1) {
            $result = str_replace('.', '', $result);
        }

        $parts = explode('.', $result);

        if (isset($parts[1]) && strlen($parts[1] ?? '')>2) {
            $result = str_replace('.', '', $result);
        }

        return $result;
    }

    protected function getDelimitersOnly($value)
    {
        return preg_replace('/[0-9]/', '', $value);
    }
}