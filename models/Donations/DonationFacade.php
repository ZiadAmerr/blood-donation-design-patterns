<?php

require_once 'Donor.php';
require_once 'Donation.php';
require_once 'MoneyDonation.php';
require_once 'BloodDonation.php';

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
        Donation::create(
            $bloodDonation->donor->person_id,
            "blood"
        );
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
            $bloodDonation->increaseBloodStock("SingletonBloodStockInstance");
            return true;
        } catch (Exception $e) {
            // Handle exception
            echo "Error processing blood donation: " . $e->getMessage();
            return false;
        }
    }
}
?>
