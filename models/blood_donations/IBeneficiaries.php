<?php
// File: IBeneficiaries.php

interface IBeneficiaries
{
    /**
     * requestBlood()
     * Requests a certain amount of a specific BloodType from the stock.
     */
    public function requestBlood(int $amount, BloodTypeEnum $bloodType);

    /**
     * update()
     * Called automatically when BloodStock notifies its observers.
     */
    public function update();
}
