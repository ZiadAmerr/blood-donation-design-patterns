<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/MoneyDonation/Donation.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodDonation.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodStock.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodTypeEnum.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Donor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/DonorValidation/DonorValidationTemplate.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/DonorEligibility/DonorStateContext.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/Notifications/NotificationAdapters.php";



enum DonationType: string
{
    case PLASMA = 'Plasma';
    case BLOOD = 'Blood';

    // Method to get a string value of the enum
    public function getType(): string
    {
        return $this->value;
    }

    // Method to create an enum from a string value
    public static function fromString(string $value): ?self
    {
        return match ($value) {
            'Plasma' => self::PLASMA,
            'Blood' => self::BLOOD,
            default => null,
        };
    }
}

class BloodDonation extends Donation
{
    // donation id should be removed imo (or should be db generated..not entered by a user)
    private int $Number_of_liters;
    private BloodTypeEnum $BloodTypeEnum;
    private DonorValidationTemplate $validationTemplate;
    public Donor $donor; 
    public DonationType $blooddonationtype;
    public function __construct(Donor $donor, DonationType $blooddonationtype, DateTime $datetime, float $Number_of_liters, BloodTypeEnum $BloodTypeEnum, DonorValidationTemplate $validationTemplate)
    {
        // Assign values directly (No parent constructor call)
        $this->donor = $donor;
        $this->Number_of_liters = $Number_of_liters;
        $this->BloodTypeEnum = $BloodTypeEnum;
        $this->validationTemplate = $validationTemplate;
        $this->date = $datetime;
        $this->blooddonationtype = $blooddonationtype;
    }
    public function validate(): bool
    {
        return true;
        // try {
        //     $xml_ret = $this->validationTemplate->validateDonor($this->$donor);
        //     $donorStateContext = new DonorStateContext($this->$donor);
        //     if ($donorStateContext->getChangedSinceLastCheck()) {
                
        //         $smsAdapter = new SMSAdapter(new SmsService(), $this->$donor, $xml_ret);
        //         $smsAdapter->sendNotification();

        //         $whatsappAdapter = new WhatsAppAdapter(new WhatsAppService(), $this->$donor, $xml_ret);
        //         $whatsappAdapter->sendNotification();
        //     }
        //     return true;
        // } catch (Exception $e) {
        //     return false;
        // }
    }

    public function increaseBloodStock(): bool
    {
        try {
            BloodStock::getInstance()->addToStock($this->BloodTypeEnum, $this->Number_of_liters);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function saveDonationToDatabase(): void
{
    $sql = "INSERT INTO BloodDonation (donor_id, number_of_liters, blood_type, date) VALUES (?, ?, ?, ?)";

    self::executeUpdate(
        $sql,
        "idss",
        $this->donor->person_id,  
        $this->Number_of_liters,
        $this->BloodTypeEnum->value,  
        $this->date->format('Y-m-d H:i:s')
    );
}

    public function donate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $bloodStock = BloodStock::getInstance();

        if ($this->increaseBloodStock($bloodStock)) {
            $this->saveDonationToDatabase();
            return true;
        } else {
            return false;
        }
    }

    public static function fetchAllBloodDonations(): array
    {
        // Adjusted the query to include all columns
        $sql = "SELECT * FROM BloodDonation";
        return self::fetchAll($sql);
    }
    

}

?>