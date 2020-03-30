<?php declare(strict_types=1);


namespace Money\Remote;


use Money\Currency;
use Money\CurrencyPair;
use Money\Exception\ErrorFetchingExternalExchangeRate;
use Money\ExchangeRateProvider;

/** have the candidate implement this */
class RemoteExchangeRateProvider implements ExchangeRateProvider
{
    static string $url = 'https://igfcc7aebk.execute-api.sa-east-1.amazonaws.com/default/money-conversion-interview-exercise-api';

    public function getFor(Currency $from, Currency $to): CurrencyPair {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, static::$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['from' => $from->getCode(), 'to' => $to->getCode()]));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        $errnr = curl_errno($ch);
        $errormsg = curl_error($ch);

        curl_close($ch);

        if ($errnr) {
            throw new ErrorFetchingExternalExchangeRate($errormsg, $errnr);
        }

        $result = json_decode($result, true);

        if (isset($result['message'])) {
            throw new ErrorFetchingExternalExchangeRate($result['message']);
        }

        if (!is_array($result) || !isset($result['multiplier']) || !is_numeric($result['multiplier'])) {
            throw new ErrorFetchingExternalExchangeRate('Invalid multiplier received or missing', $errnr);
        }

        if (!isset($result['multiplier']) || !is_numeric($result['multiplier'])) {
            throw new ErrorFetchingExternalExchangeRate('No multiplier received');
        }

        return new CurrencyPair($from, $to, (string) $result['multiplier']);
    }
}