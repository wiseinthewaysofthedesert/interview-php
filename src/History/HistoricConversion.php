<?php declare(strict_types=1);


namespace Money\History;


use DateTime;
use Money\Money;

class HistoricConversion
{
    private Money $from;
    private Money $to;
    private DateTime $at;

    public function __construct(Money $from, Money $to, DateTime $at)
    {
        $this->from = $from;
        $this->to = $to;
        $this->at = $at;
    }

    public function getFrom(): Money
    {
        return $this->from;
    }

    public function getTo(): Money
    {
        return $this->to;
    }

    public function getAt(): DateTime
    {
        return $this->at;
    }

    public function toArray(): array {
        return [
            'from' => $this->from->toArray(),
            'to' => $this->to->toArray(),
            'at' => $this->at,
        ];
    }
}