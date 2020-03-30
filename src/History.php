<?php declare(strict_types=1);


namespace Money;


use Money\History\HistoricConversion;

interface History
{
    public function saveConversion(Money $from, Money $to): void;

    /**
     * @return HistoricConversion[]
     */
    public function getHistory(): array;

    public function clearHistory(): void;
}