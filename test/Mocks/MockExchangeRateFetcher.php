<?php declare(strict_types=1);

namespace Test\Mocks;

use Money\Currency;
use Money\CurrencyPair;
use Money\Exception\ErrorFetchingExternalExchangeRate;
use Money\ExchangeRateProvider;

/** for the interview - break this class */
class MockExchangeRateFetcher implements ExchangeRateProvider
{
    public array $pairs;

    public function __construct()
    {
        $this->pairs = [];
    }

    public function addPair($fromCode, $toCode, $rate): void {
        if (!isset($this->pairs[$fromCode])) {
            $this->pairs[$fromCode] = [];
        }

        $this->pairs[$fromCode][$toCode] = new CurrencyPair(new Currency($fromCode), new Currency($toCode), $rate);
    }

    public function getFor(Currency $from, Currency $to): CurrencyPair
    {
        if (!isset($this->pairs[$from->getCode()][$to->getCode()])) {
            throw new ErrorFetchingExternalExchangeRate('Invalid multiplier received or missing');
        }

        return $this->pairs[$from->getCode()][$to->getCode()];
    }
}