<?php
// File: /views/donations/donationAdminV.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/DonationAdminController.php';

$admin = new DonationAdmin();
$action = $_GET['action'] ?? 'showDonations';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($action) {
    case 'showDonations':
        $admin->showDonations();
        break;
        
    case 'editDonation':
        if ($id > 0) {
            $admin->editDonation($id);
        } else {
            echo "Invalid donation ID.";
        }
        break;
        
    case 'deleteDonation':
        if ($id > 0) {
            $admin->deleteDonation($id);
        } else {
            echo "Invalid donation ID.";
        }
        break;
        
    default:
        echo "Invalid action!";
        break;
}
?>
