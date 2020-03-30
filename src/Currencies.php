<?php declare(strict_types=1);


namespace Money;


interface Currencies
{
    public function contains(Currency $currency);
}