<?php declare(strict_types=1);

namespace Money\History;

use Exception;
use Money\Currency;
use Money\History;
use Money\Money;
use PDO;

/** written by candidate **/
class DbHistory implements History
{
    /**
     * @param Money $from
     * @param Money $to
     *
     * @return void
     */
    public function saveConversion(Money $from, Money $to): void
    {
        $dbh = $this->getHandler();

        $now = new \DateTime();

        $data = [
            "from_amount" => $from->getAmount(),
            "from_currency" => $from->getCurrency(),
            "to_currency" => $to->getCurrency(),
            "to_amount" => $to->getAmount(),
            "date_transaction" => $now->format('Y-m-d H:i:s'),
        ];

        $sql = 'INSERT INTO history (
                    from_currency, 
                    to_currency, 
                    date_transaction, 
                    from_amount, 
                    to_amount
                ) VALUES (
                    :from_currency, 
                    :to_currency, 
                    :date_transaction,
                    :from_amount, 
                    :to_amount
                )';

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * @return HistoricConversion[]
     * @throws Exception
     */
    public function getHistory(): array
    {
        $dbh = $this->getHandler();

        $results = [];

        foreach ($dbh->query(
            'SELECT * FROM history ORDER BY date_transaction DESC',
        ) as $row) {
            $results [] = new HistoricConversion(
                new Money(
                    $row['from_amount'],
                    new Currency($row['from_currency'])),
                new Money(
                    $row['to_amount'],
                    new Currency($row['to_currency'])),
                new \DateTime($row['date_transaction'])
            );
        }

        return $results;
    }

    /**
     * @return void
     */
    public function clearHistory(): void
    {
        $dbh = $this->getHandler();

        $sql = 'DELETE FROM history';

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return PDO
     */
    private function getHandler()
    {
        return new PDO(
            'mysql:host=mysql-server;dbname=CurrencyConverterDB',
            'test',
            'password'
        );
    }
}
