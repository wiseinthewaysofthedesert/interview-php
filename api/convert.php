<?php declare(strict_types=1);

include "../vendor/autoload.php";

use Money\Converter;
use Money\Currencies\SupportedCurrencies;
use Money\Exception\ErrorFetchingExternalExchangeRate;
use Money\Exception\InvalidAmountException;
use Money\Exception\UnsupportedCurrencyException;
use Money\History\SessionHistory;
use Money\MoneyFactory;
use Money\Remote\RemoteExchangeRateProvider;

$supportedCurrencies = new SupportedCurrencies(['USD', 'EUR', 'YEN', 'CHF']);

$history = new SessionHistory();

$money = new MoneyFactory($supportedCurrencies);
$fetcher = new RemoteExchangeRateProvider();
$converter = new Converter($money, $history, $fetcher);

if (isset($_GET['fakepost'])) {
    $_POST['amount'] = $_GET['amount'];
    $_POST['from'] = $_GET['from'];
    $_POST['to'] = $_GET['to'];
}

function sendError(array $msg): void {
    header('Content-type:application/json;charset=utf-8');
    echo json_encode(["error" => $msg]);
    die;
}

function sendSuccess(array $data): void{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode(["data" => $data]);
    die;
}

$errors = [];
if (isset($_POST['amount'])) {
    if (!is_numeric($_POST['amount'])) {
        $errors[] = ['amount' => 'Amount has to be a number'];
    }
} else {
    $errors[] = ['amount' => 'Missing amount'];
}

if (isset($_POST['from'])) {
    try {
        $money->in($_POST['from']);
    } catch (UnsupportedCurrencyException $e) {
        $errors[] = ['from' => sprintf('Unsupported currency (%s)', $_POST['from']),
            'data' => ['supported' => ['USD', 'EUR', 'YEN', 'CHF']]];
    }
} else {
    $errors[] = ['from' => 'Missing from currency'];
}

if (isset($_POST['to'])) {
    try {
        $money->in($_POST['to']);
    } catch (UnsupportedCurrencyException $e) {
        $errors[] = ['to' => sprintf('Unsupported currency (%s)', $_POST['to']),
            'data' => ['supported' => ['USD', 'EUR', 'YEN', 'CHF']]];
    }
} else {
    $errors[] = ['to' => 'Missing to currency'];
}

if ($errors) {
    sendError($errors);
}

try {
    $from = $money->in($_POST['from'])->amount(trim($_POST['amount']));

    $to = $converter->convert(
        $from,
        $_POST['to']
    );

    sendSuccess(['from' => $from->toArray(), 'to' => $to->toArray(), 'at' => new DateTime()]);
} catch (ErrorFetchingExternalExchangeRate $e) {
    $e->getMessage();
} catch (InvalidAmountException $e) {
    $e->getMessage();
}
