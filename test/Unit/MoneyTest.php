<?php declare(strict_types=1);

namespace Test\Unit;

use Money\Currencies\SupportedCurrencies;
use Money\Exception\UnsupportedCurrencyException;
use Money\MoneyFactory;
use PHPUnit\Framework\TestCase;

use Money\Money;
use Money\Exception\InvalidAmountException;

final class MoneyTestCase extends TestCase
{
    private SupportedCurrencies $supportedCurrencies;
    private MoneyFactory $money;

    protected function setUp() : void
    {
        $this->supportedCurrencies = new SupportedCurrencies(['USD', 'EUR', 'CHF']);
        $this->money = new MoneyFactory($this->supportedCurrencies);
    }

    public function testMoneyIsCreatedFromCurrencyAndAmount(): void
    {
        $this->assertInstanceOf(
            Money::class,
            $this->money->in('USD')->amount('10.99')
        );
    }

    public function testMoneyCannotBeCreatedFromInvalidAmount(): void
    {
        $this->expectException(InvalidAmountException::class);

        $this->money->in('USD')->amount('börek');
    }

    public function testAddTwoAmountsTogetherSuccess(): void {

        $this->assertTrue($this->money->in('EUR')->amount('4.99')
            ->equalTo(
                $this->money->in('EUR')->amount('2')->add(
                $this->money->in('EUR')->amount('2.99')
            )
        ));
    }

    public function testSubtractTwoAmountsSuccess(): void {

        $this->assertTrue($this->money->in('CHF')->amount('100.32')
            ->equalTo(
                $this->money->in('CHF')->amount('122.42')->subtract(
                    $this->money->in('CHF')->amount('22.1')
                )
            ));
    }

    public function testCurrencyNotSupported() : void {
        $this->expectException(UnsupportedCurrencyException::class);

        $this->money->in('UAH')->amount('4000');
    }
}