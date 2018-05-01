<?php
namespace Vistag\PriceParser\Tests;

use PHPUnit\Framework\TestCase;
use Vistag\PriceParser\PriceParser;

final class PriceParserTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testPriceExtraction($input, $currency, $price)
    {
        $extractor = new PriceParser($input);
        $this->assertEquals($price, $extractor->getPrice());
    }

    /**
     * @dataProvider additionProvider
     */
    public function testCurrencyExtraction($input, $currency, $price)
    {
        $extractor = new PriceParser($input);
        $this->assertEquals($currency, $extractor->getCurrency());
    }

    public function additionProvider()
    {
        return [
            'wqeq231232'    => ['wqeq231232', null, 231232],
            'dsdad'         => ['dsdad', null, null],
            '3213111€'      => ['3213111€', 'EUR', 3213111],
            '€8765143'      => ['€8765143', 'EUR', 8765143],
            '112320 a'        => ['112320', null, 112320],
            '112320'        => ['112320', null, 112320],
            '112320 euro'   => ['112320 euro', 'EUR', 112320],
            '112.320 euro'  => ['112.320 euro', 'EUR', 112320],
            '112320.Kč'     => ['112320.Kč', 'CZK', 112320],
            '1231221kc'     => ['1231221kc', 'CZK', 1231221],
            '1 000 kc'      => ['1 000 kc', 'CZK', 1000],
            '1.000 kc'      => ['1.000 kc', 'CZK', 1000],
            '121.00 kc'     => ['121.00 kc', 'CZK', 121],
            '121,00 $'      => ['121,00 $', 'USD', 121],
            '21 211 £'      => ['21 211 £', 'GBP', 21211],
            '£ 1.21,00 '    => ['£ 1.21,00 ', 'GBP', 121],
            '$ 1,21.00 '    => ['$ 1,21.00 ', 'USD', 121],
            '$ 1,210,000.00'=> ['$ 1,210,000.00', 'USD', 1210000],
            '$ 1,210,000'   => ['$ 1,210,000', 'USD', 1210000],
            '3 095 Kč'   => ['3 095 Kč', 'CZK', 3095],
            '1 leu'   => ['1 leu', 'RON', 1],
            '3 095 lei'   => ['3 095 lei', 'RON', 3095],
            '112320.lei'     => ['112320.lei', 'RON', 112320],
            '1231221lei'     => ['1231221lei', 'RON', 1231221],
        ];
    }

    /**
     * @dataProvider additionFormatProvider
     */
    public function testCurrencyFormatter($input, $result)
    {
        $extractor = new PriceParser($input);
        $this->assertEquals($result, $extractor->getFormatted());

    }

    public function additionFormatProvider()
    {
        return [
            '1000 CZK'    => ['1000 CZK', '1000 Kč'],
            '100.5 CZK'    => ['100.5 CZK', '100,50 Kč'],
            '0.99 CZK'    => ['0.99 CZK', '0,99 Kč'],

            '1000 EUR'    => ['1000 EUR', '1000 €'],
            '100.5 EUR'    => ['100.5 EUR', '100,50 €'],
            '0.99 EUR'    => ['0.99 EUR', '0,99 €'],

            '1000 GBP'    => ['1000 GBP', '£1000'],
            '100.5 GBP'    => ['100.5 GBP', '£100.50'],
            '0.99 GBP'    => ['0.99 GBP', '£0.99'],

            '1000 USD'    => ['1000 USD', '$1000'],
            '100.5 USD'    => ['100.5 USD', '$100.50'],
            '0.99 USD'    => ['0.99 USD', '$0.99'],

            '1000 RON'    => ['1000 RON', '1000 lei'],
            '100.5 RON'    => ['100.5 RON', '100,50 lei'],
            '0.99 RON'    => ['0.99 RON', '0,99 lei'],
        ];
    }
}