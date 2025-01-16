<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class WaitingPatients implements IBeneficiaries {
    public function __construct() 
    {
        // Get the singleton instance of BloodStock
        $blood_bank = BloodStock::getInstance();

        // Register this WaitingPatients object as a beneficiary of the BloodStock instance
        $blood_bank->addBeneficiary($this); 
    }

    public function requestBlood(int $amount, BloodTypeEnum $bloodType): bool
    {
        // Get the singleton instance of BloodStock
        $blood_bank = BloodStock::getInstance();

        // Check if the requested blood type and amount are available
        if ($blood_bank->blood_type === (string)$bloodType && $blood_bank->amount >= $amount) {
            $blood_bank->update($blood_bank->amount - $amountNeeded);
            return true;
        };

        return false;
    }
}


?>

