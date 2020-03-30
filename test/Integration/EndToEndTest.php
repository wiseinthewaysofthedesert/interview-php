<?php declare(strict_types=1);

namespace Test\Integration;

use Money\Converter;
use Money\Currencies\SupportedCurrencies;
use Money\MoneyFactory;
use Money\Remote\RemoteExchangeRateProvider;
use PHPUnit\Framework\TestCase;
use Test\Mocks\MockHistory;

class EndToEndTest extends TestCase
{
    private SupportedCurrencies $supportedCurrencies;
    private MoneyFactory $money;
    private Converter $converter;

    protected function setUp() : void
    {
        $this->supportedCurrencies = new SupportedCurrencies(['USD', 'EUR', 'CHF', 'YEN']);
        $this->money = new MoneyFactory($this->supportedCurrencies);

        $history = new MockHistory();
        $fetcher = new RemoteExchangeRateProvider();

        $this->converter = new Converter($this->money, $history, $fetcher);
    }

    public function testConversionThroughRemoteApi()
    {
        $money = $this->converter->convert(
            $this->money->in('USD')->amount('1'),
            'YEN'
        );

        $this->assertEquals('YEN', (string) $money->getCurrency());
        $this->assertTrue(is_numeric($money->getAmount()));
        $this->assertGreaterThan(0, (float) $money->getAmount());
    }
}
