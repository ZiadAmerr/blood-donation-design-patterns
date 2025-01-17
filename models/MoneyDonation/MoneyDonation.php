<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';

class MoneyDonation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(float $amount, string $date, int $donorId): bool {
        try {
            $sql = "INSERT INTO moneydonation (amount, date, donor_id, type) VALUES (?, ?, ?, 'money')";
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Failed to prepare statement");
            }

            $stmt->bind_param('dsi', $amount, $date, $donorId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error creating money donation: " . $e->getMessage());
            return false;
        }
    }

    public function fetchAll(): array {
        try {
            $sql = "SELECT d.name as donor_name, d.national_id, md.amount, md.date, md.type 
                    FROM moneydonation md 
                    JOIN donor d ON md.donor_id = d.person_id 
                    ORDER BY md.date DESC";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching donations: " . $e->getMessage());
            return [];
        }
    }
}