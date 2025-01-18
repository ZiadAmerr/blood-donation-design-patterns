<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodDonation.php";

interface IBeneficiary
{
    public function update( array $ownedBloodAmounts, array $ownedPlasmaAmounts): bool;
}
?>
