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
        try {
            $this->money_donation_setter($moneyDonation);
            $moneyDonation->getReceipt();
            return true;
        } catch (Exception $e) {
            echo "Error processing money donation: " . $e->getMessage();
            return false;
        }
    }

    
    public function donateBlood(BloodDonation $bloodDonation): bool
    {
        // Ensure correct blood donation type
        if ($bloodDonation->blooddonationtype !== DonationType::BLOOD) {
            return false;
        }
        // Add blood to stock
        if ($bloodDonation->increaseBloodStock()) {
            // Save donation to database
            $bloodDonation->saveDonationToDatabase();
            return true;
        }

        return false;
    }

    public function donatePlasma(BloodDonation $bloodDonation): bool
    {
        // Ensure correct plasma donation type
        if ($bloodDonation->blooddonationtype !== DonationType::PLASMA) {
            return false;
        }

        // Add plasma to stock
        if ($bloodDonation->increaseBloodStock()) {
            // Save plasma donation to database
            $bloodDonation->saveDonationToDatabase();
            return true;
        }

        return false;
    }

}
?>