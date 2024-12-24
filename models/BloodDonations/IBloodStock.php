<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

interface IBloodStock {
    public function addBeneficiary(Beneficiaries $beneficiary): void;
    public function removeBeneficiary(Beneficiaries $beneficiary): void;
    public function updateBloodStock(): void;
    
}

?>

