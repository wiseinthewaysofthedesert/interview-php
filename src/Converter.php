<?php declare(strict_types=1);

namespace Money;

class Converter
{
    private MoneyFactory $money;
    private History $history;
    private ExchangeRateProvider $exchangeRateFetcher;

    private static int $precision = 14;

    public function __construct(MoneyFactory $money, History $history, ExchangeRateProvider $exchangeRateFetcher)
    {
        $this->money = $money;
        $this->history = $history;
        $this->exchangeRateFetcher = $exchangeRateFetcher;
    }

    public function convert(Money $original, string $toCurrencyCode): Money {

        $targetCurrency = $this->money->in($toCurrencyCode);
        $exchangeRate = $this->exchangeRateFetcher->getFor($original->getCurrency(), $targetCurrency);

        $converted = $this->money->in($targetCurrency->getCode())->amount(
            bcmul($original->getAmount(), $exchangeRate->getRate(), static::$precision)
        );

        $this->history->saveConversion($original, $converted);

        return $converted;
    }

}