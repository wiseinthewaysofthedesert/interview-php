<?php declare(strict_types=1);


namespace Money;


use Money\Currencies\SupportedCurrencies;
use Money\Exception\UnsupportedCurrencyException;

class MoneyFactory
{
    private SupportedCurrencies $supportedCurrencies;

    public function __construct(SupportedCurrencies $supportedCurrencies)
    {
        $this->supportedCurrencies = $supportedCurrencies;
    }

    public function in(string $code) : Currency {
        $c = new Currency($code);

        if (!$c->isSupported($this->supportedCurrencies)) {
            throw new UnsupportedCurrencyException("Unsupported currency: " . $code);
        }

        return $c;
    }
}