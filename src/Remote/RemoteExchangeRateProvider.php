<?php declare(strict_types=1);


namespace Money\Remote;


use Money\Currency;
use Money\CurrencyPair;
use Money\ExchangeRateProvider;

/** written by candidate **/
class RemoteExchangeRateProvider implements ExchangeRateProvider
{
    public function getFor(Currency $from, Currency $to): CurrencyPair
    {
        $ch = curl_init();
        $data = array("from" => $from->getCode(), "to" => $to->getCode());
        $data_string = json_encode($data);

        curl_setopt($ch, CURLOPT_URL,"https://igfcc7aebk.execute-api.sa-east-1.amazonaws.com/default/money-conversion-interview-exercise-api");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $arrayResult = json_decode($result, true);

        $myCurrencyPair = new CurrencyPair(
            $from,
            $to,
            (string)$arrayResult['multiplier']
        );

        return $myCurrencyPair;
    }
}
