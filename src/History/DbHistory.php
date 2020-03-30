<?php declare(strict_types=1);


namespace Money;

use Money\History\HistoricConversion;

/** have the candidate implement this */
class DbHistory implements History
{
    public function saveConversion(Money $from, Money $to): void
    {
    }

    /**
     * @return HistoricConversion[]
     */
    public function getHistory(): array
    {
        return [];
    }
}