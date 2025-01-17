<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodStock.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/DonorValidation/BloodDonationValidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/DonationRemote.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/DonationRemote.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/DonationFacade.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/MakeBloodDonation.php';

class BloodDonationController
{
    public function getDonations(): array
    {
        // Fetch past blood donations from the database
        return BloodDonation::fetchAllBloodDonations("SELECT * FROM BloodDonation");
    }

    public function processDonation(array $data): array
    {
        $dr = DonationRemote::create((Donor::create(
            $data['donor_name'],
            $data['dob'],
            $data['national_id'],
            $data['address'],
            $data['phone']
        )));

        $bloodType = BloodTypeEnum::fromString($data['blood_type']);
        // Validate blood type to prevent passing NULL
        if ($bloodType === null) {
            throw new Exception("Invalid blood type provided: " . $data['blood_type']);
        }

        $numberOfLiters = floatval($data['number_of_liters']);

        $df = new DonationFacade($dr->donor);
        $dr->setCommand(new MakeBloodDonation($df));
        $result = $dr->execute($df, $dr->donor, new BloodDonation(
            $dr->donor,
            new DateTime(),
            $numberOfLiters,
            $bloodType,
            new BloodDonationValidation()
        ));

        if ($result) {
            return ['success' => true, 'message' => 'Blood donation was successful!'];
        } else {
            return ['success' => false, 'message' => 'Donation failed.'];
        }
        /*$donor = Donor::create(
            $data['donor_name'],
            $data['dob'],
            $data['national_id'],
            $data['address'],
            $data['phone']
        );

        $bloodType = BloodTypeEnum::fromString($data['blood_type']);
        // Validate blood type to prevent passing NULL
    if ($bloodType === null) {
        throw new Exception("Invalid blood type provided: " . $data['blood_type']);
    }
        $numberOfLiters = floatval($data['number_of_liters']);

        $bloodDonation = new BloodDonation(
            $donor,
            new DateTime(),
            $numberOfLiters,
            $bloodType,
            new BloodDonationValidation()
        );

        if ($bloodDonation->donate()) {
            return ['success' => true, 'message' => 'Blood donation was successful!'];
        } else {
            return ['success' => false, 'message' => 'Donation failed.'];
        }*/
    }
}
