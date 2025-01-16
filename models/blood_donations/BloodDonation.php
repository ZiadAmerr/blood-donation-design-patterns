<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once __DIR__ . "/Donation.php";
require_once __DIR__ . "/BloodStock.php";
require_once __DIR__ . "/BloodType.php";
require_once __DIR__ . "/Donor.php";
require_once __DIR__ . "/DonorValidationTemplate.php";

class BloodDonation extends Donation
{
    private int $Number_of_liters;
    private string $BloodTypeEnum;
    private DonorValidationTemplate $validationTemplate;

    public function __construct(int $Donation_ID, Donor $donor, DateTime $datetime, int $Number_of_liters, string $BloodTypeEnum, DonorValidationTemplate $validationTemplate)
    {
        parent::__construct($Donation_ID, $donor, $datetime);
        $this->Number_of_liters = $Number_of_liters;
        $this->BloodTypeEnum = $BloodTypeEnum;
        $this->validationTemplate = $validationTemplate;
    }

    public function validate(): bool
    {
        try {
            $this->validationTemplate->templateMethod($this->$donor);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function increaseBloodStock(BloodStock $bloodStock): bool
    {
        try {
            if ($bloodStock->getBloodType() === $this->BloodTypeEnum) {
                $bloodStock->addToStock($this->Number_of_liters);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function donate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $bloodStock = BloodStock::getInstance();

        if ($this->increaseBloodStock($bloodStock)) {
            return true;
        } else {
            return false;
        }
    }
}

?>