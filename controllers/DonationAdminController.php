<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Admins/BloodDonationModel.php';

class DonationAdmin
{
    public function showDonations(): void
    {
        // Fetch all donations from the database
        $donations = BloodDonationModel::getAllDonations();
        // Include the view that displays the list of donations
        include $_SERVER['DOCUMENT_ROOT'] . '/views/donations/list.php';
    }

    // Edit a specific donation record
    public function editDonation(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $donationType = $_POST['donation_type'];
            $status = $_POST['status'];

            // Update the donation record
            if (BloodDonationModel::updateDonation($id, $name, $donationType, $status)) {
                // Redirect to the donations list after successful update
                header("Location: index.php?action=showDonations");
                exit();
            }
        }

        // Fetch the current donation details for editing
        $donation = BloodDonationModel::getDonationById($id);
        // Include the view to edit the donation
        include $_SERVER['DOCUMENT_ROOT'] . '/views/donations/edit.php';
    }

    // Delete a specific donation record
    public function deleteDonation(int $id): void
    {
        // Delete the donation record
        if (BloodDonationModel::deleteDonation($id)) {
            // Redirect to the donations list after successful deletion
            header("Location: index.php?action=showDonations");
            exit();
        }
    }

}
?>
