# Price Parser
Extract price and currency from given string

## Installation

You can install this package via composer using this command:

``` bash
 composer require vistag/price-parser
```

## Usage

``` php
use Vistag\PriceParser\PriceParser;


$priceParser = new PriceParser('120 Kč');

$priceParser->getPrice();
// 120
$priceParser->getCurrency();
// CZK


$priceParser = new PriceParser('$ 1,210,000.10');

$priceParser->getPrice();
// 1210000.1
$priceParser->getCurrency();
// USD
```

## Support

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/VistagGit/price-parser/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!


## License

The MIT License (MIT). [Vistag.com](https://vistag.com)