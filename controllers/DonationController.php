<?php
require_once __DIR__ . '/../models/donation/DonationFacade.php';
require_once __DIR__ . '/../controllers/commands/MakeBloodDonation.php';
require_once __DIR__ . '/../controllers/commands/MakeMoneyDonation.php';
require_once __DIR__ . '/../controllers/commands/GetListOfDonations.php';
require_once __DIR__ . '/../models/people/Donor.php';

class DonationController
{
    // Example: handle a request from a form or endpoint
    public function handleRequest(): void
    {
        // Suppose user’s action is passed via GET or POST
        $action = $_GET['action'] ?? 'list';

        // We need a Donor object — perhaps from $_GET or $_SESSION
        $donorId = $_GET['donor_id'] ?? 1; // fallback to 1 for demonstration
        $donor = new Donor((int)$donorId);

        switch ($action) {
            case 'make_blood_donation':
                $this->makeBloodDonation($donor);
                break;

            case 'make_money_donation':
                $this->makeMoneyDonation($donor);
                break;

            case 'list_donations':
            default:
                $this->listDonations($donor);
                break;
        }
    }

    // 1) Make a Blood Donation
    private function makeBloodDonation(Donor $donor): void
    {
        // The Command pattern approach:
        $command = new MakeBloodDonation(new DonationFacade($donor));
        $success = $command->execute(new DonationFacade($donor), $donor);

        if ($success) {
            echo "Blood donation successful!";
        } else {
            echo "Blood donation failed!";
        }
    }

    // 2) Make a Money Donation
    private function makeMoneyDonation(Donor $donor): void
    {
        $command = new MakeMoneyDonation(new DonationFacade($donor));
        $success = $command->execute(new DonationFacade($donor), $donor);

        if ($success) {
            echo "Money donation successful!";
        } else {
            echo "Money donation failed!";
        }
    }

    // 3) List all Donations
    private function listDonations(Donor $donor): void
    {
        $command = new GetListOfDonations(new DonationFacade($donor));
        $command->execute(new DonationFacade($donor), $donor);
    }
}
