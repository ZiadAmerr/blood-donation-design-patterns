<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Donation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodStock.php';


class DonationFacade {
    private Donor $donor;
    public function __construct(Donor $donor) {
        $this->donor = $donor;
    }

    
    public function get_donor_donations(Donor $donor): void {
        $result = Model::fetchAll(
            "SELECT * FROM Donation WHERE donor_id = ?",
            "i",
            $donor->person_id
        );
        foreach ($result as $row) {
            echo "Donation ID: " . $row['id'] . ", Type: " . $row['type'] . "\n";
        }
    }


    
    public function donateMoney(MoneyDonation $moneyDonation): bool {
        
            $this->money_donation_setter($moneyDonation);
            $moneyDonation->getReceipt();
            return true;
        
    }

    
    public function donateBlood(BloodDonation $bloodDonation): bool {
    
            $code = $bloodDonation->increaseBloodStock();
            $bloodDonation->saveDonationToDatabase();
            return $code;

      
    }

    public function donatePlasma(BloodDonation $bloodDonation): bool {
        ///TODO: TO BE IMPLEMENTED...
        return true;
    }
}
?>
