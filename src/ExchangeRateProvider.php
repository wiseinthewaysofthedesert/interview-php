<?php declare(strict_types=1);


namespace Money;


interface ExchangeRateProvider
{
    public function getFor(Currency $from, Currency $to): CurrencyPair;
}