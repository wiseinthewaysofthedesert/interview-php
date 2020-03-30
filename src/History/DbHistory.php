<?php declare(strict_types=1);


namespace Money\History;

use Money\History;
use Money\Money;

/** written by candidate **/
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

    public function clearHistory(): void
    {
    }
}