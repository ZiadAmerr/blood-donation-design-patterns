<?php
// File: MoneyStock.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class MoneyStock
{
    private static ?MoneyStock $instance = null;
    private float $totalCash;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->totalCash = 0.0;
    }

    // Get the single instance of MoneyStock
    public static function getInstance(): MoneyStock
    {
        if (self::$instance === null) {
            self::$instance = new MoneyStock();
        }
        return self::$instance;
    }

    // Add cash to the total amount
    public function addCash(float $amount): void
    {
        if ($amount > 0) {
            $this->totalCash += $amount;
        }
    }

    // Retrieve the total cash amount
    public function getTotalCash(): float
    {
        return $this->totalCash;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing the instance
    private function __wakeup() {}
}
?>
