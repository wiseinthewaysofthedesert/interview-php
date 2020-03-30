<?php declare(strict_types=1);

error_reporting(E_ALL);

include "../vendor/autoload.php";

use Money\History\SessionHistory;

$history = new SessionHistory();

if (isset($_GET['clear'])) {
    session_destroy();
}

$rv = [];
foreach ($history->getHistory() as $record) {
    $rv[] = $record->toArray();
}

header('Content-type:application/json;charset=utf-8');
echo json_encode(["history" => $rv]);
die;