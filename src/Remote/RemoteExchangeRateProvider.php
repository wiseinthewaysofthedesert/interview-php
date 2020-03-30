<?php declare(strict_types=1);


namespace Money\Remote;


use Money\Currency;
use Money\CurrencyPair;
use Money\ExchangeRateProvider;

/** written by candidate **/
class RemoteExchangeRateProvider implements ExchangeRateProvider
{
    public function getFor(Currency $from, Currency $to): CurrencyPair
    {
        // TODO: Implement getFor() method.
    }
}