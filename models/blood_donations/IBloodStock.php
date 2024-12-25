<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

interface IBloodStock {
    public function addBeneficiary(IBeneficiaries $beneficiary): void;
    public function removeBeneficiary(IBeneficiaries $beneficiary): void;
    public function updateBloodStock(): void;
}

?>

