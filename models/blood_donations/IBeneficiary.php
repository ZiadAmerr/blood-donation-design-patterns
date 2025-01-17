<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodDonation.php";

interface IBeneficiary
{
    public function update(DonationType $bloodDonationType, BloodTypeEnum $bloodType, float $amount): bool;
}
?>
