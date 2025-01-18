<?php
// Include the necessary controllers or models
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/DonationAdminController.php';

$admin = new DonationAdmin(); // Instantiate the controller

// Check for POST requests and handle the actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Handle POST actions (Edit, Delete, Request)
    switch ($action) {
        case 'editDonation':
            $id = $_POST['id'] ?? 0;
            if ($id) {
                $admin->editDonation($id);
            }
            break;
        case 'deleteDonation':
            $id = $_POST['id'] ?? 0;
            if ($id) {
                $admin->deleteDonation($id);
            }
            break;
        default:
            echo "Invalid action!";
            break;
    }
} else {
    // If no POST request, redirect to the donations list
    header('Location: views/');
    exit();
}
?>
