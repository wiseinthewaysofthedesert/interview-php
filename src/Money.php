<?php declare(strict_types=1);

namespace Money;

use Money\Exception\IncompatibleCurrencyException;
use Money\Exception\InvalidAmountException;


class Money {
    private string $amount;
    private Currency $currency;

    private static int $precision = 14;

    public function __construct(string $amount, Currency $currency)
    {
        if (!is_numeric($amount)) {
            throw new InvalidAmountException("The amount has to be a valid number");
        }

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function add(Money $money): Money {
        $this->ensureCurrencyCompatibility($money);

        $this->amount = bcadd($this->getAmount(), $money->getAmount(), static::$precision);

        return $this;
    }

    public function subtract(Money $money) : Money {
        $this->ensureCurrencyCompatibility($money);

        $this->amount = bcsub($this->getAmount(), $money->getAmount(), static::$precision);

        return $this;
    }

    public function equalTo(Money $money): bool
    {
        $this->ensureCurrencyCompatibility($money);

        return bccomp($this->getAmount(), $money->getAmount(), static::$precision) === 0;
    }

    public function getAmount() : string
    {
        return $this->amount;
    }

    public function getCurrency() : Currency
    {
        return $this->currency;
    }

    private function ensureCurrencyCompatibility(Money $other) : void {
        if (!$this->currency->equals($other->currency)) {
            throw new IncompatibleCurrencyException();
        }
    }

    public function __toString() : string
    {
        return number_format((float) $this->amount, 2, ',', '.') . ' ' . $this->currency;
    }

    public function toArray() : array {
        return [
            'amount' => number_format((float) $this->getAmount(), 2, '.', ''),
            'currency' => $this->currency->getCode(),
        ];
    }
}