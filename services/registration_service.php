<?php

require_once 'address_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Donor.php";

class RegistrationService {
    public static function registerDonor(array $postData): array {
        $response = ['success' => false, 'message' => ''];

        try {
            // Validate required fields
            $required_fields = ['name', 'date_of_birth', 'phone_number', 'national_id', 'username', 'password', 'blood_type', 'weight'];
            foreach ($required_fields as $field) {
                if (empty($postData[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            // Extract and sanitize input data
            $name = htmlspecialchars(trim($postData['name']));
            $date_of_birth = htmlspecialchars(trim($postData['date_of_birth']));
            $phone_number = htmlspecialchars(trim($postData['phone_number']));
            $national_id = htmlspecialchars(trim($postData['national_id']));
            $username = htmlspecialchars(trim($postData['username']));
            $password = htmlspecialchars(trim($postData['password']));
            $blood_type = htmlspecialchars(trim($postData['blood_type']));
            $weight = floatval($postData['weight']); // Ensure weight is a float

            // Extract selected diseases
            $diseases = isset($postData['diseases']) ? array_map('intval', $postData['diseases']) : [];

            // Address Handling
            $new_address_name = $postData['new_address_name'] ?? null;
            $parent_address_id = !empty($postData['parent_address_id']) ? (int)$postData['parent_address_id'] : null;
            $selected_address = !empty($postData['selected_address']) ? (int)$postData['selected_address'] : null;

            // Step 1: Handle Address (get existing or create new)
            $address_id = AddressService::getOrCreateAddress($new_address_name, $parent_address_id, $selected_address);

            // Step 2: Register Donor with weight and diseases
            $donor_id = Donor::create(
                $name,
                $date_of_birth,
                $phone_number,
                $national_id,
                $username,
                $password, // Using MD5
                $address_id,
                $blood_type,
                $weight,
                $diseases // List of disease IDs
            );

            $response['success'] = true;
            $response['message'] = "Registration successful!";
            $response['donor_id'] = $donor_id;
        } catch (Exception $e) {
            $response['message'] = "Registration failed: " . $e->getMessage();
        }

        return $response;
    }
}
?>
