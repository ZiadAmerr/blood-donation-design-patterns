<?php
require_once 'address_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Donor.php";

class RegistrationService {
    public static function registerDonor($postData): array {
        $response = ['success' => false, 'message' => ''];

        try {
            // Extract POST data
            $name = $postData['name'];
            $date_of_birth = $postData['date_of_birth'];
            $national_id = $postData['national_id'];

            // Normalize parent_address_id to ensure it's either null or an integer
            $parent_address_id = isset($postData['parent_address_id']) && $postData['parent_address_id'] !== ''
                ? (int)$postData['parent_address_id']
                : null;

            $new_address_name = $postData['new_address_name'] ?? null;

            // Step 1: Handle address creation or selection
            if (!empty($new_address_name)) {
                // Create a new address with optional parent_id
                $address = Address::create($new_address_name, $parent_address_id);
            } elseif (!empty($postData['selected_address'])) {
                // Use existing address selected from the dropdown
                $address = new Address((int)$postData['selected_address']);
            } else {
                throw new Exception("Please choose or create an address.");
            }

            // Step 2: Create Donor
            Donor::create($name, $date_of_birth, $national_id, $address->id);

            $response['success'] = true;
            $response['message'] = "Registration successful!";
        } catch (Exception $e) {
            $response['message'] = "Registration failed: " . $e->getMessage();
        }

        return $response;
    }
}

?>
