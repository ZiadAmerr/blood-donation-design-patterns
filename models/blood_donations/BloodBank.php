<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class BloodBank implements IBeneficiaries {

    public function __construct() 
    {
        $blood_bank = BloodStock::getInstance();
        $blood_bank->addBeneficiary($this); 
    }
    public function requestBlood(int $amount, BloodType $bloodType)
    {
        // check if blood is available from the bloodstock instance
    }
    public function update()
    {
        // update the blood stock singelton instance
    }

    
}

?>

