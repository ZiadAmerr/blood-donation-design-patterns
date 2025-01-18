<?php
// File: /controllers/DonationAdminController.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Admins/BloodDonationModel.php';

class DonationAdmin
{
    /**
     * Display all blood donations.
     */
    public function showDonations(): void
    {
        $donations = BloodDonationModel::getAllDonations();
        include $_SERVER['DOCUMENT_ROOT'] . '/views/donations/list.php';
    }

    /**
     * Edit a specific donation record.
     */
    public function editDonation(int $id): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $donationType = trim($_POST['donation_type'] ?? '');
            $status = trim($_POST['status'] ?? '');

            if ($name === '' || $donationType === '' || $status === '') {
                $error = "All fields are required.";
            } else {
                $updateSuccess = BloodDonationModel::updateDonation($id, $name, $donationType, $status);
                
                if ($updateSuccess) {
                    header("Location: /views/donations/donationAdminV.php?action=showDonations&message=update_success");
                    exit();
                } else {
                    $error = "Failed to update the donation.";
                }
            }
        }

        $donation = BloodDonationModel::getDonationById($id);
        if (!$donation) {
            echo "Donation not found.";
            exit();
        }

        include $_SERVER['DOCUMENT_ROOT'] . '/views/donations/edit.php';
    }

    /**
     * Delete a specific donation record.
     */
    public function deleteDonation(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleteSuccess = BloodDonationModel::deleteDonation($id);
            
            if ($deleteSuccess) {
                header("Location: /views/donations/donationAdminV.php?action=showDonations&message=delete_success");
                exit();
            } else {
                echo "Failed to delete the donation.";
                exit();
            }
        } else {
            $donation = BloodDonationModel::getDonationById($id);
            if (!$donation) {
                echo "Donation not found.";
                exit();
            }

            include $_SERVER['DOCUMENT_ROOT'] . '/views/donations/delete_confirm.php';
        }
    }
}
?>
