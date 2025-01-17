<?php
// File: MoneyStock.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class MoneyStock
{
    private static ?MoneyStock $instance = null;
    private float $totalCash;
    private mysqli $db;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->db = Database::getInstance();  // Connect to the database
        $this->initializeTotalCash();                  // Load totalCash from DB
    }

    // Get the single instance of MoneyStock
    public static function getInstance(): MoneyStock
    {
        if (self::$instance === null) {
            self::$instance = new MoneyStock();
        }
        return self::$instance;
    }

    // Initialize totalCash from the database
    private function initializeTotalCash(): void
    {
        $query = "SELECT totalCash FROM moneystock LIMIT 1";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->totalCash = (float)$row['totalCash'];
        } else {
            // Initialize if no record exists
            $this->totalCash = 0.0;
            $this->db->query("INSERT INTO moneystock (totalCash) VALUES (0.0)");
        }
    }

    // Add cash to the total amount and update the database
    public function addCash(float $amount): void
    {
        if ($amount > 0) {
            $this->totalCash += $amount;

            $stmt = $this->db->prepare("UPDATE moneystock SET totalCash = ?");
            $stmt->bind_param("d", $this->totalCash);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Retrieve the total cash amount
    public function getTotalCash(): float
    {
        return $this->totalCash;
    }

    // Prevent cloning of the instance
    private function __clone() {}
}
?>
