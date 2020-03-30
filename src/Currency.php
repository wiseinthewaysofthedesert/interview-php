<?php declare(strict_types=1);


namespace Money;

class Currency
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function equals(Currency $cur) : bool {
        return $this->code === $cur->code;
    }

    public function isSupported(Currencies $currencies) {
        return $currencies->contains($this);
    }

    public function amount(string $amount): Money {
        return new Money($amount, $this);
    }

    public function __toString()
    {
        return $this->code;
    }
}