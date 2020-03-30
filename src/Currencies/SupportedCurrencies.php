<?php declare(strict_types=1);


namespace Money\Currencies;


use Money\Currencies;
use Money\Currency;

class SupportedCurrencies implements Currencies
{
    private array $supportedCurrencies;

    /**
     * SupportedCurrencies constructor.
     * @param string[] supportedCurrencies
     */
    public function __construct(array $supportedCurrencies)
    {
        $this->supportedCurrencies = $supportedCurrencies;
    }

    public function contains(Currency $currency)
    {
        return in_array($currency->getCode(), $this->supportedCurrencies);
    }

    public function getList(): array {
        return $this->supportedCurrencies;
    }
}