<?php declare(strict_types=1);


namespace Money\History;


use DateTime;
use Money\History;
use Money\Money;
use RuntimeException;

class SessionHistory implements History
{
    private static string $sessionKey = __CLASS__ . '/History';

    public function __construct()
    {
        switch (session_status()) {
            case PHP_SESSION_DISABLED:
                throw new RuntimeException('Session needs to be enabled for SessionHistory to work.');
                break;
            case PHP_SESSION_NONE: {
                session_start();
            }
        }

        if (!isset($_SESSION[static::$sessionKey])) {
            $_SESSION[static::$sessionKey] = [];
        }
    }

    public function saveConversion(Money $from, Money $to): void
    {
        array_unshift($_SESSION[static::$sessionKey], new HistoricConversion($from, $to, new DateTime()));
    }

    /**
     * @return HistoricConversion[]
     */
    public function getHistory(): array
    {
        return $_SESSION[static::$sessionKey];
    }
}