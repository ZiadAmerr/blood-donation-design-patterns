<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodStock.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodTypeEnum.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/Hospitals.php';

class BeneficiaryController
{
    private Hospitals $hospital;

    public function __construct()
    {
        // Initialize Hospital and BloodStock instances
        $this->hospital = new Hospitals("City Hospital", "123 Main St", BloodStock::getInstance());
    }

    // Handle blood/plasma request form submissions
    public function handleRequest(): ?string
    {
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bloodType = $_POST['blood_type'];
            $amount = (float)$_POST['amount'];
            $type = $_POST['type'];

            if ($type === 'blood') {
                $success = $this->hospital->requestBlood(BloodTypeEnum::from($bloodType), $amount);
            } elseif ($type === 'plasma') {
                $success = $this->hospital->requestPlasma(BloodTypeEnum::from($bloodType), $amount);
            }

            $message = $success ? "Request successful!" : "Request failed. Not enough stock.";
        }

        return $message;
    }

    public function getBloodStock(): array
    {
        return $this->hospital->getBloodStockAmounts();
    }

    public function getPlasmaStock(): array
    {
        return $this->hospital->getPlasmaStockAmounts();
    }
}
