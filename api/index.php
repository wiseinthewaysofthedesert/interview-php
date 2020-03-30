<?php declare(strict_types=1);

include "../vendor/autoload.php";

use Money\Converter;
use Money\Currencies\SupportedCurrencies;
use Money\Exception\ErrorFetchingExternalExchangeRate;
use Money\Exception\InvalidAmountException;
use Money\History;
use Money\History\SessionHistory;
use Money\MoneyFactory;
use Money\Remote\RemoteExchangeRateProvider;

$supportedCurrencies = new SupportedCurrencies(['USD', 'EUR', 'YEN', 'CHF']);

$history = new SessionHistory();

if (isset($_GET['clear'])) {
    session_destroy(); header('Location: index.php'); die();
}

$error = '';
if (isset($_POST['amount'])) {
    $money = new MoneyFactory($supportedCurrencies);
    $fetcher = new RemoteExchangeRateProvider();
    $converter = new Converter($money, $history, $fetcher);

    try {
        $from = $money->in($_POST['from'])->amount(trim($_POST['amount']));

        $converter->convert(
            $from,
            $_POST['to']
        );
    } catch (ErrorFetchingExternalExchangeRate $e) {
        $error = $e->getMessage();
    } catch (InvalidAmountException $e) {
        $error = $e->getMessage();
    }
}

function renderOptions(SupportedCurrencies $supportedCurrencies): string {
    $list = $supportedCurrencies->getList();

    $options = '';
    foreach ($list as $code) {
        $options .= "<option value=\"${code}\">${code}</option>";
    }

    return $options;
}

function renderHistory(History $history): string {
    $conversions = $history->getHistory();

    if (sizeof($conversions) === 0) return '';

    $tmpl = '<ul>';
    foreach ($conversions as $k => $conversion) {
        $tmpl .= sprintf('<li><span class="time">%s</span>%s => %s</li>',
            $conversion->getAt()->format('Y-m-d H:i:s'), $conversion->getFrom(), $conversion->getTo());
    }
    $tmpl .= '</ul>';

    return $tmpl;
}

$options = renderOptions($supportedCurrencies);
$conversionHistory = renderHistory($history);

echo <<<HTML
<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title>Currency Converter</title>
        <style type="text/css">
            body {color: #666}
           .error { color: darkred; margin-bottom: 10px;}
           .history ul { list-style-type: none }
           .history ul > li { padding: 5px 10px }
           .history ul > li:first-of-type{ color: #333; font-weight: bold }
           .history .time { display: inline-block; margin-right: 10px; font-size: 12px; }
</style>
    </head>
    <body>
        <h1>Currency Converter</h1>
            
        <div class="error">$error</div>
        <form method="post" action="index.php">
            <input type="text" name="amount">
            <select name="from">
                ${options}
            </select>
            <select name="to">
                ${options}
            </select>
            <input type="submit" value="Convert">
        </form>
        <div class="history">
            ${conversionHistory}
        </div>
    </body>
</html>
HTML;