<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodStock.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/DonorValidation/BloodDonationValidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';

class BloodDonationController
{
    public function getDonations(): array
    {
        // Fetch past blood donations from the database
        return BloodDonation::fetchAllBloodDonations("SELECT * FROM BloodDonation");
    }

    public function processDonation(array $data): array
    {
        $donor = Donor::create(
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
        }
    }
}
