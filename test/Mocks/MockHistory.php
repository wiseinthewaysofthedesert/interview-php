<?php declare(strict_types=1);

namespace Test\Mocks;

use Money\History;
use Money\Money;

class MockHistory implements History
{
    public function saveConversion(Money $from, Money $to): void
    {
    }

    public function getHistory(): array
    {
        return [];
    }
}