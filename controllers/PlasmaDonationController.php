<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodStock.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/DonorValidation/PlasmaDonationValidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/DonationRemote.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/DonationFacade.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/MakePlasmaDonation.php';

class PlasmaDonationController
{
    public function getDonations(): array
    {
        // Fetch past plasma donations from the database
        return BloodDonation::fetchAllBloodDonations("SELECT * FROM BloodDonation");
    }

    public function getDonorName(int $donorId): string
    {
        return Donor::getDonorNameById($donorId);
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

        $volumeOfPlasma = floatval($data['volume_of_plasma']);

        $df = new DonationFacade($dr->donor);
        $dr->setCommand(new MakePlasmaDonation($df));
        $result = $dr->execute($df, $dr->donor, new BloodDonation(
            $dr->donor,
            DonationType::PLASMA,
            new DateTime(),
            $volumeOfPlasma,
            $bloodType,
            new PlasmaDonationValidation()
        ));

        if ($result) {
            return ['success' => true, 'message' => 'Plasma donation was successful!'];
        } else {
            return ['success' => false, 'message' => 'Donation failed.'];
        }
    }
}
