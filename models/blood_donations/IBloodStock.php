<?php
// File: IBloodStock.php

interface IBloodStock
{
    public function addBeneficiary(IBeneficiaries $beneficiary): void;
    public function removeBeneficiary(IBeneficiaries $beneficiary): void;
    public function updateBloodStock(): void;
}
