<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/DonationAdminController.php';

$admin = new DonationAdmin(); // Instantiate the controller

$action = $_GET['action'] ?? 'showDonations'; // Default to show donations

switch ($action) {
    case 'showDonations':
        $admin->showDonations();
        break;
    case 'editDonation':
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $admin->editDonation($id);
        }
        break;
    case 'deleteDonation':
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $admin->deleteDonation($id);
        }
        break;
    default:
        echo "Invalid action!";
        break;
}
?>
