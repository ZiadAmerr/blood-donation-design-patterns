<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Donation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodDonation.php';

class DonationFacade {
    private Donor $donor;
    public function __construct(Donor $donor) {
        $this->donor = $donor;
    }

    
    public function update_donations_list(Donation $donation, Donor $donor): void {
        Donation::create($donor->person_id, $donation->type);
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

    
    public function money_donation_setter(MoneyDonation $moneyDonation): void {
        Donation::create(
            $moneyDonation->donor->person_id,
            "money"
        );
    }

    
    public function blood_donation_setter(BloodDonation $bloodDonation): void {
        $bloodDonation->saveDonationToDatabase();
    }

    
    public function donateMoney(MoneyDonation $moneyDonation): bool {
        try {
            $this->money_donation_setter($moneyDonation);
            $moneyDonation->getReceipt();
            return true;
        } catch (Exception $e) {
            echo "Error processing money donation: " . $e->getMessage();
            return false;
        }
    }

    
    public function donateBlood(BloodDonation $bloodDonation): bool {
        try {
            $this->blood_donation_setter($bloodDonation);
            return $bloodDonation->increaseBloodStock("SingletonBloodStockInstance");
            //return true;
        } catch (Exception $e) {
            // Handle exception
            echo "Error processing blood donation: " . $e->getMessage();
            return false;
        }
    }

    public function donatePlasma(BloodDonation $bloodDonation): bool {
        ///TODO: TO BE IMPLEMENTED...
        return true;
    }
}
?>
