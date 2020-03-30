<?php declare(strict_types=1);

namespace Test\Unit;

use Money\Converter;
use Money\Currencies\SupportedCurrencies;
use Money\Exception\UnsupportedCurrencyException;
use Money\MoneyFactory;
use PHPUnit\Framework\TestCase;
use Test\Mocks\MockExchangeRateFetcher;
use Test\Mocks\MockHistory;

class ConverterTest extends TestCase
{
    private SupportedCurrencies $supportedCurrencies;
    private MoneyFactory $money;
    private Converter $converter;

    protected function setUp() : void
    {
        $this->supportedCurrencies = new SupportedCurrencies(['USD', 'EUR', 'CHF', 'YEN']);
        $this->money = new MoneyFactory($this->supportedCurrencies);

        $history = new MockHistory();

        $fetcher = new MockExchangeRateFetcher();

        $fetcher->addPair('USD', 'EUR', '0.92');
        $fetcher->addPair('EUR', 'CHF', '1.06');
        $fetcher->addPair('USD', 'YEN', '111.54');

        $this->converter = new Converter($this->money, $history, $fetcher);
    }

    public function testSuccessfulConversion()
    {
        $money = $this->converter->convert(
            $this->money->in('USD')->amount('100'),
            'YEN'
        );

        $this->assertTrue($money->equalTo($this->money->in('YEN')->amount('11154')));
    }

    public function testConvertToUnsupportedCurrency() {
        $this->expectException(UnsupportedCurrencyException::class);

        $this->converter->convert(
            $this->money->in('USD')->amount('5.99'),
            'SIT'
        );
    }
}
