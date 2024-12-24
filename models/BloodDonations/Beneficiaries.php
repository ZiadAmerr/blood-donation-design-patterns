<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

interface Beneficiaries {
    public function update();
    public function requestBlood(int $amount, BloodType $bloodType);
    
}

?>

