<?php
// File: IBeneficiary.php

interface IBeneficiary
{
    public function update(BloodTypeEnum $bloodType, float $amount): bool;
}
?>
