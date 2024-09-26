<?php declare(strict_types=1);

namespace Test\Integration;

use Exception;
use Money\Currency;
use Money\History\DbHistory;
use Money\History\HistoricConversion;
use Money\Money;
use PHPUnit\Framework\TestCase;

class DbHistoryTest extends TestCase
{
    private DbHistory $history;
    protected function setUp() : void
    {
        $this->history = new DbHistory();
    }

    public function testSuccessfullySaveAndRetrieve()
    {
        $from = new Money('10.00', new Currency('EUR'));
        $to = new Money('12.33', new Currency('US'));

        try {
            $this->history->saveConversion($from, $to);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $history = $this->history->getHistory();

        $originalCount = count($history);

        /** @var HistoricConversion $latest */
        $latest = array_pop($history);

        $this->assertTrue(count($history) === $originalCount - 1);

        $this->assertTrue($latest->getFrom()->equalTo($from), "Last record 'from' not equal to last conversion");
        $this->assertTrue($latest->getTo()->equalTo($to), "Last record 'to' not equal to last conversion");
    }

    public function testCheckIfDbSaveSuccessful()
    {
        $this->history->clearHistory();

        $from = new Money('10.00', new Currency('EUR'));
        $to = new Money('12.33', new Currency('US'));

        $this->history->saveConversion($from, $to);

        $history = $this->history->getHistory();

        $this->assertTrue(count($history) > 0, 'not saved in database');
    }

    public function testHistoryClearSuccess() {
        $from = new Money('10.00', new Currency('EUR'));
        $to = new Money('12.33', new Currency('US'));

        $this->history->saveConversion($from, $to);

        $history = $this->history->getHistory();

        $this->assertTrue(count($history) > 0, 'not saved in database');

        $this->history->clearHistory();
        $history = $this->history->getHistory();

        $this->assertTrue(count($history) === 0, 'cache not clear');
    }
}
