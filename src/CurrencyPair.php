<?php declare(strict_types=1);


namespace Money;

use DateTime;

class CurrencyPair
{
    private Currency $from;
    private Currency $to;

    private string $rate;
    private DateTime $rateAge;

    public function __construct(Currency $from, Currency $to, string $rate)
    {
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
        $this->rateAge = new DateTime();
    }

    public function getFrom(): Currency
    {
        return $this->from;
    }

    public function getTo(): Currency
    {
        return $this->to;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getRateAge(): DateTime
    {
        return $this->rateAge;
    }
}
