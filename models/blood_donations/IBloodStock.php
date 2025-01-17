<?php
// File: IBloodStock.php

interface IBloodStock
{
    public function addBeneficiary(IBeneficiary $beneficiary): void;
    public function removeBeneficiary(IBeneficiary $beneficiary): void;
    public function notifyBeneficiaries(BloodTypeEnum $bloodType, float $amount): void;

}
